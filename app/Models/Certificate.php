<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'issuer',
        'issue_date',
        'description',
        'image',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
        ];
    }

    /**
     * URL gambar yang tetap tampil walaupun storage kosong setelah deploy.
     *
     * Urutan prioritas: aset bawaan di public/, file hasil upload di storage,
     * lalu placeholder bawaan.
     */
    public function imageUrl(): string
    {
        $image = $this->image;

        if ($image) {
            // Aset yang ikut ter-commit, jadi tidak bergantung pada public/storage.
            if (is_file(public_path($image))) {
                return asset($image);
            }

            if (Storage::disk('public')->exists($image)) {
                return asset('storage/' . $image);
            }
        }

        return asset('certificates/placeholder.svg');
    }
}
