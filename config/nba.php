<?php

return [

    /*
    |--------------------------------------------------------------------------
    | NBA Teams
    |--------------------------------------------------------------------------
    |
    | The balldontlie /teams endpoint returns 89 franchises, including 59
    | defunct ones and a duplicated Washington entry. Only the 30 franchises
    | listed here are current, so this map is the allow-list used to keep
    | the local teams table clean and to resolve the duplicated WAS row.
    |
    */

    'teams' => [
        'ATL' => ['nba_id' => 1610612737, 'apisports_id' => 1, 'arena' => 'State Farm Arena', 'city' => 'Atlanta', 'capacity' => 18118],
        'BOS' => ['nba_id' => 1610612738, 'apisports_id' => 2, 'arena' => 'TD Garden', 'city' => 'Boston', 'capacity' => 19156],
        'BKN' => ['nba_id' => 1610612751, 'apisports_id' => 4, 'arena' => 'Barclays Center', 'city' => 'Brooklyn', 'capacity' => 17732],
        'CHA' => ['nba_id' => 1610612766, 'apisports_id' => 5, 'arena' => 'Spectrum Center', 'city' => 'Charlotte', 'capacity' => 19077],
        'CHI' => ['nba_id' => 1610612741, 'apisports_id' => 6, 'arena' => 'United Center', 'city' => 'Chicago', 'capacity' => 20917],
        'CLE' => ['nba_id' => 1610612739, 'apisports_id' => 7, 'arena' => 'Rocket Mortgage FieldHouse', 'city' => 'Cleveland', 'capacity' => 19432],
        'DAL' => ['nba_id' => 1610612742, 'apisports_id' => 8, 'arena' => 'American Airlines Center', 'city' => 'Dallas', 'capacity' => 19200],
        'DEN' => ['nba_id' => 1610612743, 'apisports_id' => 9, 'arena' => 'Ball Arena', 'city' => 'Denver', 'capacity' => 19520],
        'DET' => ['nba_id' => 1610612765, 'apisports_id' => 10, 'arena' => 'Little Caesars Arena', 'city' => 'Detroit', 'capacity' => 20332],
        'GSW' => ['nba_id' => 1610612744, 'apisports_id' => 11, 'arena' => 'Chase Center', 'city' => 'San Francisco', 'capacity' => 18064],
        'HOU' => ['nba_id' => 1610612745, 'apisports_id' => 14, 'arena' => 'Toyota Center', 'city' => 'Houston', 'capacity' => 18055],
        'IND' => ['nba_id' => 1610612754, 'apisports_id' => 15, 'arena' => 'Gainbridge Fieldhouse', 'city' => 'Indianapolis', 'capacity' => 17923],
        'LAC' => ['nba_id' => 1610612746, 'apisports_id' => 16, 'arena' => 'Intuit Dome', 'city' => 'Inglewood', 'capacity' => 18118],
        'LAL' => ['nba_id' => 1610612747, 'apisports_id' => 17, 'arena' => 'Crypto.com Arena', 'city' => 'Los Angeles', 'capacity' => 18997],
        'MEM' => ['nba_id' => 1610612763, 'apisports_id' => 19, 'arena' => 'FedExForum', 'city' => 'Memphis', 'capacity' => 17794],
        'MIA' => ['nba_id' => 1610612748, 'apisports_id' => 20, 'arena' => 'Kaseya Center', 'city' => 'Miami', 'capacity' => 19600],
        'MIL' => ['nba_id' => 1610612749, 'apisports_id' => 21, 'arena' => 'Fiserv Forum', 'city' => 'Milwaukee', 'capacity' => 17341],
        'MIN' => ['nba_id' => 1610612750, 'apisports_id' => 22, 'arena' => 'Target Center', 'city' => 'Minneapolis', 'capacity' => 18798],
        'NOP' => ['nba_id' => 1610612740, 'apisports_id' => 23, 'arena' => 'Smoothie King Center', 'city' => 'New Orleans', 'capacity' => 16867],
        'NYK' => ['nba_id' => 1610612752, 'apisports_id' => 24, 'arena' => 'Madison Square Garden', 'city' => 'New York', 'capacity' => 19812],
        'OKC' => ['nba_id' => 1610612760, 'apisports_id' => 25, 'arena' => 'Paycom Center', 'city' => 'Oklahoma City', 'capacity' => 18203],
        'ORL' => ['nba_id' => 1610612753, 'apisports_id' => 26, 'arena' => 'Kia Center', 'city' => 'Orlando', 'capacity' => 18846],
        'PHI' => ['nba_id' => 1610612755, 'apisports_id' => 27, 'arena' => 'Wells Fargo Center', 'city' => 'Philadelphia', 'capacity' => 20478],
        'PHX' => ['nba_id' => 1610612756, 'apisports_id' => 28, 'arena' => 'Footprint Center', 'city' => 'Phoenix', 'capacity' => 18055],
        'POR' => ['nba_id' => 1610612757, 'apisports_id' => 29, 'arena' => 'Moda Center', 'city' => 'Portland', 'capacity' => 19393],
        'SAC' => ['nba_id' => 1610612758, 'apisports_id' => 30, 'arena' => 'Golden 1 Center', 'city' => 'Sacramento', 'capacity' => 17608],
        'SAS' => ['nba_id' => 1610612759, 'apisports_id' => 31, 'arena' => 'Frost Bank Center', 'city' => 'San Antonio', 'capacity' => 18418],
        'TOR' => ['nba_id' => 1610612761, 'apisports_id' => 38, 'arena' => 'Scotiabank Arena', 'city' => 'Toronto', 'capacity' => 19800],
        'UTA' => ['nba_id' => 1610612762, 'apisports_id' => 40, 'arena' => 'Delta Center', 'city' => 'Salt Lake City', 'capacity' => 18306],
        'WAS' => ['nba_id' => 1610612764, 'apisports_id' => 41, 'arena' => 'Capital One Arena', 'city' => 'Washington', 'capacity' => 20356],
    ],

    /*
    |--------------------------------------------------------------------------
    | Titles and founding years
    |--------------------------------------------------------------------------
    |
    | Neither integrated API exposes this data. Every field of both /teams
    | payloads was checked: balldontlie returns id, conference, division,
    | city, name, full_name and abbreviation; api-sports returns id, name,
    | nickname, code, city, logo, allStar, nbaFranchise and leagues. There is
    | no championship counter and no founding date anywhere, so both values are
    | maintained by hand here.
    |
    | founded_year is the ORIGINAL franchise year, not the year the team
    | arrived in its current city: the Lakers are 1947 (Minneapolis) rather
    | than 1960, the Warriors are 1946 (Philadelphia) rather than 1971.
    | championships_count counts NBA titles only, so the Pacers stay at 0
    | despite their ABA titles. Totals reflect the titles decided through the
    | 2024 championship plus OKC in 2025; anything decided after that is not
    | reflected here.
    |
    */

    'history' => [
        'ATL' => ['championships' => 1, 'founded_year' => 1946],
        'BOS' => ['championships' => 18, 'founded_year' => 1946],
        'BKN' => ['championships' => 0, 'founded_year' => 1967],
        'CHA' => ['championships' => 0, 'founded_year' => 1988],
        'CHI' => ['championships' => 6, 'founded_year' => 1966],
        'CLE' => ['championships' => 1, 'founded_year' => 1946],
        'DAL' => ['championships' => 1, 'founded_year' => 1980],
        'DEN' => ['championships' => 1, 'founded_year' => 1967],
        'DET' => ['championships' => 3, 'founded_year' => 1946],
        'GSW' => ['championships' => 7, 'founded_year' => 1946],
        'HOU' => ['championships' => 2, 'founded_year' => 1967],
        'IND' => ['championships' => 0, 'founded_year' => 1967],
        'LAC' => ['championships' => 0, 'founded_year' => 1970],
        'LAL' => ['championships' => 17, 'founded_year' => 1947],
        'MEM' => ['championships' => 0, 'founded_year' => 1995],
        'MIA' => ['championships' => 3, 'founded_year' => 1988],
        'MIL' => ['championships' => 1, 'founded_year' => 1968],
        'MIN' => ['championships' => 1, 'founded_year' => 1989],
        'NOP' => ['championships' => 0, 'founded_year' => 1988],
        'NYK' => ['championships' => 2, 'founded_year' => 1946],
        'OKC' => ['championships' => 1, 'founded_year' => 1967],
        'ORL' => ['championships' => 0, 'founded_year' => 1989],
        'PHI' => ['championships' => 2, 'founded_year' => 1963],
        'PHX' => ['championships' => 0, 'founded_year' => 1968],
        'POR' => ['championships' => 1, 'founded_year' => 1970],
        'SAC' => ['championships' => 1, 'founded_year' => 1951],
        'SAS' => ['championships' => 5, 'founded_year' => 1976],
        'TOR' => ['championships' => 1, 'founded_year' => 1995],
        'UTA' => ['championships' => 0, 'founded_year' => 1974],
        'WAS' => ['championships' => 1, 'founded_year' => 1946],
    ],

    /*
    |--------------------------------------------------------------------------
    | Seasons
    |--------------------------------------------------------------------------
    |
    | balldontlie exposes the current season, while the api-sports free plan
    | only reaches back to 2024, so the two are tracked separately.
    |
    */

    'seasons' => [
        'current' => (int) env('NBA_SEASON_CURRENT', 2025),
        'apisports' => (int) env('APISPORTS_SEASON', 2024),
    ],

];
