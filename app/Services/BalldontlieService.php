<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class BalldontlieService
{
    private string $baseUrl = 'https://api.balldontlie.io/v1';
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.balldontlie.key');
    }

    private function request(string $endpoint, array $params = []): ?array
    {
        $cacheKey = 'balldontlie_' . md5($endpoint . json_encode($params));

        return Cache::remember($cacheKey, 3600, function () use ($endpoint, $params) {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => $this->apiKey,
                ])
                ->get("{$this->baseUrl}{$endpoint}", $params);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        });
    }

    public function getTeams(): array
    {
        $data = $this->request('/teams');
        return $data['data'] ?? [];
    }

    public function getTeam(int $id): ?array
    {
        $data = $this->request("/teams/{$id}");
        return $data['data'] ?? null;
    }

    public function getPlayers(array $params = []): array
    {
        $data = $this->request('/players', $params);
        return $data['data'] ?? [];
    }

    public function searchPlayers(string $search): array
    {
        return $this->getPlayers(['search' => $search]);
    }

    public function getSeasonAverages(array $params): array
    {
        $data = $this->request('/season_averages', $params);
        return $data['data'] ?? [];
    }

    public function getGames(array $params = []): array
    {
        $data = $this->request('/games', $params);
        return $data['data'] ?? [];
    }

    public function getPlayerStats(array $params = []): array
    {
        $data = $this->request('/stats', $params);
        return $data['data'] ?? [];
    }

    public function syncTeams(): void
    {
        $teams = $this->getTeams();

        foreach ($teams as $teamData) {
            \App\Models\Team::updateOrCreate(
                ['api_id' => $teamData['id']],
                [
                    'name' => $teamData['name'],
                    'abbreviation' => $teamData['abbreviation'] ?? strtoupper(substr($teamData['name'], 0, 3)),
                    'city' => $teamData['city'] ?? '',
                    'conference' => $teamData['conference'] ?? 'Unknown',
                    'division' => $teamData['division'] ?? '',
                ]
            );
        }
    }
}
