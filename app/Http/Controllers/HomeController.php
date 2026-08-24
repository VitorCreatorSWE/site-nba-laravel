<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Game;
use App\Models\Team;
use App\Models\Player;
use App\Services\BalldontlieService;
use App\Services\NewsGenerator;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index(BalldontlieService $balldontlie)
    {
        if (Team::count() === 0 || Game::count() === 0 || Player::count() === 0) {
            $this->syncAll($balldontlie);
        }

        $this->generateNewsIfNeeded();

        $featuredPosts = Post::where('status', 'published')
            ->with('user')
            ->latest('published_at')
            ->limit(3)
            ->get();

        $latestPosts = Post::where('status', 'published')
            ->with('user')
            ->latest('published_at')
            ->limit(6)
            ->get();

        $upcomingGames = Game::with(['homeTeam', 'awayTeam'])
            ->where('game_date', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('game_date')
            ->limit(5)
            ->get();

        $liveGames = Game::with(['homeTeam', 'awayTeam'])
            ->where('status', 'live')
            ->get();

        $standings = Team::orderByDesc('championships_count')
            ->limit(10)
            ->get();

        return view('home', compact(
            'featuredPosts',
            'latestPosts',
            'upcomingGames',
            'liveGames',
            'standings'
        ));
    }

    private function generateNewsIfNeeded(): void
    {
        $finishedWithoutNews = Game::where('status', 'finished')
            ->whereDoesntHave('post')
            ->count();

        if ($finishedWithoutNews > 0) {
            $generator = new NewsGenerator();
            $generator->generateForAllFinishedGames();
        }
    }

    private function syncAll(BalldontlieService $balldontlie)
    {
        $this->syncTeams($balldontlie);
        $this->syncPlayers($balldontlie);
        $this->syncGames();
    }

    private function syncTeams(BalldontlieService $balldontlie)
    {
        $teams = $balldontlie->getTeams();

        $nbaData = [
            'ATL' => ['nba_id' => 1610612737, 'arena' => 'State Farm Arena', 'capacity' => 18118, 'founded' => 1946, 'champs' => 1],
            'BOS' => ['nba_id' => 1610612738, 'arena' => 'TD Garden', 'capacity' => 19156, 'founded' => 1946, 'champs' => 18],
            'BKN' => ['nba_id' => 1610612751, 'arena' => 'Barclays Center', 'capacity' => 17732, 'founded' => 1967, 'champs' => 2],
            'CHA' => ['nba_id' => 1610612766, 'arena' => 'Spectrum Center', 'capacity' => 19077, 'founded' => 2004, 'champs' => 0],
            'CHI' => ['nba_id' => 1610612741, 'arena' => 'United Center', 'capacity' => 20917, 'founded' => 1966, 'champs' => 6],
            'CLE' => ['nba_id' => 1610612739, 'arena' => 'Rocket Mortgage FieldHouse', 'capacity' => 19432, 'founded' => 1970, 'champs' => 1],
            'DAL' => ['nba_id' => 1610612742, 'arena' => 'American Airlines Center', 'capacity' => 19200, 'founded' => 1980, 'champs' => 1],
            'DEN' => ['nba_id' => 1610612743, 'arena' => 'Ball Arena', 'capacity' => 19520, 'founded' => 1967, 'champs' => 1],
            'DET' => ['nba_id' => 1610612765, 'arena' => 'Little Caesars Arena', 'capacity' => 20332, 'founded' => 1941, 'champs' => 3],
            'GSW' => ['nba_id' => 1610612744, 'arena' => 'Chase Center', 'capacity' => 18064, 'founded' => 1946, 'champs' => 7],
            'HOU' => ['nba_id' => 1610612745, 'arena' => 'Toyota Center', 'capacity' => 18055, 'founded' => 1967, 'champs' => 2],
            'IND' => ['nba_id' => 1610612754, 'arena' => 'Gainbridge Fieldhouse', 'capacity' => 17923, 'founded' => 1967, 'champs' => 0],
            'LAC' => ['nba_id' => 1610612746, 'arena' => 'Intuit Dome', 'capacity' => 18118, 'founded' => 1970, 'champs' => 0],
            'LAL' => ['nba_id' => 1610612747, 'arena' => 'Crypto.com Arena', 'capacity' => 18997, 'founded' => 1947, 'champs' => 17],
            'MEM' => ['nba_id' => 1610612763, 'arena' => 'FedExForum', 'capacity' => 17794, 'founded' => 1995, 'champs' => 0],
            'MIA' => ['nba_id' => 1610612748, 'arena' => 'Kaseya Center', 'capacity' => 19600, 'founded' => 1988, 'champs' => 3],
            'MIL' => ['nba_id' => 1610612749, 'arena' => 'Fiserv Forum', 'capacity' => 17341, 'founded' => 1968, 'champs' => 2],
            'MIN' => ['nba_id' => 1610612750, 'arena' => 'Target Center', 'capacity' => 18798, 'founded' => 1989, 'champs' => 0],
            'NOP' => ['nba_id' => 1610612740, 'arena' => 'Smoothie King Center', 'capacity' => 16867, 'founded' => 2002, 'champs' => 0],
            'NYK' => ['nba_id' => 1610612752, 'arena' => 'Madison Square Garden', 'capacity' => 19812, 'founded' => 1946, 'champs' => 2],
            'OKC' => ['nba_id' => 1610612760, 'arena' => 'Paycom Center', 'capacity' => 18203, 'founded' => 1967, 'champs' => 1],
            'ORL' => ['nba_id' => 1610612753, 'arena' => 'Kia Center', 'capacity' => 18846, 'founded' => 1989, 'champs' => 0],
            'PHI' => ['nba_id' => 1610612755, 'arena' => 'Wells Fargo Center', 'capacity' => 20478, 'founded' => 1946, 'champs' => 3],
            'PHX' => ['nba_id' => 1610612756, 'arena' => 'Footprint Center', 'capacity' => 18055, 'founded' => 1968, 'champs' => 0],
            'POR' => ['nba_id' => 1610612757, 'arena' => 'Moda Center', 'capacity' => 19393, 'founded' => 1970, 'champs' => 1],
            'SAC' => ['nba_id' => 1610612758, 'arena' => 'Golden 1 Center', 'capacity' => 17608, 'founded' => 1923, 'champs' => 1],
            'SAS' => ['nba_id' => 1610612759, 'arena' => 'Frost Bank Center', 'capacity' => 18418, 'founded' => 1967, 'champs' => 5],
            'TOR' => ['nba_id' => 1610612761, 'arena' => 'Scotiabank Arena', 'capacity' => 19800, 'founded' => 1995, 'champs' => 1],
            'UTA' => ['nba_id' => 1610612762, 'arena' => 'Delta Center', 'capacity' => 18306, 'founded' => 1974, 'champs' => 0],
            'WAS' => ['nba_id' => 1610612764, 'arena' => 'Capital One Arena', 'capacity' => 20356, 'founded' => 1961, 'champs' => 1],
        ];

        foreach ($teams as $t) {
            $abbr = $t['abbreviation'] ?? strtoupper(substr($t['name'], 0, 3));
            $extra = $nbaData[$abbr] ?? null;

            if (!$extra) continue;

            Team::updateOrCreate(
                ['api_id' => $t['id']],
                [
                    'name' => $t['name'],
                    'abbreviation' => $abbr,
                    'city' => $t['city'] ?? '',
                    'conference' => $t['conference'] ?? 'Unknown',
                    'division' => $t['division'] ?? '',
                    'nba_api_id' => $extra['nba_id'],
                    'arena_name' => $extra['arena'],
                    'arena_capacity' => $extra['capacity'],
                    'founded_year' => $extra['founded'],
                    'championships_count' => $extra['champs'],
                ]
            );
        }
    }

    private function syncPlayers(BalldontlieService $balldontlie)
    {
        $nbaIdMap = $this->getPlayerNbaIds();

        $players = $balldontlie->getPlayers(['per_page' => 100, 'page' => 1]);

        foreach ($players as $p) {
            $team = Team::where('api_id', $p['team']['id'] ?? 0)->first();
            $fullName = trim($p['first_name'] . ' ' . $p['last_name']);
            $nbaId = $nbaIdMap[$fullName] ?? null;

            if (!$nbaId) continue;

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

            Player::updateOrCreate(
                ['api_id' => $p['id']],
                [
                    'nba_api_id' => $nbaId,
                    'full_name' => $fullName,
                    'position' => $p['position'] ?? 'Unknown',
                    'team_id' => $team?->id,
                    'jersey_number' => $p['jersey_number'] ?? null,
                    'height_cm' => $heightCm,
                    'weight_kg' => $weightKg,
                    'nationality' => $p['country'] ?? null,
                ]
            );
        }
    }

    private function syncGames()
    {
        $apiKey = config('services.balldontlie.key');
        $times = ['19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00'];

        $response = Http::withoutVerifying()
            ->withHeaders(['Authorization' => $apiKey])
            ->timeout(10)
            ->get('https://api.balldontlie.io/v1/games', [
                'seasons[]' => 2025,
                'per_page' => 100,
                'page' => 1,
            ]);

        if (!$response->successful()) return;

        $games = $response->json('data', []);

        foreach ($games as $g) {
            $home = Team::where('api_id', $g['home_team']['id'] ?? 0)->first();
            $away = Team::where('api_id', $g['visitor_team']['id'] ?? 0)->first();
            if (!$home || !$away) continue;

            $time = $times[array_rand($times)];
            $date = substr($g['date'], 0, 10);

            Game::updateOrCreate(
                ['api_id' => $g['id']],
                [
                    'home_team_id' => $home->id,
                    'away_team_id' => $away->id,
                    'game_date' => "{$date} {$time}:00",
                    'status' => $g['status'] === 'Final' ? 'finished' : 'scheduled',
                    'home_score' => $g['home_team_score'] ?? 0,
                    'away_score' => $g['visitor_team_score'] ?? 0,
                    'season' => '2025',
                ]
            );
        }
    }

    private function getPlayerNbaIds(): array
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
            'Carmelo Anthony' => 2540,
            'Austin Reaves' => 1630559,
            'Rui Hachimura' => 1628960,
            'Kentavious Caldwell-Pope' => 203484,
            'Malik Monk' => 1628370,
            'P.J. Washington' => 1629606,
            'Terry Rozier' => 201567,
            'Miles Bridges' => 1628964,
            'Wendell Carter Jr.' => 1628975,
            'Markelle Fultz' => 1628365,
            'Jalen Suggs' => 1630591,
            'Cole Anthony' => 1630175,
            'Bobby Portis' => 1630157,
            'Max Strus' => 1629622,
            'Caris LeVert' => 203924,
            'Domantas Sabonis' => 204001,
            'Harrison Barnes' => 203077,
            'Steven Adams' => 203500,
            "Jae'Sean Tate" => 1630227,
            'Tari Eason' => 1631105,
            'Jabari Smith Jr.' => 1631101,
            'Fred VanVleet' => 203999,
            'Dillon Brooks' => 1627758,
            'Jalen Smith' => 1630190,
            'Obi Toppin' => 1630167,
            'Bennedict Mathurin' => 1631003,
            'Daniel Gafford' => 1629665,
            'Dwight Powell' => 201572,
            'Dereck Lively II' => 1641726,
            'De\'Andre Hunter' => 1629631,
            'Jalen Johnson' => 1630552,
            'Onyeka Okongwu' => 1630168,
            'Trey Lyles' => 1627786,
            'Kevin Huerter' => 1628975,
            'Caleb Martin' => 1628999,
        ];
    }
}
