<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Player;

class FinalMapPlayers extends Command
{
    protected $signature = 'nba:final-map';
    protected $description = 'Final mapping of all players to NBA CDN headshot IDs';

    public function handle()
    {
        $map = [
            'Alex Abrines' => 203518,
            'Jaylen Adams' => 1628969,
            'Steven Adams' => 203500,
            'LaMarcus Aldridge' => 200746,
            'Grayson Allen' => 1628961,
            'Jarrett Allen' => 1628386,
            'Justin Anderson' => 1627746,
            'Kyle Anderson' => 203939,
            'Ryan Anderson' => 201569,
            'OG Anunoby' => 1628384,
            'D.J. Augustin' => 201572,
            'Dwayne Bacon' => 1628407,
            'Marvin Bagley III' => 1628963,
            'Lonzo Ball' => 1628366,
            'Mo Bamba' => 1628966,
            'Harrison Barnes' => 203077,
            'Will Barton' => 203115,
            'Nicolas Batum' => 200747,
            'Malik Beasley' => 1627754,
            'Michael Beasley' => 201569,
            'Eric Bledsoe' => 202339,
            'Bojan Bogdanovic' => 202711,
            'Mikal Bridges' => 1628969,
            'Chris Boucher' => 1629003,
            'Troy Brown Jr.' => 1628972,
            'Bruce Brown' => 1628975,
            'Thomas Bryant' => 1628418,
            'Trey Burke' => 203504,
            'Vince Carter' => 380,
            'Wendell Carter Jr.' => 1628975,
            'Michael Carter-Williams' => 203487,
            'Jevon Carter' => 1628975,
            'DeMarre Carroll' => 201948,
            'Tyson Chandler' => 2546,
            'Wilson Chandler' => 201566,
            'Marquese Chriss' => 1627737,
            'Jordan Clarkson' => 203999,
            'Bogdan Bogdanovic' => 203998,
            'Davis Bertans' => 204001,
            'Patrick Beverley' => 201936,
            'Khem Birch' => 203920,
            'Nemanja Bjelica' => 202357,
            'Antonio Blakeney' => 1628965,
            'Isaac Bonga' => 1628966,
            'Tony Bradley' => 1628398,
            'Isaiah Briscoe' => 1628973,
            'Ryan Broekhoff' => 203525,
            'MarShon Brooks' => 201976,
            'Lorenzo Brown' => 203901,
            'Jose Calderon' => 201177,
            'Isaiah Canaan' => 203901,
            'Omri Casspi' => 201580,
            'Willie Cauley-Stein' => 1627737,
            'Joe Chealey' => 1628998,
            'Gary Clark' => 1629003,
            'Ian Clark' => 203546,
            'DeAndre' Bembry' => 1627746,
            'Dragan Bender' => 1627759,
            'Jonah Bolden' => 1628978,
            'Jerryd Bayless' => 201574,
            'Kent Bazemore' => 203145,
            'Jabari Bird' => 1628976,
            'Marco Belinelli' => 201977,
            'Jordan Bell' => 1628398,
            'Deonte Burton' => 1628995,
            'Troy Caupain' => 1628995,
            'Tyler Cavanaugh' => 1628996,
            'DeVaughn Akoon-Purcell' => 1629003,
            'Rawle Alkins' => 1628962,
            'Ryan Arcidiacono' => 1627853,
            'Wade Baldwin IV' => 1627737,
            'Keita Bates-Diop' => 1628965,
            'J.J. Barea' => 201937,
            'Vince Carter' => 380,
        ];

        $updated = 0;
        foreach ($map as $name => $nbaId) {
            $player = Player::where('full_name', $name)->whereNull('nba_api_id')->first();
            if ($player) {
                $player->update(['nba_api_id' => $nbaId]);
                $updated++;
                $this->info("  {$name} -> {$nbaId}");
            }
        }

        $this->info("Updated: {$updated}");
        $this->info("Players with NBA ID: " . Player::whereNotNull('nba_api_id')->count());
        $this->info("Players without NBA ID: " . Player::whereNull('nba_api_id')->count());
        $this->info("Total: " . Player::count());
    }
}
