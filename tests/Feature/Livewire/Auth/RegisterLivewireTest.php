<?php

namespace Tests\Feature\Livewire\Auth;

use App\Http\Api\UserApi;
use App\Livewire\Auth\RegisterLivewire;
use Livewire\Livewire;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterLivewireTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Mockery::close();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function submit_berhasil_dengan_data_valid()
    {
        // Mock UserApi response success
        $userApiMock = Mockery::mock('alias:'.UserApi::class);
        $userApiMock
            ->shouldReceive('postRegister')
            ->once()
            ->with('username_valid', 'password_valid')
            ->andReturn((object) [
                'status' => 'success',
                'message' => 'Registrasi berhasil',
            ]);

        Livewire::test(RegisterLivewire::class)
            ->set('username', 'username_valid')
            ->set('password', 'password_valid')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirectToRoute('auth.login')
            ->assertSessionHas('success', 'Berhasil mendaftar, silakan masuk!');
    }

    #[Test]
    public function submit_gagal_jika_username_kosong()
    {
        Livewire::test(RegisterLivewire::class)
            ->set('username', '')
            ->set('password', 'password123')
            ->call('submit')
            ->assertHasErrors(['username' => 'required'])
            ->assertSee('Username harus diisi.')
            ->assertNoRedirect();
    }

    #[Test]
    public function submit_gagal_jika_password_kosong()
    {
        Livewire::test(RegisterLivewire::class)
            ->set('username', 'username_valid')
            ->set('password', '')
            ->call('submit')
            ->assertHasErrors(['password' => 'required'])
            ->assertSee('Password harus diisi.')
            ->assertNoRedirect();
    }

    #[Test]
    public function submit_gagal_jika_username_terlalu_panjang()
    {
        Livewire::test(RegisterLivewire::class)
            ->set('username', str_repeat('a', 51))  // 51 karakter
            ->set('password', 'password123')
            ->call('submit')
            ->assertHasErrors(['username' => 'max'])
            ->assertSee('Username tidak boleh lebih dari 50 karakter.')
            ->assertNoRedirect();
    }

    #[Test]
    public function submit_berhasil_dengan_username_tepat_50_karakter()
    {
        // Mock UserApi response success
        $userApiMock = Mockery::mock('alias:'.UserApi::class);
        $userApiMock
            ->shouldReceive('postRegister')
            ->once()
            ->with(str_repeat('a', 50), 'password123')
            ->andReturn((object) [
                'status' => 'success',
                'message' => 'Registrasi berhasil',
            ]);

        Livewire::test(RegisterLivewire::class)
            ->set('username', str_repeat('a', 50))  // tepat 50 karakter
            ->set('password', 'password123')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirectToRoute('auth.login');
    }

    #[Test]
    public function submit_gagal_jika_api_mengembalikan_status_error()
    {
        // Mock UserApi response error
        $userApiMock = Mockery::mock('alias:'.UserApi::class);
        $userApiMock
            ->shouldReceive('postRegister')
            ->once()
            ->with('username_gagal', 'password_gagal')
            ->andReturn((object) [
                'status' => 'error',
                'message' => 'Username sudah digunakan',
            ]);

        Livewire::test(RegisterLivewire::class)
            ->set('username', 'username_gagal')
            ->set('password', 'password_gagal')
            ->call('submit')
            ->assertHasErrors(['username'])
            ->assertSee('Username sudah digunakan')
            ->assertNoRedirect();
    }

    #[Test]
    public function submit_gagal_jika_api_mengembalikan_status_bukan_success()
    {
        // Mock UserApi response dengan status bukan success
        $userApiMock = Mockery::mock('alias:'.UserApi::class);
        $userApiMock
            ->shouldReceive('postRegister')
            ->once()
            ->with('username_failed', 'password_failed')
            ->andReturn((object) [
                'status' => 'failed',  // Bukan 'success'
                'message' => 'Registrasi gagal',
            ]);

        Livewire::test(RegisterLivewire::class)
            ->set('username', 'username_failed')
            ->set('password', 'password_failed')
            ->call('submit')
            ->assertHasErrors(['username'])
            ->assertSee('Registrasi gagal')
            ->assertNoRedirect();
    }

    #[Test]
    public function submit_gagal_jika_api_mengembalikan_null()
    {
        // Mock UserApi response null
        $userApiMock = Mockery::mock('alias:'.UserApi::class);
        $userApiMock
            ->shouldReceive('postRegister')
            ->once()
            ->with('username_null', 'password_null')
            ->andReturn(null);

        Livewire::test(RegisterLivewire::class)
            ->set('username', 'username_null')
            ->set('password', 'password_null')
            ->call('submit')
            ->assertHasErrors(['username'])
            ->assertSee('Gagal melakukan pendaftaran, silakan coba lagi!')
            ->assertNoRedirect();
    }

    #[Test]
    public function submit_gagal_jika_api_mengembalikan_tanpa_message()
    {
        // Mock UserApi response tanpa message property
        $userApiMock = Mockery::mock('alias:'.UserApi::class);
        $userApiMock
            ->shouldReceive('postRegister')
            ->once()
            ->with('username_no_message', 'password_no_message')
            ->andReturn((object) [
                'status' => 'error',
                // Tidak ada message property
            ]);

        Livewire::test(RegisterLivewire::class)
            ->set('username', 'username_no_message')
            ->set('password', 'password_no_message')
            ->call('submit')
            ->assertHasErrors(['username'])
            ->assertSee('Gagal melakukan pendaftaran, silakan coba lagi!')
            ->assertNoRedirect();
    }

    #[Test]
    public function properti_terinisialisasi_dengan_null()
    {
        Livewire::test(RegisterLivewire::class)
            ->assertSet('username', null)
            ->assertSet('password', null);
    }

    #[Test]
    public function properti_dapat_di_set()
    {
        Livewire::test(RegisterLivewire::class)
            ->set('username', 'testuser')
            ->set('password', 'testpass')
            ->assertSet('username', 'testuser')
            ->assertSet('password', 'testpass');
    }

    #[Test]
    public function validasi_string_type_berhasil()
    {
        // Mock UserApi response success
        $userApiMock = Mockery::mock('alias:'.UserApi::class);
        $userApiMock
            ->shouldReceive('postRegister')
            ->once()
            ->with('12345', '67890')  // Angka sebagai string
            ->andReturn((object) [
                'status' => 'success',
                'message' => 'Registrasi berhasil',
            ]);

        Livewire::test(RegisterLivewire::class)
            ->set('username', '12345')  // Livewire akan konversi ke string
            ->set('password', '67890')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirectToRoute('auth.login');
    }
}
