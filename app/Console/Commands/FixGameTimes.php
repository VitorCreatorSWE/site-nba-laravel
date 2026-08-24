<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Game;

class FixGameTimes extends Command
{
    protected $signature = 'nba:fix-game-times';
    protected $description = 'Add realistic NBA tip-off times to games';

    public function handle()
    {
        $times = ['19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00'];
        $games = Game::all();
        $count = 0;

        foreach ($games as $game) {
            $time = $times[array_rand($times)];
            $date = $game->game_date->format('Y-m-d');
            $game->update(['game_date' => "{$date} {$time}:00"]);
            $count++;
        }

        $this->info("Updated {$count} games with tip-off times");
    }
}
