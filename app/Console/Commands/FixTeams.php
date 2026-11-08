<?php

namespace App\Console\Commands;

use App\Models\Team;
use Illuminate\Console\Command;

class FixTeams extends Command
{
    protected $signature = 'nba:fix-teams';

    protected $description = 'Fix team logos, arenas, NBA API IDs, titles and founding years';

    public function handle()
    {
        $this->info('=== Fixing Teams ===');
        $updated = 0;
        $missing = [];
        $semHistorico = [];

        foreach (Team::currentTeamData() as $abbr => $data) {
            $team = Team::where('abbreviation', $abbr)->first();

            if (! $team) {
                $missing[] = $abbr;

                continue;
            }

            $attributes = [
                'nba_api_id' => $data['nba_id'],
                'arena_name' => $data['arena'],
                'city' => $data['city'],
                'arena_capacity' => $data['capacity'],
            ];

            // Títulos e fundação não existem em nenhuma das APIs, então vêm do
            // config. Sem entrada no config, os campos são preservados como
            // estão para não sobrescrever um valor corrigido à mão.
            $history = config('nba.history.' . $abbr);

            if ($history === null) {
                $semHistorico[] = $abbr;
            } else {
                $attributes['championships_count'] = $history['championships'];
                $attributes['founded_year'] = $history['founded_year'];
            }

            $team->update($attributes);

            $this->info("  {$team->name} -> NBA ID {$data['nba_id']}, Arena: {$data['arena']}");
            $updated++;
        }

        if ($missing !== []) {
            $this->warn('Sem registro no banco: ' . implode(', ', $missing));
        }

        if ($semHistorico !== []) {
            $this->warn('Sem títulos/fundação no config: ' . implode(', ', $semHistorico));
        }

        $this->info("Updated {$updated} teams");

        $this->info('');
        $this->info('=== Status ===');
        $this->info('Teams with arena: ' . Team::whereNotNull('arena_name')->count());
        $this->info('Teams with NBA ID: ' . Team::whereNotNull('nba_api_id')->count());
        $this->info('Teams com fundação: ' . Team::whereNotNull('founded_year')->count());
        $this->info('Times com pelo menos 1 título: ' . Team::where('championships_count', '>', 0)->count());
        $this->info('Times fora da whitelist: ' . Team::whereNotIn('abbreviation', Team::currentAbbreviations())->count());

        return Command::SUCCESS;
    }
}
