<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerStatsSeason extends Model
{
    use HasFactory;

    protected $table = 'player_stats_season';

    protected $fillable = [
        'player_id',
        'season',
        'games_played',
        'points_per_game',
        'rebounds_per_game',
        'assists_per_game',
        'field_goal_percentage',
        'three_point_percentage',
        'free_throw_percentage',
    ];

    protected function casts(): array
    {
        return [
            'games_played' => 'integer',
            'points_per_game' => 'decimal:2',
            'rebounds_per_game' => 'decimal:2',
            'assists_per_game' => 'decimal:2',
            'field_goal_percentage' => 'decimal:2',
            'three_point_percentage' => 'decimal:2',
            'free_throw_percentage' => 'decimal:2',
        ];
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}