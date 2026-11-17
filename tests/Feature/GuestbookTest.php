<?php

namespace Tests\Feature;

use App\Models\GuestMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestbookTest extends TestCase
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

    public function test_guestbook_page_shows_form_and_empty_state(): void
    {
        $this->get('/kesan-pesan')
            ->assertOk()
            ->assertSee('Kesan &amp; Pesan', false)
            ->assertSee('Tulis Kesan &amp; Pesan', false)
            ->assertSee('Belum ada kesan &amp; pesan', false);
    }

    public function test_visitor_can_submit_kesan_dan_pesan(): void
    {
        $response = $this->post('/kesan-pesan', [
            'name' => 'Budi',
            'message' => 'Portofolionya keren dan rapi!',
        ]);

        $response->assertRedirect(route('guestbook.index'));
        $this->assertDatabaseHas('guest_messages', [
            'name' => 'Budi',
            'message' => 'Portofolionya keren dan rapi!',
        ]);
    }

    public function test_kesan_dan_pesan_requires_name_and_message(): void
    {
        $this->post('/kesan-pesan', [])
            ->assertSessionHasErrors(['name', 'message']);
    }

    public function test_submitted_kesan_dan_pesan_is_visible_publicly(): void
    {
        GuestMessage::create([
            'name' => 'Siti',
            'message' => 'Semangat terus berkarya!',
        ]);

        $this->get('/kesan-pesan')
            ->assertOk()
            ->assertSee('Siti')
            ->assertSee('Semangat terus berkarya!');
    }

    public function test_guest_cannot_access_admin_guest_messages(): void
    {
        $this->get('/admin/guest-messages')->assertRedirect('/login');
    }

    public function test_admin_can_view_and_delete_kesan_dan_pesan(): void
    {
        $user = $this->admin();

        $entry = GuestMessage::create([
            'name' => 'Andi',
            'message' => 'Mantap, lanjutkan!',
        ]);

        $this->actingAs($user)->get('/admin/guest-messages')
            ->assertOk()
            ->assertSee('Andi')
            ->assertSee('Mantap, lanjutkan!');

        $this->actingAs($user)->delete("/admin/guest-messages/{$entry->id}")
            ->assertRedirect(route('admin.guest-messages.index'));

        $this->assertDatabaseMissing('guest_messages', ['id' => $entry->id]);
    }
}
