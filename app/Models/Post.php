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
        return $this->comments();
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'noticia' => __('messages.noticia'),
            'jogo' => __('messages.game'),
            'estatistica' => __('messages.statistics'),
            'recente' => __('messages.recent_news'),
            'analise' => __('messages.analysis'),
            default => $this->category,
        };
    }

    public function getTitleAttribute($value): string
    {
        if (app()->getLocale() !== 'en' || !$this->game) {
            return $value;
        }

        [$winner, $loser, $score] = $this->englishGameData();
        return "{$winner} defeat {$loser} {$score} in an exciting NBA game";
    }

    public function getExcerptAttribute($value): ?string
    {
        if (app()->getLocale() !== 'en' || !$this->game) {
            return $value;
        }

        [$winner, $loser, $score] = $this->englishGameData();
        return "{$winner} defeated {$loser} {$score} in an intense NBA matchup full of big plays.";
    }

    public function getContentAttribute($value): string
    {
        if (app()->getLocale() !== 'en' || !$this->game) {
            return $value;
        }

        [$winner, $loser, $score] = $this->englishGameData();
        return "<p>{$winner} defeated {$loser} {$score} in an exciting NBA game. Both teams played with intensity from the opening tip, trading big shots and strong defensive plays throughout the matchup.</p><p>The game remained competitive until the final minutes. {$winner} found the right answers under pressure, controlled the closing possessions and secured a well-deserved victory over {$loser}.</p>";
    }

    /**
     * Pool local de imagens de capa — o mesmo carrossel da aba noticias.
     */
    public const NEWS_IMAGES = ['FxxaP.jpg', 'ZiRD0.jpg', 'sKunT.jpg', 'so1kB.jpg', 'ZwTSd.jpg', 'upJiz.jpg'];

    /**
     * Capa da noticia.
     *
     * As capas geradas apontavam para o CDN da NBA, que tem restricao de
     * licenca. URLs do CDN sao ignoradas e a capa passa a vir do pool local
     * de public/images (mesma configuração da aba noticias), escolhida de
     * forma estavel por id. Uma URL propria cadastrada pelo admin e respeitada.
     */
    public function getCoverImageAttribute($value)
    {
        if ($value && ! str_contains($value, 'cdn.nba.com')) {
            return $value;
        }

        if (! $this->id) {
            return $value;
        }

        return asset('images/' . self::NEWS_IMAGES[$this->id % count(self::NEWS_IMAGES)]);
    }

    private function englishGameData(): array
    {
        $game = $this->game;
        $homeWon = $game->home_score > $game->away_score;
        $winner = $homeWon ? $game->homeTeam : $game->awayTeam;
        $loser = $homeWon ? $game->awayTeam : $game->homeTeam;
        $score = ($homeWon ? $game->home_score : $game->away_score) . '-' . ($homeWon ? $game->away_score : $game->home_score);

        return [$winner->name, $loser->name, $score];
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
