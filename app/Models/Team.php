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

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public static function currentAbbreviations(): array
    {
        return array_keys(config('nba.teams', []));
    }

    public static function currentTeamData(): array
    {
        return config('nba.teams', []);
    }

/**
 * Emblema generico, usado quando o time nao tem nba_api_id.
 *
 * Guardado sem a barra inicial para poder virar tanto asset() nas views
 * quanto caminho relativo no banco (que continua valendo se o host mudar).
 */
public const NEUTRAL_LOGO = 'images/team-placeholder.svg';

/**
 * Emblema exibido no lugar do time.
 *
 * teams.logo_url tem precedencia: e o caminho para cadastrar um asset com
 * licenca propria. Sem ele, usa o emblema neutro local — os escudos do CDN
 * da NBA sao marca registrada e nao podem ser usados sem licenca.
 */
public function getLogoAttribute()
{
    if ($this->logo_url) {
        return $this->logo_url;
    }

    return asset(self::NEUTRAL_LOGO);
}
}
