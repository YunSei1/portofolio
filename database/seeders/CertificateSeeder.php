<?php

namespace Database\Seeders;

use App\Models\Certificate;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $certificates = [
            [
                'title' => 'Sertifikat Kompetensi Keahlian',
                'issuer' => 'SMK',
                'issue_date' => '2026-05-15',
                'description' => 'Sertifikat kelulusan uji kompetensi keahlian jurusan RPL.',
                'image' => 'certificates/sertifikat-asli.png',
            ],
            [
                'title' => 'Pelatihan Web Development',
                'issuer' => 'Dicoding',
                'issue_date' => '2026-01-20',
                'description' => 'Pelatihan dasar HTML, CSS, dan JavaScript.',
                'image' => 'certificates/sertifikat-asli.png',
            ],
        ];

        foreach ($certificates as $certificate) {
            Certificate::updateOrCreate(
                ['title' => $certificate['title']],
                $certificate
            );
        }
    }
}