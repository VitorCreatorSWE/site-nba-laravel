<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Support\Str;

class PlayerSyncService
{
    public function __construct(
        private BalldontlieService $balldontlie,
        private ApiSportsService $apiSports,
    ) {
    }

    public function sync(?int $season = null, ?callable $progress = null): array
    {
        $season ??= (int) config('services.apisports.season', 2024);

        $stats = [
            'teams' => 0,
            'inserted' => 0,
            'updated' => 0,
            'skipped' => 0,
            'fallback_teams' => 0,
        ];

        $balldontlie = $this->indexBalldontliePlayers();

        $stats['balldontlie'] = count($balldontlie['by_team']);

        foreach (Team::currentTeamData() as $abbreviation => $meta) {
            $team = Team::where('abbreviation', $abbreviation)->first();

            if (! $team) {
                $stats['skipped']++;

                continue;
            }

            $players = $this->apiSports->getPlayers($meta['apisports_id'], $season);
            $usedFallback = false;

            if ($players === []) {
                $players = $balldontlie['by_team'][$abbreviation] ?? [];
                $usedFallback = $players !== [];

                if ($usedFallback) {
                    $stats['fallback_teams']++;
                }
            }

            if ($progress) {
                $progress($abbreviation, count($players), $usedFallback);
            }

            foreach ($players as $payload) {
                $result = $usedFallback
                    ? $this->upsertFromBalldontlie($payload, $team)
                    : $this->upsertFromApiSports($payload, $team, $season, $balldontlie['by_name']);

                $stats[$result]++;
            }

            $stats['teams']++;
        }

        return $stats;
    }

    /**
     * @return array{by_name: array<string, array>, by_team: array<string, array>}
     */
    private function indexBalldontliePlayers(): array
    {
        $byName = [];
        $byTeam = [];

        try {
            $players = $this->balldontlie->getAllPlayers();
        } catch (\Throwable $e) {
            report($e);

            return ['by_name' => [], 'by_team' => []];
        }

        foreach ($players as $payload) {
            $abbreviation = strtoupper(trim((string) ($payload['team']['abbreviation'] ?? '')));

            if ($abbreviation !== '' && in_array($abbreviation, Team::currentAbbreviations(), true)) {
                $byTeam[$abbreviation][] = $payload;
            }

            $key = $this->nameKey($payload['first_name'] ?? '', $payload['last_name'] ?? '');

            if ($key !== '' && ! isset($byName[$key])) {
                $byName[$key] = $payload;
            }
        }

        return ['by_name' => $byName, 'by_team' => $byTeam];
    }

    private function nameKey(?string $first, ?string $last): string
    {
        $first = Str::lower(preg_replace('/[^a-z]/i', '', (string) $first) ?? '');
        $last = Str::lower(preg_replace('/[^a-z]/i', '', (string) $last) ?? '');

        if ($first === '' || $last === '') {
            return '';
        }

        return $first . '|' . $last;
    }

    private function upsertFromApiSports(array $payload, Team $team, int $season, array $byName): string
    {
        $apisportsId = (int) ($payload['id'] ?? 0);

        if ($apisportsId === 0) {
            return 'skipped';
        }

        $firstName = trim((string) ($payload['firstname'] ?? ''));
        $lastName = trim((string) ($payload['lastname'] ?? ''));

        if ($firstName === '' || $lastName === '') {
            return 'skipped';
        }

        $bdl = $byName[$this->nameKey($firstName, $lastName)] ?? null;
        $attributes = $this->apiSportsAttributes($payload, $team, $season, $bdl);

        $existing = Player::where('apisports_id', $apisportsId)->first()
            ?? Player::where('api_id', $attributes['api_id'])->whereNotNull('api_id')->first();

        if ($existing) {
            $existing->fill($attributes)->save();

            return 'updated';
        }

        Player::create($attributes);

        return 'inserted';
    }

