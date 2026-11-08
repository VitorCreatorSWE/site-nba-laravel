<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Team;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ApiSportsService
{
    private const BASE_URL = 'https://v2.nba.api-sports.io';

    private const CACHE_TTL = 3600;

    private const LIVE_CACHE_TTL = 60;

    private const MAX_ATTEMPTS = 4;

    /**
     * The free plan is capped at 10 requests per minute, so every call is
     * spaced out and backed off aggressively when the limiter kicks in.
     */
    private const MIN_SECONDS_BETWEEN_CALLS = 7;

    private static ?float $lastCallAt = null;

    private string $apiKey;

    private int $season;

    public function __construct()
    {
        $this->apiKey = (string) config('services.apisports.key');
        $this->season = (int) config('services.apisports.season', 2024);
    }

    public function season(): int
    {
        return $this->season;
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
            'x-apisports-key' => $this->apiKey,
            'Accept' => 'application/json',
        ])
            ->timeout(25)
            ->connectTimeout(10)
            ->retry(self::MAX_ATTEMPTS, 10000, function (\Throwable $e, $request, ?string $response = null) {
                if ($response !== null) {
                    return (int) $response === 429;
                }

                return $e instanceof ConnectionException;
            });
    }

    /**
     * The api-sports rate limiter answers with HTTP 200 and an
     * errors.rateLimit payload, so the status based retry never fires and the
     * throttle has to be enforced on the decoded body instead.
     */
    public const RATE_LIMIT_ERROR = 'rateLimit';

    private const MAX_RATE_LIMIT_RETRIES = 5;

    /**
     * Failures are never cached, otherwise a rate limited window would leave
     * an empty result cached for the whole TTL.
     */
    private function request(string $endpoint, array $params = []): ?array
    {
        $cacheKey = 'apisports:' . md5($endpoint . json_encode($params));
        $ttl = str_contains($endpoint, 'live') ? self::LIVE_CACHE_TTL : self::CACHE_TTL;

        $cached = Cache::get($cacheKey);

        if (is_array($cached)) {
            return $cached;
        }

        $payload = $this->fetch($endpoint, $params);

        if ($payload !== null) {
            Cache::put($cacheKey, $payload, $ttl);
        }

        return $payload;
    }

    private function fetch(string $endpoint, array $params): ?array
    {
        for ($attempt = 0; $attempt <= self::MAX_RATE_LIMIT_RETRIES; $attempt++) {
            $this->throttle();

            $response = $this->http()->get(self::BASE_URL . $endpoint, $params);

            if (! $response->successful()) {
                report(new \RuntimeException(
                    "api-sports {$endpoint} respondeu HTTP {$response->status()}"
                ));

                return null;
            }

            $payload = $response->json();

            if (is_array($payload['errors'] ?? null) && isset($payload['errors'][self::RATE_LIMIT_ERROR])) {
                $wait = 15 * ($attempt + 1);
                report(new \RuntimeException("api-sports {$endpoint} rate limited, aguardando {$wait}s"));
                sleep($wait);

                continue;
            }

            if (! empty($payload['errors']) && empty($payload['response'])) {
                report(new \RuntimeException(
                    'api-sports ' . $endpoint . ': ' . json_encode($payload['errors'])
                ));

                return null;
            }

            return $payload;
        }

        report(new \RuntimeException("api-sports {$endpoint} esgotou as tentativas por rate limit"));

        return null;
    }

    public function getGames(?string $date = null): array
    {
        $params = [];

        if ($date) {
            $params['date'] = $date;
        }

        return $this->request('/games', $params)['response'] ?? [];
    }

    public function getGamesBySeason(int $season, int $page = 1): array
    {
        return $this->request('/games', [
            'season' => $season,
            'page' => $page,
        ])['response'] ?? [];
    }

    public function getLiveGames(): array
    {
        return $this->request('/games', ['live' => 'all'])['response'] ?? [];
    }

    public function getPlayers(int $teamId, ?int $season = null): array
    {
        return $this->request('/players', [
            'team' => $teamId,
            'season' => $season ?? $this->season,
        ])['response'] ?? [];
    }

    public function getPlayerStatistics(int $teamId, ?int $season = null): array
    {
        return $this->request('/players/statistics', [
            'team' => $teamId,
            'season' => $season ?? $this->season,
        ])['response'] ?? [];
    }

    public function getTeamStandings(?int $season = null): array
    {
        return $this->request('/standings', [
            'league' => 12,
            'season' => $season ?? $this->season,
        ])['response'] ?? [];
    }

    public function getTeamStatistics(int $teamId, ?int $season = null): ?array
    {
        return $this->request('/teams/statistics', [
            'team' => $teamId,
            'season' => $season ?? $this->season,
        ])['response'] ?? null;
    }

    public function getTeams(?int $season = null): array
    {
        return $this->request('/teams', ['season' => $season ?? $this->season])['response'] ?? [];
    }

    public function syncGames(?int $season = null, int $maxPages = 20): int
    {
        $season ??= $this->season;
        $synced = 0;
        $page = 1;

        while ($page <= $maxPages) {
            $games = $this->getGamesBySeason($season, $page);

            if ($games === []) {
                break;
            }

            foreach ($games as $gameData) {
                if ($this->upsertGame($gameData, $season)) {
                    $synced++;
                }
            }

            $page++;
        }

        return $synced;
    }

    private function upsertGame(array $gameData, int $season): bool
    {
        $home = Team::where('api_id', $gameData['teams']['home']['id'] ?? null)->first();
        $away = Team::where('api_id', $gameData['teams']['visitors']['id'] ?? null)->first();

        if (! $home || ! $away) {
            return false;
        }

        Game::updateOrCreate(
            ['api_id' => $gameData['id']],
            [
                'home_team_id' => $home->id,
                'away_team_id' => $away->id,
                'game_date' => $gameData['date']['start'] ?? $gameData['date'] ?? null,
                'arena' => $gameData['arena']['name'] ?? null,
                'status' => $this->mapStatus((int) ($gameData['status']['short'] ?? 1)),
                'home_score' => (int) ($gameData['scores']['home']['points'] ?? 0),
                'away_score' => (int) ($gameData['scores']['visitors']['points'] ?? 0),
                'season' => (string) ($gameData['season'] ?? $season),
            ]
        );

        return true;
    }

    private function mapStatus(int $status): string
    {
        return match ($status) {
            1 => 'scheduled',
            2, 5 => 'live',
            default => 'finished',
        };
    }
}
