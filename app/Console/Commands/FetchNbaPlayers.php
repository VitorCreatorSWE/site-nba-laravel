<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Player;
use App\Models\Team;

class FetchNbaPlayers extends Command
{
    protected $signature = 'nba:fetch-players';
    protected $description = 'Fetch current NBA players from CDN roster JSON';

    public function handle()
    {
        $this->info('Fetching NBA rosters from CDN...');

        $nbaTeamIds = Team::whereNotNull('nba_api_id')
            ->pluck('nba_api_id', 'id')
            ->toArray();

        $count = 0;

        foreach ($nbaTeamIds as $teamId => $nbaId) {
            $url = "https://cdn.nba.com/logos/nba/{$nbaId}/global/L/roster.json";
            try {
                $res = Http::withoutVerifying()->timeout(10)->get($url);
                if ($res->successful()) {
                    $roster = $res->json('Standard.players', []);
                    $this->info("  Team {$nbaId}: " . count($roster) . " players");
                    foreach ($roster as $p) {
                        $name = trim(($p['FIRST_NAME'] ?? '') . ' ' . ($p['LAST_NAME'] ?? ''));
                        $player = Player::where('full_name', $name)->first();
                        if ($player) {
                            $player->update([
                                'nba_api_id' => $p['PERSON_ID'],
                                'team_id' => $teamId,
                                'jersey_number' => $p['JERSEY'] ?? $player->jersey_number,
                                'position' => $p['POSITION'] ?? $player->position,
                            ]);
                            $count++;
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->warn("  Skipped team {$nbaId}: " . $e->getMessage());
            }
        }

        $this->info("Matched {$count} players to NBA CDN IDs");
        $this->info("Players with NBA ID: " . Player::whereNotNull('nba_api_id')->count());
        $this->info("Total players: " . Player::count());
    }
}
