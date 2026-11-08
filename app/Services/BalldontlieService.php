<?php

namespace App\Services;

use App\Models\Team;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BalldontlieService
{
    private const BASE_URL = 'https://api.balldontlie.io/v1';

    private const CACHE_TTL = 3600;

    private const MAX_ATTEMPTS = 5;

    private const PLAYERS_PER_PAGE = 100;

    /**
     * The free plan throttles aggressively, so consecutive calls are spaced
     * out instead of relying only on the 429 back-off.
     */
    private const MIN_SECONDS_BETWEEN_CALLS = 13;

    private static ?float $lastCallAt = null;

    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = (string) config('services.balldontlie.key');
    }

    private function throttle(): void
    {
        if (self::$lastCallAt === null) {
            self::$lastCallAt = microtime(true);

            return;
        }

        $wait = self::MIN_SECONDS_BETWEEN_CALLS - (microtime(true) - self::$lastCallAt);

        if ($wait > 0) {
            sleep((int) ceil($wait));
        }

        self::$lastCallAt = microtime(true);
    }

    private function http(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Accept' => 'application/json',
        ])
            ->timeout(30)
            ->connectTimeout(10)
            ->retry(self::MAX_ATTEMPTS, 5000, function (\Throwable $e, $request, ?string $response = null) {
                if ($response !== null) {
                    return (int) $response === 429;
                }

                return $e instanceof ConnectionException;
            });
    }

    /**
     * Failures are never cached, otherwise a rate limited window would leave
     * an empty result cached for the whole TTL.
     */
    private function request(string $endpoint, array $params = []): ?array
    {
        $cacheKey = 'balldontlie:' . md5($endpoint . json_encode($params));

        $cached = Cache::get($cacheKey);

        if (is_array($cached)) {
            return $cached;
        }

        $this->throttle();

        $response = $this->http()->get(self::BASE_URL . $endpoint, $params);

        if (! $response->successful()) {
            report(new \RuntimeException(
                "balldontlie {$endpoint} respondeu HTTP {$response->status()}"
            ));

            return null;
        }

        $payload = $response->json();

        if (! is_array($payload)) {
            return null;
        }

        Cache::put($cacheKey, $payload, self::CACHE_TTL);

        return $payload;
    }

    public function getTeams(): array
    {
        return $this->request('/teams')['data'] ?? [];
    }

    public function getTeam(int $id): ?array
    {
        return $this->request("/teams/{$id}")['data'] ?? null;
    }

    public function getGames(array $params = []): array
    {
        return $this->request('/games', $params)['data'] ?? [];
    }

    /**
     * The v1 API ignores the page parameter and only honours the opaque
     * cursor, so raw pagination state has to be returned to the caller.
     */
    public function getGamesPageWithCursor(int $season, int $cursor = 0, int $perPage = 100): array
    {
        return $this->request('/games', [
            'seasons[]' => $season,
            'per_page' => $perPage,
            'cursor' => $cursor,
        ]) ?? [];
    }

    public function getPlayers(array $params = [], ?int $cursor = null): array
    {
        if ($cursor !== null) {
            $params['cursor'] = $cursor;
        }

        return $this->request('/players', $params + ['per_page' => self::PLAYERS_PER_PAGE])['data'] ?? [];
    }

    public function getPlayer(int $id): ?array
    {
        return $this->request("/players/{$id}")['data'] ?? null;
    }

    /**
     * The v1 API paginates with an opaque cursor, not with a page number,
     * so the cursor is walked until a short or empty page comes back.
     */
    public function getAllPlayers(array $params = [], int $maxPages = 60): array
    {
        $collected = [];
        $cursor = 0;

        for ($page = 0; $page < $maxPages; $page++) {
            $payload = $this->request('/players', $params + [
                'per_page' => self::PLAYERS_PER_PAGE,
                'cursor' => $cursor,
            ]);

            $data = $payload['data'] ?? [];

            if ($data === []) {
                break;
            }

            $collected = array_merge($collected, $data);

            $next = $payload['meta']['next_cursor'] ?? null;

            if ($next === null || (int) $next === $cursor) {
                break;
            }

            $cursor = (int) $next;
        }

        return $collected;
    }

    /**
     * /teams returns 89 rows: the 30 current franchises plus 59 defunct ones
     * whose conference is a blank string. Only rows carrying a real East/West
     * conference belong in the database, which also collapses the duplicated
     * Washington entry (the defunct "Washington Capitols" shares the WAS code).
     */
    public function syncTeams(): int
    {
        $whitelist = Team::currentAbbreviations();
        $synced = 0;

        foreach ($this->getTeams() as $teamData) {
            $abbreviation = strtoupper(trim((string) ($teamData['abbreviation'] ?? '')));
            $conference = trim((string) ($teamData['conference'] ?? ''));

            if (! in_array($conference, ['East', 'West'], true)) {
                continue;
            }

            if ($abbreviation === '' || ! in_array($abbreviation, $whitelist, true)) {
                continue;
            }

            Team::updateOrCreate(
                ['abbreviation' => $abbreviation],
                [
                    'api_id' => $teamData['id'],
                    'name' => trim((string) ($teamData['full_name'] ?? $teamData['name'] ?? $abbreviation)),
                    'city' => trim((string) ($teamData['city'] ?? '')),
                    'conference' => $conference,
                    'division' => trim((string) ($teamData['division'] ?? '')) ?: 'Unknown',
                ]
            );

            $synced++;
        }

        return $synced;
    }
}
