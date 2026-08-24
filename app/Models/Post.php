<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'cover_image',
        'category',
        'status',
        'published_at',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'views_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments()
    {
        return $this->comments()->where('approved', true);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'noticia' => 'Notícia',
            'jogo' => 'Jogo',
            'estatistica' => 'Estatística',
            'recente' => 'Notícia Recente',
            'analise' => 'Análise',
            default => $this->category,
        };
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}