<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogTest extends TestCase
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

    private function publishedPost(array $overrides = []): Blog
    {
        return Blog::create(array_merge([
            'title' => 'Tips Layout Responsif',
            'slug' => 'tips-layout-responsif',
            'excerpt' => 'Ringkasan tips layout responsif.',
            'content' => "Paragraf pertama tentang layout.\n\nParagraf kedua tentang Tailwind.",
            'is_published' => true,
            'published_at' => now(),
        ], $overrides));
    }

    public function test_guest_can_browse_published_blog_posts(): void
    {
        $post = $this->publishedPost();

        $this->get('/blog')
            ->assertOk()
            ->assertSee('Blog Pengembangan')
            ->assertSee($post->title)
            ->assertSee($post->excerpt);

        $this->get("/blog/{$post->slug}")
            ->assertOk()
            ->assertSee($post->title)
            ->assertSee('Paragraf pertama tentang layout.')
            ->assertSee('Paragraf kedua tentang Tailwind.');
    }

    public function test_draft_and_future_posts_are_hidden_from_public(): void
    {
        $draft = $this->publishedPost([
            'title' => 'Artikel Draft',
            'slug' => 'artikel-draft',
            'is_published' => false,
            'published_at' => null,
        ]);

        $scheduled = $this->publishedPost([
            'title' => 'Artikel Terjadwal',
            'slug' => 'artikel-terjadwal',
            'published_at' => now()->addDay(),
        ]);

        $this->get('/blog')
            ->assertOk()
            ->assertDontSee($draft->title)
            ->assertDontSee($scheduled->title);

        $this->get("/blog/{$draft->slug}")->assertNotFound();
        $this->get("/blog/{$scheduled->slug}")->assertNotFound();
    }

    public function test_blog_search_matches_title_and_content(): void
    {
        $layout = $this->publishedPost();
        $database = $this->publishedPost([
            'title' => 'Optimasi Query Database',
            'slug' => 'optimasi-query-database',
            'excerpt' => 'Tentang performa query.',
            'content' => 'Gunakan index dan hindari N+1.',
        ]);

        $this->get('/blog?q=layout')
            ->assertOk()
            ->assertSee($layout->title)
            ->assertDontSee($database->title);

        $this->get('/blog?q=N%2B1')
            ->assertOk()
            ->assertSee($database->title)
            ->assertDontSee($layout->title);

        $this->get('/blog?q=tidak-ada-hasil')
            ->assertOk()
            ->assertSee('Tidak ada artikel yang cocok');
    }

    public function test_admin_can_create_blog_post_with_cover_image(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $cover = UploadedFile::fake()->image('cover.png', 1200, 800);

        $response = $this->actingAs($admin)->post('/admin/blogs', [
            'title' => 'Membuat Blog dengan Laravel',
            'slug' => '',
            'excerpt' => 'Panduan membuat blog.',
            'content' => "Langkah pertama membuat migration.\n\nLangkah kedua membuat controller.",
            'cover_image' => $cover,
            'is_published' => '1',
            'published_at' => '',
        ]);

        $response->assertRedirect(route('admin.blogs.index'));

        $post = Blog::first();

        $this->assertSame('membuat-blog-dengan-laravel', $post->slug);
        $this->assertTrue($post->is_published);
        $this->assertNotNull($post->published_at);
        Storage::disk('public')->assertExists($post->cover_image);

        $this->get("/blog/{$post->slug}")
            ->assertOk()
            ->assertSee($post->title);
    }

    public function test_admin_can_update_and_delete_blog_post(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $post = $this->publishedPost([
            'cover_image' => UploadedFile::fake()->image('lama.png')->store('blogs', 'public'),
        ]);

        $oldCover = $post->cover_image;
        $newCover = UploadedFile::fake()->image('baru.png', 1200, 800);

        $this->actingAs($admin)->put("/admin/blogs/{$post->id}", [
            'title' => 'Tips Layout Responsif (Revisi)',
            'slug' => $post->slug,
            'excerpt' => 'Ringkasan revisi.',
            'content' => 'Isi artikel setelah revisi.',
            'cover_image' => $newCover,
            'is_published' => '1',
            'published_at' => $post->published_at->format('Y-m-d\TH:i'),
        ])->assertRedirect(route('admin.blogs.index'));

        $post->refresh();

        $this->assertSame('Tips Layout Responsif (Revisi)', $post->title);
        $this->assertNotSame($oldCover, $post->cover_image);
        Storage::disk('public')->assertMissing($oldCover);
        Storage::disk('public')->assertExists($post->cover_image);

        $newPath = $post->cover_image;

        $this->actingAs($admin)->delete("/admin/blogs/{$post->id}")
            ->assertRedirect(route('admin.blogs.index'));

        $this->assertDatabaseMissing('blogs', ['id' => $post->id]);
        Storage::disk('public')->assertMissing($newPath);
    }

    public function test_admin_can_unpublish_blog_post(): void
    {
        $admin = $this->admin();
        $post = $this->publishedPost();

        $this->actingAs($admin)->put("/admin/blogs/{$post->id}", [
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'content' => $post->content,
            'is_published' => '0',
            'published_at' => '',
        ])->assertRedirect(route('admin.blogs.index'));

        $post->refresh();

        $this->assertFalse($post->is_published);
        $this->assertNull($post->published_at);
        $this->get("/blog/{$post->slug}")->assertNotFound();
    }

    public function test_blog_slug_is_made_unique(): void
    {
        $admin = $this->admin();

        $payload = [
            'title' => 'Judul yang Sama',
            'slug' => '',
            'excerpt' => 'Ringkasan.',
            'content' => 'Isi artikel.',
            'is_published' => '1',
            'published_at' => '',
        ];

        $this->actingAs($admin)->post('/admin/blogs', $payload);
        $this->actingAs($admin)->post('/admin/blogs', $payload);

        $this->assertDatabaseHas('blogs', ['slug' => 'judul-yang-sama']);
        $this->assertDatabaseHas('blogs', ['slug' => 'judul-yang-sama-2']);
    }

    public function test_non_admin_cannot_manage_blog(): void
    {
        $user = User::create([
            'name' => 'User Biasa',
            'username' => 'userbiasa',
            'password' => 'password',
            'role' => 'user',
        ]);

        $this->actingAs($user)->get('/admin/blogs')->assertRedirect('/');

        $this->actingAs($user)->post('/admin/blogs', [
            'title' => 'Tidak Boleh',
            'content' => 'Isi.',
            'is_published' => '1',
        ])->assertRedirect('/');

        $this->assertDatabaseMissing('blogs', ['title' => 'Tidak Boleh']);
    }

    public function test_guest_cannot_manage_blog(): void
    {
        $this->get('/admin/blogs')->assertRedirect('/login');
    }
}
