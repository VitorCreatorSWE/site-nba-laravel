<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\Player;
use App\Models\Team;
use App\Services\ApiSportsService;
use App\Services\BalldontlieService;
use App\Services\PlayerSyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class NbaSync extends Command
{
    protected $signature = 'nba:sync
        {--only= : teams, games, players, all}
        {--season= : Temporada para a API-Sports (padrao: config)}
        {--fresh : Limpa o cache antes de sincronizar}
        {--source=balldontlie : balldontlie ou apisports para os jogos}';

    protected $description = 'Sincroniza times, jogos e jogadores das APIs externas';

    public function handle(
        BalldontlieService $balldontlie,
        ApiSportsService $apiSports,
        PlayerSyncService $playerSync,
    ): int {
        $only = $this->option('only') ?: 'all';

        if ($this->option('fresh')) {
            Cache::flush();
            $this->info('Cache limpo.');
        }

        if (in_array($only, ['teams', 'all'], true)) {
            $this->syncTeams($balldontlie);
        }

        if (in_array($only, ['games', 'all'], true)) {
            $this->option('source') === 'apisports'
                ? $this->syncGamesFromApiSports($apiSports)
                : $this->syncGamesFromBalldontlie($balldontlie);
        }

        if (in_array($only, ['players', 'all'], true)) {
            $this->syncPlayers($playerSync);
        }

        $this->newLine();
        $this->info('=== Status Final ===');
        $this->table(
            ['Tabela', 'Registros'],
            [
                ['teams', Team::count()],
                ['games', Game::count()],
                ['players', Player::count()],
            ]
        );

        return self::SUCCESS;
    }

    private function syncTeams(BalldontlieService $balldontlie): void
    {
        $this->info('Sincronizando times (whitelist de 30)...');
        $synced = $balldontlie->syncTeams();

        $strays = Team::whereNotIn('abbreviation', Team::currentAbbreviations())->count();

        $this->info("  {$synced} times sincronizados.");
        $this->info('  Times no banco: ' . Team::count());

        if ($strays > 0) {
            $this->warn("  {$strays} times fora da whitelist (extintos). Rode nba:clean-db.");
        }

        $this->callSilently('nba:fix-teams');
    }

    private function syncGamesFromBalldontlie(BalldontlieService $balldontlie): void
    {
        $season = (int) config('nba.seasons.current', 2025);

        $this->info("Sincronizando jogos da balldontlie (temporada {$season})...");

        $cursor = 0;
        $count = 0;
        $pages = 0;

        while ($pages < 60) {
            $payload = $balldontlie->getGamesPageWithCursor($season, $cursor);
            $games = $payload['data'] ?? [];

            if ($games === []) {
                break;
            }

            $count += $this->persistBalldontlieGames($games, $season);
            $pages++;

            $next = $payload['meta']['next_cursor'] ?? null;

            if ($next === null || (int) $next === $cursor) {
                break;
            }

            $cursor = (int) $next;
        }

        $this->info("  {$count} jogos gravados em {$pages} paginas.");
    }

    private function persistBalldontlieGames(array $games, int $season): int
    {
        $count = 0;

        foreach ($games as $game) {
            $home = Team::where('api_id', $game['home_team']['id'] ?? null)->first();
            $away = Team::where('api_id', $game['visitor_team']['id'] ?? null)->first();

            if (! $home || ! $away) {
                continue;
            }

            Game::updateOrCreate(
                ['api_id' => $game['id']],
                [
                    'home_team_id' => $home->id,
                    'away_team_id' => $away->id,
                    'game_date' => $game['datetime'] ?? $game['date'],
                    'arena' => $home->arena_name,
                    'status' => $this->mapBalldontlieStatus($game['status_state'] ?? ''),
                    'home_score' => (int) ($game['home_team_score'] ?? 0),
                    'away_score' => (int) ($game['visitor_team_score'] ?? 0),
                    'season' => (string) ($game['season'] ?? $season),
                ]
            );

            $count++;
        }

        return $count;
    }

    private function syncGamesFromApiSports(ApiSportsService $apiSports): void
    {
        $season = (int) ($this->option('season') ?: $apiSports->season());

        $this->info("Sincronizando jogos da api-sports (temporada {$season})...");
        $this->warn('  A API-Sports limita o plano gratuito a 10 requisicoes por minuto.');

        $count = $apiSports->syncGames($season);

        $this->info("  {$count} jogos gravados.");
    }

    private function syncPlayers(PlayerSyncService $playerSync): void
    {
        $season = $this->option('season') ?: null;

        $this->info('Sincronizando jogadores da api-sports...');

        $stats = $playerSync->sync($season === null ? null : (int) $season, function ($abbreviation, $count, $fallback = false) {
            $this->line(sprintf('  %s: %d jogadores%s', $abbreviation, $count, $fallback ? ' (balldontlie)' : ''));
        });

        $this->info(sprintf(
            '  times=%d  inseridos=%d  atualizados=%d  pulados=%d',
            $stats['teams'],
            $stats['inserted'],
            $stats['updated'],
            $stats['skipped'],
        ));

        if ($stats['fallback_teams'] > 0) {
            $this->warn(
                "  {$stats['fallback_teams']} times vieram da balldontlie "
                . '(cota diaria da api-sports esgotada ou indisponivel).'
            );
        }
    }

    private function mapBalldontlieStatus(string $state): string
    {
        return match ($state) {
            'final' => 'finished',
            'pre', '1st half', '2nd half', 'halftime', 'end overtime' => 'live',
            default => 'scheduled',
        };
    }
}
