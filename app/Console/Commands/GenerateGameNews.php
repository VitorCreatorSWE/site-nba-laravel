<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsGenerator;

class GenerateGameNews extends Command
{
    protected $signature = 'nba:generate-news';
    protected $description = 'Auto-generate news posts from finished games';

    public function handle()
    {
        $generator = new NewsGenerator();
        $count = $generator->generateForAllFinishedGames();

        $this->info("Generated {$count} news posts from finished games");
    }
}
