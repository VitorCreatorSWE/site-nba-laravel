<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Player;
use App\Models\Team;

class RefreshPlayers extends Command
{
    protected $signature = 'nba:refresh-players';
    protected $description = 'Remove old players, resync from API with NBA CDN mapping';

    public function handle()
    {
        $this->info('Removing all players...');
        Player::truncate();

        $this->info('Resyncing from balldontlie API...');
        $balldontlie = app(\App\Services\BalldontlieService::class);
        $page = 1;
        $total = 0;

        while ($page <= 50) {
            $players = $balldontlie->getPlayers(['per_page' => 100, 'page' => $page]);
            if (empty($players)) break;

            foreach ($players as $p) {
                $team = Team::where('api_id', $p['team']['id'] ?? 0)->first();
                $fullName = trim($p['first_name'] . ' ' . $p['last_name']);
                $nbaId = $this->getNbaId($fullName);

                $heightCm = null;
                if (!empty($p['height'])) {
                    $parts = explode('-', $p['height']);
                    if (count($parts) === 2) {
                        $heightCm = round(((int)$parts[0] * 30.48) + ((int)$parts[1] * 2.54), 2);
                    }
                }
                $weightKg = null;
                if (!empty($p['weight']) && is_numeric($p['weight'])) {
                    $weightKg = round((float)$p['weight'] / 2.205, 2);
                }

                Player::create([
                    'api_id' => $p['id'],
                    'nba_api_id' => $nbaId,
                    'full_name' => $fullName,
                    'position' => $p['position'] ?? 'Unknown',
                    'team_id' => $team?->id,
                    'jersey_number' => $p['jersey_number'] ?? null,
                    'height_cm' => $heightCm,
                    'weight_kg' => $weightKg,
                    'nationality' => $p['country'] ?? null,
                ]);
                $total++;
            }

            $this->info("  Page {$page}: " . count($players) . " players");
            $page++;
        }

        $this->info("Total synced: {$total}");
        $this->info("With NBA ID: " . Player::whereNotNull('nba_api_id')->count());
        $this->info("Total: " . Player::count());
    }

    private function getNbaId(string $name): ?int
    {
        $map = $this->nbaIdMap();
        return $map[$name] ?? null;
    }

