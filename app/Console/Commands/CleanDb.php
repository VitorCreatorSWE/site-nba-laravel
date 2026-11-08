<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanDb extends Command
{
    protected $signature = 'nba:clean-db {--dry-run : Apenas mostra o que seria removido}';

    protected $description = 'Remove times fora da whitelist de 30 franchises e resolve o WAS duplicado';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $whitelist = Team::currentAbbreviations();

        $strays = Team::whereNotIn('abbreviation', $whitelist)->get();

        $this->info("Times na whitelist: " . count($whitelist));
        $this->info("Times fora da whitelist: {$strays->count()}");

        foreach ($strays as $stray) {
            $this->line("  - {$stray->abbreviation} ({$stray->name}) api_id={$stray->api_id}");
        }

        $duplicates = Team::whereIn('abbreviation', $whitelist)
            ->groupBy('abbreviation')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('abbreviation');

        if ($duplicates->isNotEmpty()) {
            $this->warn('Siglas duplicadas: ' . $duplicates->implode(', '));
        }

        if ($dryRun) {
            $this->info('Dry run: nada foi alterado.');

            return self::SUCCESS;
        }

        if ($strays->isNotEmpty()) {
            $this->removeStrays($strays);
        }

        foreach ($duplicates as $abbreviation) {
            $this->mergeDuplicates($abbreviation);
        }

        $this->newLine();
        $this->info('=== Pos limpeza ===');
        $this->info('Times: ' . Team::count());
        $this->info('Jogos: ' . Game::count());
        $this->info('Jogadores: ' . Player::count());

        return self::SUCCESS;
    }

    private function removeStrays($strays): void
    {
        $ids = $strays->pluck('id');

        $affected = Game::whereIn('home_team_id', $ids)
            ->orWhereIn('away_team_id', $ids)
            ->count();

        if ($affected > 0) {
            $this->info("Removendo {$affected} jogos que apontam para times extintos...");
            Game::whereIn('home_team_id', $ids)->orWhereIn('away_team_id', $ids)->delete();
        }

        $playerCount = Player::whereIn('team_id', $ids)->count();

        if ($playerCount > 0) {
            $this->info("Desvinculando {$playerCount} jogadores...");
            Player::whereIn('team_id', $ids)->update(['team_id' => null]);
        }

        Team::whereIn('id', $ids)->delete();
    }

    private function mergeDuplicates(string $abbreviation): void
    {
        $teams = Team::where('abbreviation', $abbreviation)->orderBy('id')->get();
        $keep = $teams->shift();

        foreach ($teams as $duplicate) {
            Game::where('home_team_id', $duplicate->id)->update(['home_team_id' => $keep->id]);
            Game::where('away_team_id', $duplicate->id)->update(['away_team_id' => $keep->id]);
            Player::where('team_id', $duplicate->id)->update(['team_id' => $keep->id]);

            DB::table('game_stats')->where('team_id', $duplicate->id)->update(['team_id' => $keep->id]);

            $duplicate->delete();
        }

        $this->info("  {$abbreviation}: duplicatas mescladas em #{$keep->id} ({$keep->name})");
    }
}
