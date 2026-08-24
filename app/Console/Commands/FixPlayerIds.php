<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Player;

class FixPlayerIds extends Command
{
    protected $signature = 'nba:fix-player-ids';
    protected $description = 'Fix NBA CDN IDs for players';

    public function handle()
    {
        $playerMap = [
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
            'Devin Booker' => 1626164,
            'Ja Morant' => 1629630,
            'Zion Williamson' => 1629627,
            'Trae Young' => 1628973,
            'Shai Gilgeous-Alexander' => 1628983,
            'Tyrese Haliburton' => 1630169,
            'Paolo Banchero' => 1631104,
            'Chet Holmgren' => 1631100,
            'Victor Wembanyama' => 1641705,
            'Anthony Edwards' => 1630162,
            'Jalen Brunson' => 1628977,
            'Bam Adebayo' => 1628389,
            'De Aaron Fox' => 1628368,
            'Donovan Mitchell' => 1628378,
            'Jaylen Brown' => 1627759,
            'Pascal Siakam' => 1627783,
            'Darius Garland' => 1629632,
            'Evan Mobley' => 1630559,
            'LaMelo Ball' => 1630163,
            'Scottie Barnes' => 1630569,
            'Cade Cunningham' => 1630556,
            'Jalen Green' => 1630224,
            'Alperen Sengun' => 1630597,
            'Desmond Bane' => 1630540,
            'Franz Wagner' => 1630570,
            'Jalen Williams' => 1631110,
            'Derrick White' => 1628401,
            'Buddy Hield' => 203552,
            'CJ McCollum' => 203990,
            'Brandon Ingram' => 1627742,
            'Zach LaVine' => 203897,
            'Dejounte Murray' => 1627741,
            'Myles Turner' => 203994,
            'Brook Lopez' => 201572,
            'Karl-Anthony Towns' => 203999,
            'Rudy Gobert' => 203497,
            'Deandre Ayton' => 1628960,
            'Clint Capela' => 203994,
            'Nikola Vucevic' => 202691,
            'Khris Middleton' => 203269,
            'DeMar DeRozan' => 201942,
            'Bradley Beal' => 203078,
            'Ben Simmons' => 203999,
            'Dennis Schroder' => 203471,
            'Al Horford' => 201143,
            'Tyler Herro' => 1629630,
            'Jordan Poole' => 1629672,
            'Cam Thomas' => 1630553,
            'Keegan Murray' => 1631559,
            'Jaden Ivey' => 1631001,
            'Davion Mitchell' => 1630557,
            'Tre Mann' => 1630552,
            'Alex Caruso' => 1629636,
            'Alec Burks' => 202697,
            'Avery Bradley' => 202340,
            'Al-Farouq Aminu' => 201569,
            'Bogdan Bogdanovic' => 203998,
            'Bismack Biyombo' => 202685,
            'Aron Baynes' => 203546,
            'Derrick Jones Jr.' => 1627884,
            'Nikola Jovic' => 1631106,
        ];

        $updated = 0;
        foreach ($playerMap as $name => $nbaId) {
            $player = Player::where('full_name', $name)->first();
            if ($player && !$player->nba_api_id) {
                $player->update(['nba_api_id' => $nbaId]);
                $updated++;
                $this->info("  {$name} -> {$nbaId}");
            }
        }

        $this->info("Updated {$updated} players");
        $this->info("Players with NBA ID: " . Player::whereNotNull('nba_api_id')->count());
        $this->info("Total players: " . Player::count());
    }
}
