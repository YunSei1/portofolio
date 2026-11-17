<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Muhammad Rizky',
                'role' => 'Front-End Developer',
                'bio' => 'Saya adalah seorang front-end developer yang bersemangat dalam menciptakan pengalaman digital yang menarik dan responsif. Dengan keahlian dalam HTML, CSS, JavaScript, dan framework modern seperti React, saya berkomitmen untuk mengubah ide menjadi realitas yang memukau di dunia web.',
                'photo' => null,
                'badge_text' => 'Available for New Projects',
                'instagram_url' => 'https://www.instagram.com/yun_sei15/',
                'whatsapp_number' => '6285939155761',
                'email' => 'muhammadrizkyy232@gmail.com',
            ]
        );
    }
}
