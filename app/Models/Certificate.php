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
            // 1. Cek sebagai file fisik di public/ (aset bawaan yang ikut ter-commit).
            $publicPath = public_path($image);
            if (is_file($publicPath)) {
                return asset($image);
            }

            // 2. Cek di storage disk 'public' (file hasil upload via Laravel store()).
            if (Storage::disk('public')->exists($image)) {
                // Lewat controller agar tidak bergantung pada symlink public/storage.
                return route('certificates.image', $this);
            }

            // 3. Fallback: gambar placeholder bawaan yang sudah ikut ter-commit di public/certificates/.
            if (file_exists(public_path('certificates/placeholder.svg'))) {
                return asset('certificates/placeholder.svg');
            }
        }

        // 4. Ultimate fallback: return string kosong agar tidak break tampilan.
        return '';
    }
}
