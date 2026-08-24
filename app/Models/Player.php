<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_id',
        'nba_api_id',
        'team_id',
        'full_name',
        'nickname',
        'position',
        'height_cm',
        'weight_kg',
        'wingspan_cm',
        'birth_date',
        'nationality',
        'jersey_number',
        'photo_url',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'height_cm' => 'decimal:2',
            'weight_kg' => 'decimal:2',
            'wingspan_cm' => 'decimal:2',
        ];
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function teamHistory()
    {
        return $this->hasMany(PlayerTeamHistory::class);
    }

    public function careerStats()
    {
        return $this->hasOne(PlayerStatsCareer::class);
    }

    public function seasonStats()
    {
        return $this->hasMany(PlayerStatsSeason::class);
    }

    public function getPhotoAttribute()
    {
        if ($this->photo_url) return $this->photo_url;
        if ($this->nba_api_id) return "https://cdn.nba.com/headshots/nba/latest/1040x760/{$this->nba_api_id}.png";
        return asset('images/placeholder.svg');
    }

    public function getHeightFormattedAttribute()
    {
        if (!$this->height_cm) return '-';
        $feet = floor($this->height_cm / 30.48);
        $inches = round(($this->height_cm / 2.54) - ($feet * 12));
        return "{$feet}'{$inches}\"";
    }

    public function getWeightFormattedAttribute()
    {
        return $this->weight_kg ? round($this->weight_kg * 2.205) . ' lbs' : '-';
    }
}