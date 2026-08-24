<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Team;
use App\Models\Game;
use App\Models\Player;

class CleanDb extends Command
{
    protected $signature = 'nba:clean-db';
    protected $description = 'Remove non-NBA teams and fix broken references';

    public function handle()
    {
        $nonNba = Team::whereNull('nba_api_id')->pluck('id');
        $this->info("Non-NBA teams: {$nonNba->count()}");

        $gamesCount = Game::whereIn('home_team_id', $nonNba)->orWhereIn('away_team_id', $nonNba)->count();
        $playersCount = Player::whereIn('team_id', $nonNba)->count();

        $this->info("Games referencing them: {$gamesCount}");
        $this->info("Players referencing them: {$playersCount}");

        Game::whereIn('home_team_id', $nonNba)->orWhereIn('away_team_id', $nonNba)->delete();
        Player::whereIn('team_id', $nonNba)->update(['team_id' => null]);
        Team::whereIn('id', $nonNba)->delete();

        $this->info("After cleanup:");
        $this->info("Teams: " . Team::count());
        $this->info("Games: " . Game::count());
        $this->info("Players: " . Player::count());
    }
}
