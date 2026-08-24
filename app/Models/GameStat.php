<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameStat extends Model
{
    use HasFactory;

    protected $table = 'game_stats';

    protected $fillable = [
        'game_id',
        'team_id',
        'field_goals_made',
        'field_goals_attempted',
        'three_pointers_made',
        'three_pointers_attempted',
        'free_throws_made',
        'free_throws_attempted',
        'rebounds',
        'assists',
        'steals',
        'blocks',
        'turnovers',
        'points',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function getFieldGoalPercentageAttribute(): float
    {
        return $this->field_goals_attempted > 0
            ? round(($this->field_goals_made / $this->field_goals_attempted) * 100, 1)
            : 0;
    }

    public function getThreePointPercentageAttribute(): float
    {
        return $this->three_pointers_attempted > 0
            ? round(($this->three_pointers_made / $this->three_pointers_attempted) * 100, 1)
            : 0;
    }
}