<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'tags',
        'content',
        'cover_image',
        'is_published',
        'published_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'tags' => 'array',
        ];
    }

    /**
     * Daftar tag artikel yang sudah dinormalisasi.
     *
     * @return array<int, string>
     */
    public function tagList(): array
    {
        return collect($this->tags ?? [])
            ->filter(fn ($tag) => filled($tag))
            ->map(fn ($tag) => trim((string) $tag))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Hanya artikel yang sudah dipublikasikan dan waktunya sudah tiba.
     */
    public function scopePublished($query)
    {
        return $query
            ->where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Tanggal yang ditampilkan ke pembaca.
     */
    public function displayDate()
    {
        return $this->published_at ?? $this->created_at;
    }

    /**
     * Perkiraan waktu baca dalam menit.
     */
    public function readingTime(): int
    {
        $words = preg_match_all('/\S+/', strip_tags((string) $this->content));

        return max(1, (int) ceil($words / 200));
    }

    /**
     * Ringkasan artikel; pakai excerpt jika ada, jika tidak potong isi artikel.
     */
    public function summary(int $limit = 160): string
    {
        if (filled($this->excerpt)) {
            return $this->excerpt;
        }

        return Str::limit(trim(strip_tags((string) $this->content)), $limit);
    }
}
