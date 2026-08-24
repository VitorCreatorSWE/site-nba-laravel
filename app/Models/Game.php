<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_id',
        'home_team_id',
        'away_team_id',
        'game_date',
        'arena',
        'status',
        'home_score',
        'away_score',
        'season',
        'highlights',
        'admin_analysis',
    ];

    protected function casts(): array
    {
        return [
            'game_date' => 'datetime',
            'home_score' => 'integer',
            'away_score' => 'integer',
            'highlights' => 'array',
        ];
    }

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function stats()
    {
        return $this->hasMany(GameStat::class);
    }

    public function post()
    {
        return $this->hasOne(Post::class);
    }

    public function isLive(): bool
    {
        return $this->status === 'live';
    }

    public function isFinished(): bool
    {
        return $this->status === 'finished';
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function getScoreFormattedAttribute(): string
    {
        return "{$this->home_score} x {$this->away_score}";
    }
}