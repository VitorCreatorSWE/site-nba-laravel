<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_id',
        'nba_api_id',
        'name',
        'abbreviation',
        'city',
        'state',
        'conference',
        'division',
        'arena_name',
        'arena_capacity',
        'arena_location',
        'founded_year',
        'championships_count',
        'logo_url',
    ];

    public function homeGames()
    {
        return $this->hasMany(Game::class, 'home_team_id');
    }

    public function awayGames()
    {
        return $this->hasMany(Game::class, 'away_team_id');
    }

    public function gameStats()
    {
        return $this->hasMany(GameStat::class);
    }

    public function getLogoAttribute()
    {
        if ($this->logo_url) return $this->logo_url;
        if ($this->nba_api_id) return "https://cdn.nba.com/logos/nba/{$this->nba_api_id}/global/L/logo.svg";
        return asset('images/placeholder.svg');
    }
}
