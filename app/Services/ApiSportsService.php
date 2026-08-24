<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ApiSportsService
{
    private string $baseUrl = 'https://v2.nba.api-sports.io';
    private string $apiKey;
    private int $leagueId = 12;

    public function __construct()
    {
        $this->apiKey = config('services.apisports.key');
    }

    private function request(string $endpoint, array $params = []): ?array
    {
        $cacheKey = 'apisports_' . md5($endpoint . json_encode($params));
        $cacheTtl = str_contains($endpoint, 'live') ? 60 : 3600;

        return Cache::remember($cacheKey, $cacheTtl, function () use ($endpoint, $params) {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'x-apisports-key' => $this->apiKey,
                ])
                ->get("{$this->baseUrl}{$endpoint}", array_merge([
                    'league' => $this->leagueId,
                ], $params));

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        });
    }

    public function getGames(string $date = null): array
    {
        $params = [];
        if ($date) {
            $params['date'] = $date;
        }
        $data = $this->request('/games', $params);
        return $data['response'] ?? [];
    }

    public function getLiveGames(): array
    {
        $data = $this->request('/games', ['live' => 'all']);
        return $data['response'] ?? [];
    }

    public function getTeamStandings(int $season = null): array
    {
        $season = $season ?? (int) date('Y');
        $data = $this->request('/standings', ['season' => $season]);
        return $data['response'] ?? [];
    }

    public function getTeamStatistics(int $teamId, int $season = null): ?array
    {
        $season = $season ?? (int) date('Y');
        $data = $this->request('/teams/statistics', [
            'team' => $teamId,
            'season' => $season,
        ]);
        return $data['response'] ?? null;
    }

    public function getTeams(int $season = null): array
    {
        $season = $season ?? (int) date('Y');
        $data = $this->request('/teams', ['season' => $season]);
        return $data['response'] ?? [];
    }

    public function getPlayers(int $teamId, int $season = null): array
    {
        $season = $season ?? (int) date('Y');
        $data = $this->request('/players', [
            'team' => $teamId,
            'season' => $season,
        ]);
        return $data['response'] ?? [];
    }

    public function syncGames(): void
    {
        $games = $this->getGames();

        foreach ($games as $gameData) {
            $homeTeam = \App\Models\Team::where('api_id', $gameData['teams']['home']['id'])->first();
            $awayTeam = \App\Models\Team::where('api_id', $gameData['teams']['away']['id'])->first();

            if ($homeTeam && $awayTeam) {
                \App\Models\Game::updateOrCreate(
                    ['api_id' => $gameData['id']],
                    [
                        'home_team_id' => $homeTeam->id,
                        'away_team_id' => $awayTeam->id,
                        'game_date' => $gameData['date'],
                        'arena' => $gameData['venue']['name'] ?? null,
                        'status' => $this->mapStatus($gameData['status']['short']),
                        'home_score' => $gameData['scores']['home']['total'] ?? 0,
                        'away_score' => $gameData['scores']['away']['total'] ?? 0,
                    ]
                );
            }
        }
    }

    private function mapStatus(string $status): string
    {
        return match($status) {
            'NS' => 'scheduled',
            '1Q', '2Q', '3Q', '4Q', 'HT', 'OT' => 'live',
            'FT' => 'finished',
            default => 'scheduled',
        };
    }
}
