<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\EventBooking;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile; 
use Illuminate\Support\Facades\Storage; 
use Tests\TestCase;


class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_users()
    {
        User::factory()->count(3)->create();

        $response = $this->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    public function test_it_can_list_users_when_users_exist()
    {
        User::factory()->count(3)->create();

        $response = $this->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewHas('users');
        $response->assertSee('User Management'); // Memastikan informasi yang sesuai muncul
    }

    public function test_it_shows_empty_user_list_when_no_users_exist()
    {
        $response = $this->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewHas('users');
        $response->assertSee('No users found'); // Mengasumsikan Anda menampilkan pesan jika tidak ada pengguna
    }

    public function test_it_can_store_a_user()
    {
        $email = fake()->unique()->safeEmail(); // Simpan email yang dibuat
    
        $userData = [
            'name' => 'John Doe',
            'email' => $email,  // Gunakan email yang disimpan
            'password' => 'password123',
            'role' => 'Student',
        ];
    
        $response = $this->post(route('users.store'), $userData);
    
        // Gunakan variabel email yang disimpan dalam assertion
        $this->assertDatabaseHas('users', ['email' => $email]);
        $response->assertRedirect(route('users.index'));
    }
    

    public function test_it_can_delete_a_user()
    {
        $user = User::factory()->create();

        $response = $this->delete(route('users.destroy', $user->id));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $response->assertJson(['success' => true]);
    }

    public function test_it_cannot_delete_user_if_not_found()
    {
        // Coba menghapus pengguna yang tidak ada di database
        $response = $this->delete(route('users.destroy', 9999)); // ID yang tidak ada

        // Memastikan respons 404 dan pesan yang benar
        $response->assertStatus(404);
        $response->assertJson(['success' => false, 'message' => 'User not found!']);
    }

    public function test_it_can_view_all_users()
    {
        // Membuat beberapa pengguna
        $user = User::factory()->create();
        
        // Melakukan permintaan GET ke route 'users.index' atau 'user-controller/index'
        $response = $this->get(route('users.index')); // Ganti dengan route yang sesuai

        // Memeriksa apakah halaman berhasil dimuat dan data pengguna ada
        $response->assertStatus(200);
        $response->assertViewHas('users'); // Memastikan data users ada di dalam view
        $response->assertSee($user->name); // Memastikan nama pengguna ada di halaman
    }

    public function test_it_can_show_user_profile_edit_page()
    {
        // Membuat pengguna dan login
        $user = User::factory()->create();
        $this->actingAs($user);

        // Melakukan permintaan GET ke route 'edit-profile'
        $response = $this->get(route('edit-profile'));

        // Memastikan halaman edit profile dapat diakses
        $response->assertStatus(200);
        $response->assertViewHas('user', $user); // Memastikan view memiliki data user
    }

    public function test_it_can_update_user_profile()
    {
        // Membuat pengguna dan login
        $user = User::factory()->create();
        $this->actingAs($user);

        // Data untuk update profile
        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'profile_image' => null, // Misalnya tidak mengunggah gambar
        ];

        // Melakukan permintaan POST untuk memperbarui profile
        $response = $this->post(route('update-profile'), $data);

        // Memastikan pengguna diperbarui
        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated@example.com', $user->email);

        // Memastikan kembali ke halaman dengan pesan sukses
        $response->assertRedirect(route('edit-profile'));
        $response->assertSessionHas('success', 'Profile updated successfully!');
    }

    public function test_it_cannot_update_profile_when_user_is_not_authenticated()
    {
        $response = $this->post(route('update-profile'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);

        $response->assertSessionHas('error', 'User not found.');
    }

    public function test_it_cannot_update_profile_with_invalid_data()
    {
        // Membuat pengguna dan login
        $user = User::factory()->create();
        $this->actingAs($user);
    
        // Data untuk update profile dengan nama kosong dan email tidak valid
        $data = [
            'name' => '', // Nama kosong
            'email' => 'invalid-email', // Email tidak valid
            'profile_image' => null, // Tidak mengunggah gambar
        ];
    
        // Melakukan permintaan POST untuk memperbarui profile
        $response = $this->post(route('update-profile'), $data);
    
        // Memastikan validasi gagal dan kembali ke halaman edit
        $response->assertStatus(302); // Redirect karena gagal validasi
        $response->assertSessionHasErrors(['name', 'email']);
    }
    

    /** @test */
    public function it_displays_users_page_with_users_data()
    {
        // Arrange: Buat data user dummy di database
        User::factory()->count(3)->create();

        // Act: Akses halaman index
        $response = $this->get('/users');

        // Assert: Pastikan response sukses (200) dan view yang ditampilkan benar
        $response->assertStatus(200);
        $response->assertViewIs('users');
        $response->assertViewHas('users', function ($users) {
            return $users->count() === 3; // Pastikan ada 3 user dalam view
        });
    }
    
    public function test_it_shows_error_when_user_not_authenticated()
    {
        $response = $this->post(route('update-profile'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);
    
        $response->assertSessionHas('error', 'User not found.');
    }

}