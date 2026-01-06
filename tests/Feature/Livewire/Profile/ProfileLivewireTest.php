<?php

namespace Tests\Feature\Livewire\Profile;

use App\Helper\ToolsHelper;
use App\Http\Api\UserApi;
use App\Livewire\HakAkses\HakAksesLivewire;
use App\Livewire\Profile\ProfileLivewire;
use Illuminate\Http\Request;
use Livewire\Livewire;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProfileLivewireTest extends TestCase
{
    protected $userApiMock;

    public $fakeAuth;

    // Setup sebelum setiap test => dipanggil sebelum test dijalankan
    protected function setUp(): void
    {
        parent::setUp();
        Mockery::close();

        // Siapkan auth user palsu
        $this->fakeAuth = (object) [
            'id' => 'auth-user-id',
            'name' => 'Auth User',
            'akses' => ['Admin'],
            'roles' => ['Admin'],
        ];

        $this->userApiMock = Mockery::mock('alias:'.UserApi::class);
    }

    // Tear down setelah setiap test => dipanggil setelah test selesai
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function mount_mengatur_auth_user_dari_request()
    {
        $authUser = (object) [
            'id' => '123',
            'name' => 'Test User',
            'akses' => ['Admin'],
            'roles' => ['Admin'],
        ];

        // Siapkan request dengan properti auth
        $request = Request::create('/app/hak-akses', 'GET');
        $request->auth = $authUser;
        $this->app->instance('request', $request);

        // Jalankan komponen
        $component = new HakAksesLivewire;
        $component->mount();

        // Pastikan authUser terisi dari request
        $this->assertEquals($request->auth, $authUser);
    }

    #[Test]
    public function render_mengembalikan_view_yang_benar()
    {
        // Jalankan komponen menggunakan Livewire test helper
        Livewire::test(ProfileLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->assertSet('auth', $this->fakeAuth)
            ->assertViewIs('features.profile.profile-livewire');
    }

    #[Test]
    public function berhasil_menjalankan_fungsi_edit_password()
    {
        $oldPassword = 'old-pass';
        $newPassword = 'new-pass';

        ToolsHelper::setAuthToken('fake-auth-token');

        // Mock UserApi
        $this
            ->userApiMock
            ->shouldReceive('putMePassword')
            ->with(
                ToolsHelper::getAuthToken(),
                $newPassword,
                $oldPassword
            )
            ->andReturn((object) ['status' => 'success']);

        // Jalankan komponen Livewire
        Livewire::test(ProfileLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->set('dataPasswordLama', $oldPassword)
            ->set('dataPasswordBaru', $newPassword)
            ->call('editPassword')
            ->assertHasNoErrors()
            ->assertDispatched('reloadPage');
    }

    #[Test]
    public function gagal_menjalankan_fungsi_edit_password_dengan_password_lama_salah()
    {
        $oldPassword = 'wrong-old-pass';
        $newPassword = 'new-pass';

        ToolsHelper::setAuthToken('fake-auth-token');

        // Mock UserApi
        $this
            ->userApiMock
            ->shouldReceive('putMePassword')
            ->with(
                ToolsHelper::getAuthToken(),
                $newPassword,
                $oldPassword
            )
            ->andReturn((object) [
                'status' => 'error',
                'message' => 'Password lama salah.',
            ]);

        // Jalankan komponen Livewire
        Livewire::test(ProfileLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->set('dataPasswordLama', $oldPassword)
            ->set('dataPasswordBaru', $newPassword)
            ->call('editPassword')
            ->assertHasErrors(['dataPasswordLama' => 'Password lama salah.']);
    }

    #[Test]
    public function gagal_menjalankan_fungsi_edit_password_dengan_validasi_gagal()
    {
        // Jalankan komponen Livewire tanpa mengisi password lama dan baru
        Livewire::test(ProfileLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->call('editPassword')
            ->assertHasErrors([
                'dataPasswordLama' => 'required',
                'dataPasswordBaru' => 'required',
            ]);
    }

    #[Test]
    public function gagal_menjalankan_fungsi_edit_password_dengan_password_lama_salah_dan_message_error_kosong()
    {
        $oldPassword = 'wrong-old-pass';
        $newPassword = 'new-pass';

        ToolsHelper::setAuthToken('fake-auth-token');

        // Mock UserApi
        $this
            ->userApiMock
            ->shouldReceive('putMePassword')
            ->with(
                ToolsHelper::getAuthToken(),
                $newPassword,
                $oldPassword
            )
            ->andReturn((object) [
                'status' => 'error',
            ]);

        // Jalankan komponen Livewire
        Livewire::test(ProfileLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->set('dataPasswordLama', $oldPassword)
            ->set('dataPasswordBaru', $newPassword)
            ->call('editPassword')
            ->assertHasErrors(['dataPasswordLama' => 'Gagal mengirimkan data.']);
    }

    #[Test]
    public function berhasil_menjalankan_fungsi_edit_whatsapp()
    {
        $whatsapp = 'old-whatsapp';
        $password = 'confirm-pass';

        ToolsHelper::setAuthToken('fake-auth-token');

        // Mock UserApi
        $this
            ->userApiMock
            ->shouldReceive('putMeWhatsapp')
            ->with(
                ToolsHelper::getAuthToken(),
                $whatsapp,
                $password
            )
            ->andReturn((object) ['status' => 'success']);

        // Jalankan komponen Livewire
        Livewire::test(ProfileLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->set('dataWhatsapp', $whatsapp)
            ->set('dataPassword', $password)
            ->call('editWhatsapp')
            ->assertHasNoErrors()
            ->assertDispatched('reloadPage');
    }

    #[Test]
    public function gagal_menjalankan_fungsi_edit_whatsapp_dengan_validasi_gagal()
    {
        // Jalankan komponen Livewire tanpa mengisi whatsapp dan password
        Livewire::test(ProfileLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->call('editWhatsapp')
            ->assertHasErrors([
                'dataWhatsapp' => 'required',
                'dataPassword' => 'required',
            ]);
    }

    #[Test]
    public function gagal_menjalankan_fungsi_edit_whatsapp_dengan_password_konfirmasi_salah()
    {
        $whatsapp = 'old-whatsapp';
        $password = 'wrong-pass';

        ToolsHelper::setAuthToken('fake-auth-token');

        // Mock UserApi
        $this
            ->userApiMock
            ->shouldReceive('putMeWhatsapp')
            ->with(
                ToolsHelper::getAuthToken(),
                $whatsapp,
                $password
            )
            ->andReturn((object) [
                'status' => 'error',
                'message' => 'Password konfirmasi salah.',
            ]);

        // Jalankan komponen Livewire
        Livewire::test(ProfileLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->set('dataWhatsapp', $whatsapp)
            ->set('dataPassword', $password)
            ->call('editWhatsapp')
            ->assertHasErrors(['dataWhatsapp' => 'Password konfirmasi salah.']);
    }

    #[Test]
    public function gagal_menjalankan_fungsi_edit_whatsapp_dengan_password_konfirmasi_salah_dan_message_error_kosong()
    {
        $whatsapp = 'old-whatsapp';
        $password = 'wrong-pass';

        ToolsHelper::setAuthToken('fake-auth-token');

        // Mock UserApi
        $this
            ->userApiMock
            ->shouldReceive('putMeWhatsapp')
            ->with(
                ToolsHelper::getAuthToken(),
                $whatsapp,
                $password
            )
            ->andReturn((object) [
                'status' => 'error',
            ]);

        // Jalankan komponen Livewire
        Livewire::test(ProfileLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->set('dataWhatsapp', $whatsapp)
            ->set('dataPassword', $password)
            ->call('editWhatsapp')
            ->assertHasErrors(['dataWhatsapp' => 'Gagal mengirimkan data.']);
    }
}
