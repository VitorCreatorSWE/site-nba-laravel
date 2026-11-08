<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_id',
        'apisports_id',
        'first_name',
        'last_name',
        'slug',
        'position',
        'jersey_number',
        'height',
        'height_meters',
        'weight',
        'weight_kg',
        'birth_date',
        'country',
        'college',
        'draft_year',
        'draft_round',
        'draft_number',
        'career_start',
        'seasons_played',
        'is_active',
        'season',
        'team_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'height_meters' => 'decimal:2',
        'weight_kg' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Player $player) {
            if (blank($player->slug)) {
                $player->slug = static::uniqueSlug($player);
            }
        });
    }

    public static function uniqueSlug(Player $player): string
    {
        $base = Str::slug(trim($player->first_name . ' ' . $player->last_name));
        $base = $base !== '' ? $base : 'player-' . $player->getKey();

        $slug = $base;
        $suffix = 2;

        while (static::where('slug', $slug)->whereKeyNot($player->getKey())->exists()) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date?->age;
    }

    public function getHeightInchesAttribute(): ?int
    {
        if (! $this->height || ! preg_match('/^(\d+)-(\d+)$/', $this->height, $m)) {
            return null;
        }

        return ((int) $m[1] * 12) + (int) $m[2];
    }

    public function getInitialsAttribute(): string
    {
        return Str::upper(Str::substr($this->first_name, 0, 1) . Str::substr($this->last_name, 0, 1));
    }

    /**
     * Foto individual do jogador.
     *
     * Nem a balldontlie nem a API-Sports expõem campo de imagem por jogador, e a
     * tabela players nem sequer tem coluna de foto. Nunca substituir pelo logo do
     * time: e marca registrada da NBA e ainda confunde time com atleta.
     *
     * As views tratam o null desenhando as iniciais. Quando vier uma fonte com
     * licencia propria, e aqui que a URL deve ser exposta.
     */
    public function getPhotoAttribute(): ?string
    {
        return null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, ?string $term)
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $term) . '%';

        return $query->where(function ($q) use ($like) {
            $q->where('first_name', 'like', $like)
                ->orWhere('last_name', 'like', $like)
                ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", [$like]);
        });
    }
}