    /**
     * Fallback used when the api-sports quota is exhausted. Its /players
     * endpoint ignores every team filter on the free plan, so the payload is
     * the all-time roster of the franchise rather than the current one. Those
     * rows are therefore stored as inactive and without a season, which keeps
     * the public roster pages honest until the api-sports sync runs again.
     */
    private function upsertFromBalldontlie(array $payload, Team $team): string
    {
        $apiId = (int) ($payload['id'] ?? 0);
        $firstName = trim((string) ($payload['first_name'] ?? ''));
        $lastName = trim((string) ($payload['last_name'] ?? ''));

        if ($apiId === 0 || $firstName === '' || $lastName === '') {
            return 'skipped';
        }

        $attributes = [
            'api_id' => $apiId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'position' => $payload['position'] ?? null,
            'height' => $payload['height'] ?? null,
            'weight' => isset($payload['weight']) ? (int) $payload['weight'] : null,
            'country' => $payload['country'] ?? null,
            'college' => $payload['college'] ?? null,
            'draft_year' => $payload['draft_year'] ?? null,
            'draft_round' => $payload['draft_round'] ?? null,
            'draft_number' => $payload['draft_number'] ?? null,
            'is_active' => false,
            'season' => null,
            'team_id' => $team->id,
        ];

        $existing = Player::where('api_id', $apiId)->first();

        if ($existing && $existing->apisports_id !== null) {
            $this->enrichFromBalldontlie($existing, $payload);

            return 'updated';
        }

        if ($existing) {
            $existing->fill($attributes)->save();

            return 'updated';
        }

        Player::create($attributes);

        return 'inserted';
    }

    /**
     * Rows already sourced from the api-sports roster keep their team,
     * season and active flag, because balldontlie reports the current
     * franchise while api-sports reports a specific season. Only the
     * biographical gaps are filled in.
     */
    private function enrichFromBalldontlie(Player $player, array $payload): void
    {
        $player->fill([
            'college' => $player->college ?? ($payload['college'] ?? null),
            'country' => $player->country ?? ($payload['country'] ?? null),
            'height' => $player->height ?? ($payload['height'] ?? null),
            'weight' => $player->weight ?? (isset($payload['weight']) ? (int) $payload['weight'] : null),
            'position' => $player->position ?? ($payload['position'] ?? null),
            'draft_year' => $player->draft_year ?? ($payload['draft_year'] ?? null),
            'draft_round' => $player->draft_round ?? ($payload['draft_round'] ?? null),
            'draft_number' => $player->draft_number ?? ($payload['draft_number'] ?? null),
        ])->save();
    }

    private function apiSportsAttributes(array $payload, Team $team, int $season, ?array $bdl): array
    {
        $standard = $payload['leagues']['standard'] ?? [];
        $height = $payload['height'] ?? [];
        $weight = $payload['weight'] ?? [];
        $birth = $payload['birth'] ?? [];
        $nba = $payload['nba'] ?? [];

        $feet = $height['feets'] ?? null;
        $inches = $height['inches'] ?? null;

        return [
            'api_id' => $bdl['id'] ?? null,
            'apisports_id' => (int) $payload['id'],
            'first_name' => trim((string) $payload['firstname']),
            'last_name' => trim((string) $payload['lastname']),
            'position' => $standard['pos'] ?? $bdl['position'] ?? null,
            'jersey_number' => isset($standard['jersey']) ? (string) $standard['jersey'] : null,
            'height' => $bdl['height'] ?? ($feet !== null ? $feet . '-' . $inches : null),
            'height_meters' => isset($height['meters']) ? round((float) $height['meters'], 2) : null,
            'weight' => isset($bdl['weight']) ? (int) $bdl['weight'] : null,
            'weight_kg' => isset($weight['kilograms']) ? round((float) $weight['kilograms'], 2) : null,
            'birth_date' => $birth['date'] ?? null,
            'country' => $birth['country'] ?? $bdl['country'] ?? null,
            'college' => $payload['college'] ?? $bdl['college'] ?? null,
            'draft_year' => $bdl['draft_year'] ?? null,
            'draft_round' => $bdl['draft_round'] ?? null,
            'draft_number' => $bdl['draft_number'] ?? null,
            'career_start' => isset($nba['start']) ? (int) $nba['start'] : null,
            'seasons_played' => isset($nba['pro']) ? (int) $nba['pro'] : null,
            'is_active' => (bool) ($standard['active'] ?? true),
            'season' => (string) $season,
            'team_id' => $team->id,
        ];
    }
}
