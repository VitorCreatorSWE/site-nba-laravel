<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Player;

class CleanPlayers extends Command
{
    protected $signature = 'nba:clean-players';
    protected $description = 'Remove players without valid NBA headshot photos';

    public function handle()
    {
        $before = Player::count();
        $withPhoto = Player::whereNotNull('nba_api_id')->count();

        Player::whereNull('nba_api_id')->delete();

        $this->info("Removed " . ($before - $withPhoto) . " players without photos");
        $this->info("Remaining: " . Player::count() . " players (all with NBA headshots)");
    }
}
