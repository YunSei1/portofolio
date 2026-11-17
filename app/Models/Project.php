<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'role',
        'description',
        'problem',
        'goal',
        'technologies',
        'features',
        'documentation',
        'detail',
        'image',
        'demo_url',
        'source_url',
        'feedback',
        'feedback_author',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Batasi query hanya pada project yang dipublikasikan.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Terapkan pencarian kata kunci dan filter ke query project.
     */
    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['status'] ?? null, function ($query, $status) {
                if ($status === 'published') {
                    $query->where('is_published', true);
                } elseif ($status === 'private') {
                    $query->where('is_published', false);
                }
            })
            ->when($filters['q'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    foreach (['title', 'description', 'role', 'technologies', 'problem', 'goal', 'features'] as $column) {
                        $query->orWhere($column, 'like', '%' . $search . '%');
                    }
                });
            })
            ->when($filters['technology'] ?? null, function ($query, $technology) {
                $query->where('technologies', 'like', '%' . $technology . '%');
            })
            ->when($filters['role'] ?? null, function ($query, $role) {
                $query->where('role', $role);
            });
    }

    /**
     * Urutkan hasil pencarian project.
     */
    public function scopeSorted($query, ?string $sort)
    {
        return match ($sort) {
            'oldest' => $query->oldest(),
            'title_asc' => $query->orderBy('title'),
            'title_desc' => $query->orderByDesc('title'),
            default => $query->latest(),
        };
    }

    /**
     * Daftar teknologi unik untuk pilihan filter.
     */
    public static function technologyOptions(bool $onlyPublished = false): array
    {
        return static::query()
            ->when($onlyPublished, fn ($query) => $query->published())
            ->pluck('technologies')
            ->flatMap(fn ($value) => preg_split('/[,;]+/', (string) $value))
            ->map(fn ($value) => trim($value))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * Daftar role unik untuk pilihan filter.
     */
    public static function roleOptions(bool $onlyPublished = false): array
    {
        return static::query()
            ->when($onlyPublished, fn ($query) => $query->published())
            ->whereNotNull('role')
            ->where('role', '!=', '')
            ->distinct()
            ->orderBy('role')
            ->pluck('role')
            ->all();
    }

    /**
     * Label status publikasi project.
     */
    public function visibilityLabel(): string
    {
        return $this->is_published ? 'Publikasi' : 'Private';
    }

    /**
     * URL gambar yang tetap tampil walaupun storage kosong setelah deploy.
     */
    public function imageUrl(): string
    {
        $image = $this->image;

        if ($image) {
            // Aset bawaan yang ikut ter-commit di public/.
            if (is_file(public_path($image))) {
                return asset($image);
            }

            if (Storage::disk('public')->exists($image)) {
                // Lewat controller agar tidak bergantung pada symlink public/storage.
                return route('projects.image', $this);
            }
        }

        return asset('projects/placeholder.svg');
    }

    /**
     * Ubah kolom teknologi yang dipisahkan koma menjadi daftar.
     */
    public function technologyList(): array
    {
        return $this->splitLines($this->technologies, '/[,;]+/');
    }

    /**
     * Ubah kolom fitur (satu per baris) menjadi daftar.
     */
    public function featureList(): array
    {
        return $this->splitLines($this->features, '/\r\n|\r|\n/');
    }

    /**
     * Ubah dokumentasi proses (satu langkah per baris) menjadi daftar.
     */
    public function documentationSteps(): array
    {
        return $this->splitLines($this->documentation, '/\r\n|\r|\n/');
    }

    /**
     * Bersihkan teks menjadi array tanpa baris kosong dan penanda list.
     */
    private function splitLines(?string $value, string $pattern): array
    {
        return collect(preg_split($pattern, (string) $value))
            ->map(function ($item) {
                $item = trim($item);
                $item = preg_replace('/^\s*(?:\d+[\.\)]|[-•*])\s*/u', '', $item);

                return trim($item);
            })
            ->filter()
            ->values()
            ->all();
    }
}
