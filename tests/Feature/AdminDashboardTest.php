<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'password' => 'password',
            'role' => 'admin',
        ]);
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_dashboard(): void
    {
        $user = $this->admin();

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Admin Dashboard');
        $response->assertSee('Curriculum Vitae (CV)');
        $response->assertSee('Kelola CV');
    }

    public function test_admin_can_create_certificate_with_image_upload(): void
    {
        Storage::fake('public');
        $user = $this->admin();

        $file = UploadedFile::fake()->image('sertifikat.png', 800, 600);

        $response = $this->actingAs($user)->post('/admin/certificates', [
            'title' => 'Sertifikat Ujian Coba',
            'issuer' => 'BNSP',
            'issue_date' => '2026-01-15',
            'description' => 'Sertifikat untuk testing.',
            'image' => $file,
        ]);

        $response->assertRedirect(route('admin.certificates.index'));
        $this->assertDatabaseHas('certificates', [
            'title' => 'Sertifikat Ujian Coba',
            'issuer' => 'BNSP',
        ]);

        $certificate = Certificate::first();
        Storage::disk('public')->assertExists($certificate->image);

        // Gambar hasil upload langsung tampil tanpa symlink public/storage.
        $this->assertSame(route('certificates.image', $certificate), $certificate->imageUrl());

        $this->get(route('certificates.image', $certificate))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');
    }

    public function test_admin_can_update_certificate(): void
    {
        $user = $this->admin();

        $certificate = Certificate::create([
            'title' => 'Judul Lama',
            'issuer' => 'Lama',
            'issue_date' => '2026-01-01',
            'description' => 'Deskripsi lama',
        ]);

        $response = $this->actingAs($user)->put("/admin/certificates/{$certificate->id}", [
            'title' => 'Judul Baru',
            'issuer' => 'Baru',
            'issue_date' => '2026-02-02',
            'description' => 'Deskripsi baru',
        ]);

        $response->assertRedirect(route('admin.certificates.index'));
        $this->assertDatabaseHas('certificates', ['title' => 'Judul Baru']);
    }

    public function test_admin_can_delete_certificate(): void
    {
        Storage::fake('public');
        $user = $this->admin();

        $certificate = Certificate::create([
            'title' => 'Hapus Saya',
        ]);

        $response = $this->actingAs($user)->delete("/admin/certificates/{$certificate->id}");

        $response->assertRedirect(route('admin.certificates.index'));
        $this->assertDatabaseMissing('certificates', ['title' => 'Hapus Saya']);
    }

    public function test_certificate_image_falls_back_to_bundled_placeholder(): void
    {
        Storage::fake('public');

        $certificate = Certificate::create([
            'title' => 'Sertifikat Tanpa File',
            'image' => 'certificate-1.png',
        ]);

        $this->assertSame(asset('certificates/placeholder.svg'), $certificate->imageUrl());

        $this->get('/')
            ->assertOk()
            ->assertSee('certificates/placeholder.svg', false);
    }

    public function test_certificate_image_resolves_bundled_and_uploaded_files(): void
    {
        Storage::fake('public');

        $certificate = Certificate::create(['title' => 'Sertifikat Uji']);

        $certificate->image = 'certificates/sertifikat-asli.png';
        $this->assertSame(asset('certificates/sertifikat-asli.png'), $certificate->imageUrl());

        Storage::disk('public')->put('certificates/hasil-upload.png', 'dummy');
        $certificate->image = 'certificates/hasil-upload.png';
        $this->assertSame(route('certificates.image', $certificate), $certificate->imageUrl());
    }

    public function test_certificate_image_route_returns_404_when_file_is_missing(): void
    {
        Storage::fake('public');

        $certificate = Certificate::create([
            'title' => 'Sertifikat Tanpa File',
            'image' => 'certificates/hilang.png',
        ]);

        $this->get(route('certificates.image', $certificate))->assertNotFound();
    }

    public function test_quick_nav_contact_icon_links_to_contact_page(): void
    {
        $contactUrl = route('contact.show');

        // Quick-nav dipakai di halaman portofolio, termasuk saat admin membukanya.
        $this->get('/')
            ->assertOk()
            ->assertSee('name="contact-url"', false)
            ->assertSee('content="' . $contactUrl . '"', false);

        $this->get(route('contact.show'))
            ->assertOk()
            ->assertSee('content="' . $contactUrl . '"', false);

        // Quick nav dibuat oleh theme.js, pastikan tidak mengarah ke anchor home lagi.
        $js = file_get_contents(public_path('js/theme.js'));
        $this->assertStringContainsString("metaUrl('contact-url', '/contact')", $js);
        $this->assertStringNotContainsString("href: '/#contact'", $js);
    }

    public function test_contact_form_stores_message(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Pengirim Coba',
            'email' => 'pengirim@contoh.com',
            'message' => 'Halo, ini pesan uji coba.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'name' => 'Pengirim Coba',
            'email' => 'pengirim@contoh.com',
            'is_read' => false,
        ]);
    }

    public function test_admin_can_view_and_manage_messages(): void
    {
        $user = $this->admin();

        $message = Message::create([
            'name' => 'Pengirim Coba',
            'email' => 'pengirim@contoh.com',
            'message' => 'Halo, ini pesan uji coba.',
            'is_read' => false,
        ]);

        // Muncul di daftar pesan masuk
        $this->actingAs($user)->get('/admin/messages')
            ->assertOk()
            ->assertSee('Pengirim Coba');

        // Membuka pesan menandai sudah dibaca
        $this->actingAs($user)->get("/admin/messages/{$message->id}")
            ->assertOk()
            ->assertSee('Halo, ini pesan uji coba.');

        $this->assertDatabaseHas('messages', ['id' => $message->id, 'is_read' => true]);

        // Toggle kembali ke belum dibaca
        $this->actingAs($user)->patch("/admin/messages/{$message->id}/toggle");
        $this->assertDatabaseHas('messages', ['id' => $message->id, 'is_read' => false]);

        // Hapus pesan
        $this->actingAs($user)->delete("/admin/messages/{$message->id}");
        $this->assertDatabaseMissing('messages', ['id' => $message->id]);
    }

    public function test_admin_can_update_profile(): void
    {
        Storage::fake('public');
        $user = $this->admin();

        Profile::create([
            'name' => 'Nama Lama',
            'role' => 'Role Lama',
            'bio' => 'Bio lama.',
        ]);

        $file = UploadedFile::fake()->image('foto.png', 400, 500);

        $response = $this->actingAs($user)->put('/admin/profile', [
            'name' => 'Nama Baru',
            'role' => 'Full-Stack Developer',
            'bio' => 'Bio baru saya.',
            'badge_text' => 'Open to Work',
            'photo' => $file,
            'instagram_url' => 'https://www.instagram.com/contoh/',
            'whatsapp_number' => '6281234567890',
            'email' => 'baru@contoh.com',
        ]);

        $response->assertRedirect(route('admin.profile.edit'));

        $profile = Profile::first();
        $this->assertSame('Nama Baru', $profile->name);
        $this->assertSame('Full-Stack Developer', $profile->role);
        $this->assertSame('6281234567890', $profile->whatsapp_number);
        Storage::disk('public')->assertExists($profile->photo);
    }

    public function test_admin_can_upload_replace_and_delete_cv(): void
    {
        Storage::fake('public');
        $user = $this->admin();

        Profile::create([
            'name' => 'Nama CV',
            'role' => 'Web Developer',
        ]);

        // Upload CV pertama
        $first = UploadedFile::fake()->create('cv-awal.pdf', 500, 'application/pdf');

        $this->actingAs($user)->post('/admin/cv', ['cv' => $first])
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('success', 'CV berhasil diunggah!');

        $profile = Profile::first();
        $firstPath = $profile->cv_file;

        $this->assertNotNull($firstPath);
        Storage::disk('public')->assertExists($firstPath);

        // Tombol preview & download muncul di halaman publik
        $this->get('/')
            ->assertOk()
            ->assertSee('Preview CV')
            ->assertSee(route('cv.preview'))
            ->assertSee('Download CV')
            ->assertSee(route('cv.download'))
            ->assertSee('id="about"', false)
            ->assertSee('Tentang Saya');

        // Pengunjung bisa mengunduh CV
        $this->get('/download-cv')
            ->assertOk()
            ->assertDownload('CV-nama-cv.pdf');

        // Upload CV baru menggantikan file lama
        $second = UploadedFile::fake()->create(
            'cv-baru.docx',
            400,
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        );

        $this->actingAs($user)->post('/admin/cv', ['cv' => $second])
            ->assertRedirect(route('admin.dashboard'));

        $profile->refresh();
        $secondPath = $profile->cv_file;

        $this->assertNotSame($firstPath, $secondPath);
        Storage::disk('public')->assertMissing($firstPath);
        Storage::disk('public')->assertExists($secondPath);

        // Hapus CV
        $this->actingAs($user)->delete('/admin/cv')
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('success', 'CV berhasil dihapus.');

        $profile->refresh();

        $this->assertNull($profile->cv_file);
        Storage::disk('public')->assertMissing($secondPath);

        $this->get('/')->assertOk()->assertDontSee('Download CV');
    }

    public function test_cv_upload_requires_supported_file(): void
    {
        Storage::fake('public');
        $user = $this->admin();
        Profile::create(['name' => 'Nama CV', 'role' => 'Web Developer']);

        $file = UploadedFile::fake()->create('gambar.png', 100, 'image/png');

        $this->actingAs($user)->post('/admin/cv', ['cv' => $file])
            ->assertSessionHasErrors('cv');

        $this->assertNull(Profile::first()->cv_file);
    }

    public function test_cv_download_returns_404_when_no_cv_uploaded(): void
    {
        Profile::create(['name' => 'Nama CV', 'role' => 'Web Developer']);

        $this->get('/download-cv')->assertNotFound();
        $this->get('/cv/preview')->assertNotFound();
    }

    public function test_cv_preview_returns_inline_pdf_response(): void
    {
        Storage::fake('public');

        $pdf = UploadedFile::fake()->create('cv-preview.pdf', 120, 'application/pdf');
        $cvPath = Storage::disk('public')->putFile('cv', $pdf);

        Profile::create([
            'name' => 'Nama CV',
            'role' => 'Web Developer',
            'cv_file' => $cvPath,
        ]);

        $response = $this->get('/cv/preview');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('inline', (string) $response->headers->get('Content-Disposition'));
    }

    public function test_admin_can_manage_project_details_and_public_page(): void
    {
        Storage::fake('public');
        $user = $this->admin();

        $image = UploadedFile::fake()->image('project.png', 1200, 800);

        $payload = [
            'title' => 'Website Donasi Sekolah',
            'role' => 'Front-End Developer',
            'description' => 'Website untuk mengumpulkan donasi sekolah secara online.',
            'problem' => 'Donasi masih dicatat manual dan sulit dipantau.',
            'goal' => 'Mempermudah pengelolaan dan pemantauan donasi sekolah.',
            'technologies' => 'HTML, CSS, Bootstrap, PHP',
            'features' => "Landing page\nForm donasi\nDashboard admin",
            'documentation' => "Analisis kebutuhan\nDesain UI\nImplementasi front-end\nPengujian",
            'detail' => 'Project dikerjakan mulai dari riset kebutuhan hingga pengujian.',
            'demo_url' => 'https://demo.contoh.com',
            'source_url' => 'https://github.com/contoh/project',
            'feedback' => 'Website mudah digunakan dan informatif.',
            'feedback_author' => 'Guru Pembimbing',
            'image' => $image,
        ];

        $this->actingAs($user)->post('/admin/projects', $payload)
            ->assertRedirect(route('admin.projects.index'));

        $project = Project::first();

        $this->assertSame('Front-End Developer', $project->role);
        $this->assertSame(['HTML', 'CSS', 'Bootstrap', 'PHP'], $project->technologyList());
        $this->assertCount(3, $project->featureList());
        $this->assertCount(4, $project->documentationSteps());
        Storage::disk('public')->assertExists($project->image);

        // Gambar hasil upload langsung tampil tanpa symlink public/storage.
        $this->assertSame(route('projects.image', $project), $project->imageUrl());

        $this->get(route('projects.image', $project))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');

        // Form admin memuat seluruh field baru
        $this->actingAs($user)->get('/admin/projects/create')
            ->assertOk()
            ->assertSee('Peran dalam Project')
            ->assertSee('Permasalahan yang Ingin Diselesaikan')
            ->assertSee('Dokumentasi Proses Pekerjaan')
            ->assertSee('Feedback User');

        $this->actingAs($user)->get("/admin/projects/{$project->id}/edit")
            ->assertOk()
            ->assertSee('Website Donasi Sekolah')
            ->assertSee('Teknologi yang Digunakan');

        // Galeri proyek di homepage
        $this->get('/')
            ->assertOk()
            ->assertSee('Galeri Proyek')
            ->assertSee('Website Donasi Sekolah')
            ->assertSee('Front-End Developer')
            ->assertSee(route('projects.show', $project));

        // Halaman detail proyek publik
        $this->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee('Website Donasi Sekolah')
            ->assertSee('Donasi masih dicatat manual dan sulit dipantau.')
            ->assertSee('Mempermudah pengelolaan dan pemantauan donasi sekolah.')
            ->assertSee('Landing page')
            ->assertSee('Analisis kebutuhan')
            ->assertSee('Project dikerjakan mulai dari riset kebutuhan')
            ->assertSee('Website mudah digunakan dan informatif.')
            ->assertSee('Guru Pembimbing')
            ->assertSee('https://demo.contoh.com')
            ->assertSee('https://github.com/contoh/project');

        // Update detail dan ganti gambar
        $oldImage = $project->image;
        $newImage = UploadedFile::fake()->image('project-baru.png', 1200, 800);

        $this->actingAs($user)->put("/admin/projects/{$project->id}", array_merge($payload, [
            'title' => 'Website Donasi Sekolah v2',
            'image' => $newImage,
        ]))->assertRedirect(route('admin.projects.index'));

        $project->refresh();

        $this->assertSame('Website Donasi Sekolah v2', $project->title);
        $this->assertNotSame($oldImage, $project->image);
        Storage::disk('public')->assertMissing($oldImage);

        // Hapus project dan gambarnya
        $newPath = $project->image;

        $this->actingAs($user)->delete("/admin/projects/{$project->id}")
            ->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        Storage::disk('public')->assertMissing($newPath);
    }

    public function test_project_image_falls_back_to_bundled_placeholder(): void
    {
        Storage::fake('public');

        $project = Project::create([
            'title' => 'Project Tanpa File',
            'description' => 'Deskripsi project tanpa file.',
            'image' => 'projects/hilang.png',
            'is_published' => true,
        ]);

        $this->assertSame(asset('projects/placeholder.svg'), $project->imageUrl());

        $this->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee('projects/placeholder.svg', false);
    }

    public function test_project_image_route_returns_404_when_file_is_missing(): void
    {
        Storage::fake('public');

        $project = Project::create([
            'title' => 'Project Tanpa File',
            'description' => 'Deskripsi project tanpa file.',
            'image' => 'projects/hilang.png',
            'is_published' => true,
        ]);

        $this->get(route('projects.image', $project))->assertNotFound();
    }

    public function test_public_project_page_works_with_only_required_fields(): void
    {
        $project = Project::create([
            'title' => 'Project Minimal',
            'description' => 'Deskripsi project minimal.',
        ]);

        $this->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee('Project Minimal')
            ->assertSee('Deskripsi project minimal.')
            ->assertDontSee('Feedback User');
    }

    public function test_homepage_shows_dynamic_profile_and_certificates(): void
    {
        Profile::create([
            'name' => 'Nama Dinamis',
            'role' => 'Web Developer',
            'bio' => 'Bio dinamis untuk uji coba.',
            'badge_text' => 'Siap Kerja',
        ]);

        Certificate::create([
            'title' => 'Sertifikat Depan',
            'issuer' => 'Uji Coba',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Nama Dinamis');
        $response->assertSee('Web Developer');
        $response->assertSee('Bio dinamis untuk uji coba.');
        $response->assertSee('Siap Kerja');
        $response->assertSee('Sertifikat Depan');
        $response->assertSee('Sertifikasi');
    }
}
