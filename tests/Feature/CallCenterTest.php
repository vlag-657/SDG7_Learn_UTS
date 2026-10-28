<?php

namespace Tests\Feature;

use App\Models\CallCenter;
use App\Models\User;
use Tests\TestCase;

class CallCenterTest extends TestCase
{
    protected User $user;

    protected User $otherUser;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::where('email', 'deco@gmail.com')->first()
            ?? User::factory()->create(['role' => 'user']);

        $this->otherUser = User::where('email', 'cristian@gmail.com')->first()
            ?? User::factory()->create(['role' => 'user']);

        $this->adminUser = User::where('role', 'admin')->first()
            ?? User::factory()->create(['role' => 'admin']);
    }

    public function test_guest_cannot_access_call_center(): void
    {
        $response = $this->get('/call-center');
        $response->assertRedirect('/login');
    }

    public function test_user_can_view_call_center_index(): void
    {
        $response = $this->actingAs($this->user)->get('/call-center');
        $response->assertStatus(200);
        $response->assertSee('Call Center');
    }

    public function test_user_can_view_create_form(): void
    {
        $response = $this->actingAs($this->user)->get('/call-center/buat');
        $response->assertStatus(200);
        $response->assertSee('Formulir Pesan Pengaduan');
    }

    public function test_user_validation_fails_when_input_is_invalid(): void
    {
        $response = $this->actingAs($this->user)->post('/call-center', [
            'kategori' => '',
            'prioritas' => '',
            'subjek' => 'abc', // min 5
            'pesan' => 'pendek', // min 10
        ]);

        $response->assertSessionHasErrors(['kategori', 'prioritas', 'subjek', 'pesan']);
    }

    public function test_user_can_create_call_center_ticket(): void
    {
        $response = $this->actingAs($this->user)->post('/call-center', [
            'kategori' => 'pertanyaan',
            'prioritas' => 'sedang',
            'nomor_telepon' => '08123456789',
            'subjek' => 'Pertanyaan Cara Kerja Turbin Angin',
            'pesan' => 'Saya ingin bertanya mengenai perbedaan turbin angin horizontal dan vertikal.',
        ]);

        $this->assertDatabaseHas('call_center', [
            'user_id' => $this->user->id,
            'subjek' => 'Pertanyaan Cara Kerja Turbin Angin',
            'status' => 'menunggu',
        ]);

        $ticket = CallCenter::where('subjek', 'Pertanyaan Cara Kerja Turbin Angin')->latest('id')->first();
        $response->assertRedirect(route('call-center.show', $ticket->id));
        $response->assertSessionHas('success');
    }

    public function test_user_can_view_own_ticket(): void
    {
        $ticket = CallCenter::create([
            'user_id' => $this->user->id,
            'nama' => $this->user->nama,
            'email' => $this->user->email,
            'kategori' => 'pertanyaan',
            'prioritas' => 'sedang',
            'subjek' => 'Tes Lihat Tiket Sendiri',
            'pesan' => 'Ini pesan tes untuk melihat tiket pengaduan sendiri.',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->user)->get(route('call-center.show', $ticket->id));
        $response->assertStatus(200);
        $response->assertSee('Tes Lihat Tiket Sendiri');
    }

    public function test_user_can_edit_own_ticket_when_status_is_menunggu(): void
    {
        $ticket = CallCenter::create([
            'user_id' => $this->user->id,
            'nama' => $this->user->nama,
            'email' => $this->user->email,
            'kategori' => 'pertanyaan',
            'prioritas' => 'sedang',
            'subjek' => 'Subjek Sebelum Diedit',
            'pesan' => 'Pesan deskripsi sebelum dilakukan penyuntingan oleh user.',
            'status' => 'menunggu',
        ]);

        $editResponse = $this->actingAs($this->user)->get(route('call-center.edit', $ticket->id));
        $editResponse->assertStatus(200);

        $updateResponse = $this->actingAs($this->user)->put(route('call-center.update', $ticket->id), [
            'kategori' => 'masalah_teknis',
            'prioritas' => 'tinggi',
            'subjek' => 'Subjek Sudah Diperbarui',
            'pesan' => 'Pesan deskripsi setelah diperbarui oleh pengguna.',
        ]);

        $updateResponse->assertRedirect(route('call-center.show', $ticket->id));
        $this->assertDatabaseHas('call_center', [
            'id' => $ticket->id,
            'subjek' => 'Subjek Sudah Diperbarui',
            'kategori' => 'masalah_teknis',
        ]);
    }

    public function test_user_cannot_edit_ticket_when_status_is_diproses_or_selesai(): void
    {
        $ticket = CallCenter::create([
            'user_id' => $this->user->id,
            'nama' => $this->user->nama,
            'email' => $this->user->email,
            'kategori' => 'pertanyaan',
            'prioritas' => 'sedang',
            'subjek' => 'Tiket Sedang Diproses',
            'pesan' => 'Deskripsi tiket yang sudah diproses oleh admin.',
            'status' => 'diproses',
        ]);

        $response = $this->actingAs($this->user)->get(route('call-center.edit', $ticket->id));
        $response->assertRedirect(route('call-center.show', $ticket->id));
        $response->assertSessionHas('error');
    }

    public function test_user_can_delete_own_ticket(): void
    {
        $ticket = CallCenter::create([
            'user_id' => $this->user->id,
            'nama' => $this->user->nama,
            'email' => $this->user->email,
            'kategori' => 'saran',
            'prioritas' => 'rendah',
            'subjek' => 'Tiket Akan Dihapus',
            'pesan' => 'Ini pesan yang akan dibatalkan dan dihapus oleh pengguna.',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->user)->delete(route('call-center.destroy', $ticket->id));
        $response->assertRedirect(route('call-center.index'));
        $this->assertDatabaseMissing('call_center', ['id' => $ticket->id]);
    }

    public function test_admin_can_view_admin_call_center_panel(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.call-center.index'));
        $response->assertStatus(200);
        $response->assertSee('Kelola Call Center');
    }

    public function test_admin_can_respond_and_update_status(): void
    {
        $ticket = CallCenter::create([
            'user_id' => $this->user->id,
            'nama' => $this->user->nama,
            'email' => $this->user->email,
            'kategori' => 'pertanyaan',
            'prioritas' => 'tinggi',
            'subjek' => 'Pertanyaan Untuk Ditanggapi Admin',
            'pesan' => 'Mohon penjelasan untuk kendala skor yang tidak tersimpan.',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->adminUser)->put(route('admin.call-center.update', $ticket->id), [
            'status' => 'selesai',
            'kecepatan_penanganan' => 'sangat_cepat',
            'tanggapan' => 'Kendala telah kami perbaiki di sisi server. Silakan dicoba kembali.',
        ]);

        $response->assertRedirect(route('admin.call-center.show', $ticket->id));
        $this->assertDatabaseHas('call_center', [
            'id' => $ticket->id,
            'status' => 'selesai',
            'kecepatan_penanganan' => 'sangat_cepat',
            'tanggapan' => 'Kendala telah kami perbaiki di sisi server. Silakan dicoba kembali.',
        ]);
    }

    public function test_admin_validation_fails_with_invalid_kecepatan_penanganan(): void
    {
        $ticket = CallCenter::create([
            'user_id' => $this->user->id,
            'nama' => $this->user->nama,
            'email' => $this->user->email,
            'kategori' => 'pertanyaan',
            'prioritas' => 'tinggi',
            'subjek' => 'Uji Validasi Penanganan',
            'pesan' => 'Pesan pengujian validasi kecepatan penanganan admin.',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->adminUser)->put(route('admin.call-center.update', $ticket->id), [
            'status' => 'diproses',
            'kecepatan_penanganan' => 'tidak_valid_xyz',
            'tanggapan' => 'Sedang kami cek.',
        ]);

        $response->assertSessionHasErrors(['kecepatan_penanganan']);
    }
}