    private function nbaIdMap(): array
    {
        return [
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
            "De'Aaron Fox" => 1628368,
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
            'Dennis Schroder' => 203471,
            'Al Horford' => 201143,
            'Tyler Herro' => 1628979,
            'Jordan Poole' => 1629672,
            'Cam Thomas' => 1630553,
            'Keegan Murray' => 1631559,
            'Jaden Ivey' => 1631001,
            'Davion Mitchell' => 1630557,
            'Alex Caruso' => 1629636,
            'Bogdan Bogdanovic' => 203998,
            'Alec Burks' => 202697,
            'Avery Bradley' => 202340,
            'Al-Farouq Aminu' => 201569,
            'Bismack Biyombo' => 202685,
            'Aron Baynes' => 203546,
            'Nikola Jovic' => 1631106,
            'Carmelo Anthony' => 2540,
            'Trevor Ariza' => 2426,
            'Sterling Brown' => 1628972,
            'Austin Reaves' => 1630559,
            'Rui Hachimura' => 1628960,
            'Jarred Vanderbilt' => 1630173,
            'Taurean Prince' => 201576,
            'Kentavious Caldwell-Pope' => 203484,
            'Malik Monk' => 1628370,
            'P.J. Washington' => 1629606,
            'Gordon Hayward' => 202331,
            'Terry Rozier' => 201567,
            'Miles Bridges' => 1628964,
            'Cody Martin' => 1628999,
            'Oshae Brissett' => 1629603,
            'Sam Hauser' => 1630574,
            'Payton Pritchard' => 1630202,
            'Luke Kornet' => 1628436,
            'Robert Williams III' => 1630528,
            'Grant Williams' => 1629633,
            'Aaron Nesmith' => 1630175,
            'Daniel Theis' => 203468,
            'Malcolm Brogdon' => 203999,
            'Marcus Smart' => 203935,
            'Seth Curry' => 203552,
            'Jock Landale' => 203994,
            "Jae'Sean Tate" => 1630227,
            'Tari Eason' => 1631105,
            'Jabari Smith Jr.' => 1631101,
            'Fred VanVleet' => 203999,
            'Dillon Brooks' => 1627758,
            'Reggie Bullock' => 202339,
            'Aaron Holiday' => 1628978,
            'Boban Marjanovic' => 204001,
            'Jalen Smith' => 1630190,
            'TJ McConnell' => 203999,
            'Obi Toppin' => 1630167,
            'Bennedict Mathurin' => 1631003,
            'Andrew Nembhard' => 1631116,
            'Isaiah Jackson' => 1630543,
            'Daniel Gafford' => 1629665,
            'Dwight Powell' => 201572,
            'Josh Green' => 1629608,
            'Tim Hardaway Jr.' => 203501,
            'Dereck Lively II' => 1641726,
            'Jaden Hardy' => 1630541,
            'Richaun Holmes' => 1626171,
            'Markieff Morris' => 202691,
            'Wendell Carter Jr.' => 1628975,
            'Cole Anthony' => 1630175,
            'Jalen Suggs' => 1630591,
            'Markelle Fultz' => 1628365,
            'Jonathan Isaac' => 1628384,
            'Gary Harris' => 203914,
            'Moritz Wagner' => 1628971,
            'Kent Bazemore' => 203145,
            'Harrison Barnes' => 203077,
            'De'Aaron Fox' => 1628368,
            'Keegan Murray' => 1631559,
            'Domantas Sabonis' => 204001,
            'Kevin Huerter' => 1628975,
            'Davion Mitchell' => 1630557,
            'Mike Brown' => 0,
            'Malik Monk' => 1628370,
            'Trey Lyles' => 1627786,
            'Harrison Barnes' => 203077,
            'Darius Garland' => 1629632,
            'Evan Mobley' => 1630559,
            'Donovan Mitchell' => 1628378,
            'Jarrett Allen' => 1628386,
            'Caris LeVert' => 203924,
            'Darius Thompson' => 0,
            'Max Strus' => 1629622,
            'Georges Niang' => 203994,
            'Isaac Okoro' => 1630171,
            'Sam Merrill' => 1630233,
            'Danny Green' => 201569,
            'Ricky Rubio' => 201577,
            'Craig Porter Jr.' => 1641766,
            'Damian Jones' => 1627783,
            'Tristan Thompson' => 202684,
            'Dean Wade' => 1629725,
            'Alperen Sengun' => 1630597,
            'Jalen Green' => 1630224,
            'Amen Thompson' => 1641742,
            'Jabari Smith Jr.' => 1631101,
            'Fred VanVleet' => 203999,
            'Dillon Brooks' => 1627758,
            'Tari Eason' => 1631105,
            'Cam Whitmore' => 1641745,
            'Jeff Green' => 201572,
            'Aaron Holiday' => 1628978,
            'Reggie Bullock' => 202339,
            'Boban Marjanovic' => 204001,
            'Steven Adams' => 203500,
            'Clint Capela' => 203994,
            'Dejounte Murray' => 1627741,
            'Bogdan Bogdanovic' => 203998,
            'Jalen Johnson' => 1630552,
            'Onyeka Okongwu' => 1630168,
            'De'Andre Hunter' => 1629631,
            'Saddiq Bey' => 1630180,
            'AJ Griffin' => 1631108,
            'AJ Green' => 1630597,
            'Brook Lopez' => 201572,
            'Khris Middleton' => 203269,
            'Bobby Portis' => 1630157,
            'Pat Connaughton' => 1626171,
            'Brook Lopez' => 201572,
            'Donte DiVincenzo' => 1628978,
            'Cody Zeller' => 203469,
            'Caleb Martin' => 1628999,
            'Nathan Knight' => 1630552,
        ];
    }
}
