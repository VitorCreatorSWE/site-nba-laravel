<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Team;
use App\Models\Player;

class FixTeams extends Command
{
    protected $signature = 'nba:fix-teams';
    protected $description = 'Fix team logos, arenas, and NBA API IDs';

    protected array $nbaTeams = [
        'ATL' => ['nba_id' => 1610612737, 'arena' => 'State Farm Arena', 'city' => 'Atlanta', 'capacity' => 18118],
        'BOS' => ['nba_id' => 1610612738, 'arena' => 'TD Garden', 'city' => 'Boston', 'capacity' => 19156],
        'BKN' => ['nba_id' => 1610612751, 'arena' => 'Barclays Center', 'city' => 'Brooklyn', 'capacity' => 17732],
        'CHA' => ['nba_id' => 1610612766, 'arena' => 'Spectrum Center', 'city' => 'Charlotte', 'capacity' => 19077],
        'CHI' => ['nba_id' => 1610612741, 'arena' => 'United Center', 'city' => 'Chicago', 'capacity' => 20917],
        'CLE' => ['nba_id' => 1610612739, 'arena' => 'Rocket Mortgage FieldHouse', 'city' => 'Cleveland', 'capacity' => 19432],
        'DAL' => ['nba_id' => 1610612742, 'arena' => 'American Airlines Center', 'city' => 'Dallas', 'capacity' => 19200],
        'DEN' => ['nba_id' => 1610612743, 'arena' => 'Ball Arena', 'city' => 'Denver', 'capacity' => 19520],
        'DET' => ['nba_id' => 1610612765, 'arena' => 'Little Caesars Arena', 'city' => 'Detroit', 'capacity' => 20332],
        'GSW' => ['nba_id' => 1610612744, 'arena' => 'Chase Center', 'city' => 'San Francisco', 'capacity' => 18064],
        'HOU' => ['nba_id' => 1610612745, 'arena' => 'Toyota Center', 'city' => 'Houston', 'capacity' => 18055],
        'IND' => ['nba_id' => 1610612754, 'arena' => 'Gainbridge Fieldhouse', 'city' => 'Indianapolis', 'capacity' => 17923],
        'LAC' => ['nba_id' => 1610612746, 'arena' => 'Intuit Dome', 'city' => 'Inglewood', 'capacity' => 18118],
        'LAL' => ['nba_id' => 1610612747, 'arena' => 'Crypto.com Arena', 'city' => 'Los Angeles', 'capacity' => 18997],
        'MEM' => ['nba_id' => 1610612763, 'arena' => 'FedExForum', 'city' => 'Memphis', 'capacity' => 17794],
        'MIA' => ['nba_id' => 1610612748, 'arena' => 'Kaseya Center', 'city' => 'Miami', 'capacity' => 19600],
        'MIL' => ['nba_id' => 1610612749, 'arena' => 'Fiserv Forum', 'city' => 'Milwaukee', 'capacity' => 17341],
        'MIN' => ['nba_id' => 1610612750, 'arena' => 'Target Center', 'city' => 'Minneapolis', 'capacity' => 18798],
        'NOP' => ['nba_id' => 1610612740, 'arena' => 'Smoothie King Center', 'city' => 'New Orleans', 'capacity' => 16867],
        'NYK' => ['nba_id' => 1610612752, 'arena' => 'Madison Square Garden', 'city' => 'New York', 'capacity' => 19812],
        'OKC' => ['nba_id' => 1610612760, 'arena' => 'Paycom Center', 'city' => 'Oklahoma City', 'capacity' => 18203],
        'ORL' => ['nba_id' => 1610612753, 'arena' => 'Kia Center', 'city' => 'Orlando', 'capacity' => 18846],
        'PHI' => ['nba_id' => 1610612755, 'arena' => 'Wells Fargo Center', 'city' => 'Philadelphia', 'capacity' => 20478],
        'PHX' => ['nba_id' => 1610612756, 'arena' => 'Footprint Center', 'city' => 'Phoenix', 'capacity' => 18055],
        'POR' => ['nba_id' => 1610612757, 'arena' => 'Moda Center', 'city' => 'Portland', 'capacity' => 19393],
        'SAC' => ['nba_id' => 1610612758, 'arena' => 'Golden 1 Center', 'city' => 'Sacramento', 'capacity' => 17608],
        'SAS' => ['nba_id' => 1610612759, 'arena' => 'Frost Bank Center', 'city' => 'San Antonio', 'capacity' => 18418],
        'TOR' => ['nba_id' => 1610612761, 'arena' => 'Scotiabank Arena', 'city' => 'Toronto', 'capacity' => 19800],
        'UTA' => ['nba_id' => 1610612762, 'arena' => 'Delta Center', 'city' => 'Salt Lake City', 'capacity' => 18306],
        'WAS' => ['nba_id' => 1610612764, 'arena' => 'Capital One Arena', 'city' => 'Washington', 'capacity' => 20356],
    ];

    protected array $nbaPlayerIds = [
        'LeBron James' => 2544,
        'Stephen Curry' => 201939,
        'Kevin Durant' => 5419,
        'Giannis Antetokounmpo' => 203507,
        'Nikola Jokic' => 203999,
        'Luka Doncic' => 1629029,
        'Joel Embiid' => 203954,
        'Jayson Tatum' => 1628369,
        'Anthony Davis' => 203076,
        'Damian Lillard' => 203081,
        'Jimmy Butler' => 202710,
        'Kawhi Leonard' => 6450,
        'Paul George' => 6451,
        'James Harden' => 201935,
        'Russell Westbrook' => 201566,
        'Kyrie Irving' => 6442,
        'Klay Thompson' => 202691,
        'Draymond Green' => 203110,
        'Devin Booker' => 203999,
        'Ja Morant' => 1629630,
        'Zion Williamson' => 1629627,
        'Trae Young' => 1628973,
        'Luka Doncic' => 1629029,
        'Shai Gilgeous-Alexander' => 1628983,
        'Tyrese Haliburton' => 1630169,
        'Paolo Banchero' => 1631104,
        'Chet Holmgren' => 1631100,
        'Victor Wembanyama' => 1641705,
        'Anthony Edwards' => 1630162,
        'Jalen Brunson' => 1628973,
    ];

    public function handle()
    {
        $this->info('=== Fixing Teams ===');
        $updated = 0;

        foreach ($this->nbaTeams as $abbr => $data) {
            $team = Team::where('abbreviation', $abbr)->first();
            if ($team) {
                $team->update([
                    'nba_api_id' => $data['nba_id'],
                    'arena_name' => $data['arena'],
                    'city' => $data['city'],
                    'arena_capacity' => $data['capacity'],
                ]);
                $this->info("  {$team->name} -> NBA ID {$data['nba_id']}, Arena: {$data['arena']}");
                $updated++;
            }
        }
        $this->info("Updated {$updated} teams");

        $this->info('');
        $this->info('=== Fixing Players ===');
        $playerUpdated = 0;

        foreach ($this->nbaPlayerIds as $name => $nbaId) {
            $player = Player::where('full_name', $name)->first();
            if ($player) {
                $player->update(['nba_api_id' => $nbaId]);
                $playerUpdated++;
            }
        }
        $this->info("Updated {$playerUpdated} players with NBA IDs");

        $this->info('');
        $this->info('=== Status ===');
        $this->info('Teams with arena: ' . Team::whereNotNull('arena_name')->count());
        $this->info('Teams with NBA ID: ' . Team::whereNotNull('nba_api_id')->count());
        $this->info('Players with NBA ID: ' . Player::whereNotNull('nba_api_id')->count());
    }
}
