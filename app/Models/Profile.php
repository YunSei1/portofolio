<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'bio',
        'photo',
        'badge_text',
        'instagram_url',
        'whatsapp_number',
        'email',
        'cv_file',
    ];

    /**
     * Cari file CV yang benar-benar ada di storage.
     *
     * Dipakai sebagai fallback ketika data di database hilang atau path-nya
     * sudah tidak valid (misalnya database di-refresh tapi file CV masih ada).
     */
    public static function storedCvPath(): ?string
    {
        $path = collect(Storage::disk('public')->files('cv'))
            ->filter(fn (string $file) => in_array(
                strtolower(pathinfo($file, PATHINFO_EXTENSION)),
                ['pdf', 'doc', 'docx'],
                true
            ))
            ->sortByDesc(fn (string $file) => Storage::disk('public')->lastModified($file))
            ->first();

        if ($path) {
            return $path;
        }

        // Deploy baru biasanya punya storage kosong (file CV tidak ikut repo).
        // Pulihkan CV bawaan yang ikut ter-commit supaya download tetap jalan.
        $bundled = public_path('cv/Curriculum-Vitae.pdf');

        if (is_file($bundled)) {
            Storage::disk('public')->put('cv/Curriculum-Vitae.pdf', file_get_contents($bundled));

            return 'cv/Curriculum-Vitae.pdf';
        }

        return null;
    }

    /**
     * Path CV milik profil ini yang siap diunduh.
     */
    public function cvFilePath(): ?string
    {
        if ($this->cv_file && Storage::disk('public')->exists($this->cv_file)) {
            return $this->cv_file;
        }

        return static::storedCvPath();
    }

    /**
     * Path CV yang bisa dipakai halaman publik, dengan atau tanpa data profil.
     */
    public static function availableCvPath(): ?string
    {
        $profile = static::first();

        // Profil dengan cv_file kosong berarti admin memang menghapus CV.
        if ($profile) {
            return $profile->cv_file ? $profile->cvFilePath() : null;
        }

        // Belum ada data profil, tapi file CV sudah ada di storage.
        return static::storedCvPath();
    }

    public static function hasCv(): bool
    {
        return static::availableCvPath() !== null;
    }
}
