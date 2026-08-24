<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestCdn extends Command
{
    protected $signature = 'test:cdn';
    protected $description = 'Test CDN roster URL';

    public function handle()
    {
        $url = "https://cdn.nba.com/logos/nba/1610612747/global/L/roster.json";
        $res = Http::withoutVerifying()->timeout(10)->get($url);

        $this->info("Status: " . $res->status());

        if ($res->successful()) {
            $data = $res->json();
            $this->info("Keys: " . implode(', ', array_keys($data)));

            if (isset($data['Standard'])) {
                $this->info("Standard keys: " . implode(', ', array_keys($data['Standard'])));
                $players = $data['Standard']['players'] ?? [];
                $this->info("Players: " . count($players));
                if (!empty($players)) {
                    $this->info("First player: " . json_encode($players[0]));
                }
            }
        } else {
            $this->info("Body: " . substr($res->body(), 0, 500));
        }
    }
}
