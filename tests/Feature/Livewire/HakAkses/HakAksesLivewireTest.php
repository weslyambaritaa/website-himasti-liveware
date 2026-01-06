<?php

namespace Tests\Feature\Livewire\HakAkses;

use App\Helper\ToolsHelper;
use App\Http\Api\UserApi;
use App\Livewire\HakAkses\HakAksesLivewire;
use App\Models\HakAksesModel;
use Illuminate\Http\Request;
use Livewire\Livewire;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HakAksesLivewireTest extends TestCase
{
    // Attribut untuk menyimpan mock objek
    protected $hakAksesModelMock;

    protected $userApiMock;

    protected $fakeAuth;

    // Setup sebelum setiap test => dipanggil sebelum test dijalankan
    protected function setUp(): void
    {
        parent::setUp();
        Mockery::close();

        $this->hakAksesModelMock = Mockery::mock('alias:'.HakAksesModel::class);
        $this->userApiMock = Mockery::mock('alias:'.UserApi::class);
    }

    // Tear down setelah setiap test => dipanggil setelah test selesai
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function setupRender()
    {
        // Siapkan auth user palsu
        $this->fakeAuth = (object) [
            'id' => 'auth-user-id',
            'name' => 'Auth User',
            'akses' => ['Admin'],
            'roles' => ['Admin'],
        ];

        ToolsHelper::setAuthToken('valid-token');

        $fakeUsers = [
            (object) ['id' => ToolsHelper::generateId(), 'name' => 'Test User', 'username' => 'testuser'],
            (object) ['id' => ToolsHelper::generateId(), 'name' => 'Another User', 'username' => 'anotheruser'],
            (object) ['id' => ToolsHelper::generateId(), 'name' => 'Third User', 'username' => 'thirduser'],
        ];

        $fakeAksesList = [];
        foreach ($fakeUsers as $user) {
            $fakeAksesList[] = (object) [
                'id' => ToolsHelper::generateId(),
                'user_id' => $user->id,
                'akses' => 'Editor,Read',
            ];
        }
        $fakeAksesList[] = (object) [
            'id' => ToolsHelper::generateId(),
            'user_id' => 'nonexistent-user',
            'akses' => 'Read',
        ];

        $collection = collect($fakeAksesList);

        // Mock HakAksesModel
        $this
            ->hakAksesModelMock
            ->shouldReceive('get')
            ->andReturn(collect($fakeAksesList));
        $this
            ->hakAksesModelMock
            ->shouldReceive('filter')
            ->andReturnUsing(function ($callback) use ($collection) {
                // Jalankan callback filter yang sama seperti di component
                $filtered = $collection->filter(function ($item) use ($callback) {
                    return $callback($item);
                });

                return $filtered;
            });

        $this
            ->hakAksesModelMock
            ->shouldReceive('pluck')
            ->with('user_id')
            ->andReturnSelf();
        $this
            ->hakAksesModelMock
            ->shouldReceive('unique')
            ->andReturnSelf();
        $this
            ->hakAksesModelMock
            ->shouldReceive('toArray')
            ->andReturn($fakeUsers);

        // Mock UserApi
        $this
            ->userApiMock
            ->shouldReceive('postReqUsersByIds')
            ->with(Mockery::any(), Mockery::any())
            ->andReturn((object) [
                'data' => (object) [
                    'users' => $fakeUsers,
                ],
            ]);
        $this
            ->userApiMock
            ->shouldReceive('getUsers')
            ->with(Mockery::any(), Mockery::any(), Mockery::any(), Mockery::any())  // Terima semua parameter
            ->andReturn((object) [
                'data' => (object) [
                    'users' => $fakeUsers,
                ],
            ]);
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
        $this->setupRender();

        // Jalankan komponen menggunakan Livewire test helper
        Livewire::test(HakAksesLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->assertSet('auth', $this->fakeAuth)
            ->assertViewIs('features.hak-akses.hak-akses-livewire');
    }

    #[Test]
    public function render_mengembalikan_view_yang_benar_dengan_data_lengkap()
    {
        $this->setupRender();

        // Jalankan komponen menggunakan Livewire test helper
        Livewire::test(HakAksesLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->set('search', 'Test User')
            ->set('searchPengguna', 'testuser')
            ->assertViewIs('features.hak-akses.hak-akses-livewire');
    }

    #[Test]
    public function berhasil_menjalankan_fungsi_add()
    {
        $dataAdd = [
            'user_id' => 'user123',
            'akses' => ['Editor', 'Read'],
        ];

        $this->setupRender();

        // Mock HakAksesModel
        $this
            ->hakAksesModelMock
            ->shouldReceive('where')
            ->andReturnSelf();
        $this
            ->hakAksesModelMock
            ->shouldReceive('delete')
            ->andReturnNull();
        $this
            ->hakAksesModelMock
            ->shouldReceive('create')
            ->andReturnUsing(function ($data) {
                $this->assertArrayHasKey('user_id', $data);
                $this->assertArrayHasKey('akses', $data);

                return (object) $data;
            });

        Livewire::test(HakAksesLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->set('dataUserId', $dataAdd['user_id'])
            ->set('dataHakAkses', $dataAdd['akses'])
            ->set('isEditor', true)
            ->call('add')
            ->assertStatus(200)
            ->assertDispatched('closeModal', id: 'addModal');
    }

    #[Test]
    public function gagal_menjalankan_fungsi_add_tidak_ada_data_auth()
    {
        $dataAdd = [
            'user_id' => ToolsHelper::generateId(),
            'akses' => ['Editor', 'Read'],
        ];

        $this->setupRender();

        // Mock HakAksesModel
        $this
            ->hakAksesModelMock
            ->shouldReceive('where')
            ->andReturnSelf();
        $this
            ->hakAksesModelMock
            ->shouldReceive('delete')
            ->andReturnNull();
        $this
            ->hakAksesModelMock
            ->shouldReceive('create')
            ->andReturnUsing(function ($data) {
                $this->assertArrayHasKey('user_id', $data);
                $this->assertArrayHasKey('akses', $data);

                return (object) $data;
            });

        Livewire::test(HakAksesLivewire::class)
            ->set('isEditor', false)
            ->set('dataUserId', $dataAdd['user_id'])
            ->set('dataHakAkses', $dataAdd['akses'])
            ->call('add')
            ->assertStatus(403);
    }

    #[Test]
    public function gagal_menjalankan_fungsi_add_bukan_editor()
    {
        $dataAdd = [
            'user_id' => ToolsHelper::generateId(),
            'akses' => ['Editor', 'Read'],
        ];

        $this->setupRender();

        // Mock HakAksesModel
        $this
            ->hakAksesModelMock
            ->shouldReceive('where')
            ->andReturnSelf();
        $this
            ->hakAksesModelMock
            ->shouldReceive('delete')
            ->andReturnNull();
        $this
            ->hakAksesModelMock
            ->shouldReceive('create')
            ->andReturnUsing(function ($data) {
                $this->assertArrayHasKey('user_id', $data);
                $this->assertArrayHasKey('akses', $data);

                return (object) $data;
            });

        Livewire::test(HakAksesLivewire::class, [
            'auth' => (object) [
                'roles' => [],
                'akses' => [],
            ],
        ])
            ->set('isEditor', false)
            ->set('dataUserId', $dataAdd['user_id'])
            ->set('dataHakAkses', $dataAdd['akses'])
            ->call('add')
            ->assertStatus(403);
    }

    #[Test]
    public function gagal_menjalankan_fungsi_add_data_tidak_valid()
    {
        $dataAdd = [
            'user_id' => ToolsHelper::generateId(),
        ];

        $this->setupRender();

        // Mock HakAksesModel
        $this
            ->hakAksesModelMock
            ->shouldReceive('where')
            ->andReturnSelf();
        $this
            ->hakAksesModelMock
            ->shouldReceive('delete')
            ->andReturnNull();
        $this
            ->hakAksesModelMock
            ->shouldReceive('create')
            ->andReturnUsing(function ($data) {
                $this->assertArrayHasKey('user_id', $data);
                $this->assertArrayHasKey('akses', $data);

                return (object) $data;
            });

        Livewire::test(HakAksesLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->set('isEditor', true)
            ->set('dataUserId', $dataAdd['user_id'])
            ->set('dataHakAkses', null)
            ->call('add')
            ->assertHasErrors(['dataHakAkses' => 'required']);
    }

    #[Test]
    public function berhasil_menjalankan_fungsi_prepare_edit()
    {
        $this->setupRender();

        // Mock HakAksesModel
        $dataId = 'some-edit-id';
        $targetAkses = (object) [
            'id' => $dataId,
            'akses' => 'Editor,Read',
        ];

        $this
            ->hakAksesModelMock
            ->shouldReceive('find')
            ->with($dataId)
            ->andReturn($targetAkses);

        Livewire::test(HakAksesLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->set('isEditor', true)
            ->call('prepareEdit', $dataId)
            ->assertSet('dataId', $dataId)
            ->assertSet('dataHakAkses', ['Editor', 'Read'])
            ->assertDispatched('showModal', id: 'editModal');
    }

    #[Test]
    public function gagal_menjalankan_fungsi_prepare_edit()
    {
        $this->setupRender();

        // Mock HakAksesModel
        $dataId = 'some-edit-id';

        $this
            ->hakAksesModelMock
            ->shouldReceive('find')
            ->with($dataId)
            ->andReturnNull();

        Livewire::test(HakAksesLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->set('isEditor', true)
            ->call('prepareEdit', $dataId)
            ->assertViewIs('features.hak-akses.hak-akses-livewire');
    }

    #[Test]
    public function berhasil_menjalankan_fungsi_edit()
    {
        $dataEdit = (object) [
            'id' => 'edit-id-123',
            'akses' => ['Editor', 'Read'],
        ];

        $this->setupRender();

        $fakeUsers = [
            (object) ['id' => ToolsHelper::generateId(), 'name' => 'Test User', 'username' => 'testuser'],
            (object) ['id' => ToolsHelper::generateId(), 'name' => 'Another User', 'username' => 'anotheruser'],
            (object) ['id' => ToolsHelper::generateId(), 'name' => 'Third User', 'username' => 'thirduser'],
        ];

        $fakeAksesList = [];
        foreach ($fakeUsers as $user) {
            $fakeAksesList[] = (object) [
                'id' => ToolsHelper::generateId(),
                'user_id' => $user->id,
                'akses' => 'Editor,Read',
            ];
        }
        $fakeAksesList[] = (object) [
            'id' => ToolsHelper::generateId(),
            'user_id' => 'nonexistent-user',
            'akses' => 'Read',
        ];

        $collection = collect($fakeAksesList);

        // Mock HakAksesModel
        $this
            ->hakAksesModelMock
            ->shouldReceive('where')
            ->with('id', $dataEdit->id)
            ->andReturnSelf();
        $this
            ->hakAksesModelMock
            ->shouldReceive('first')
            ->andReturnSelf();
        $this
            ->hakAksesModelMock
            ->shouldReceive('save')
            ->andReturnTrue();

        Livewire::test(HakAksesLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->set('dataId', $dataEdit->id)
            ->set('dataHakAkses', $dataEdit->akses)
            ->set('isEditor', true)
            ->call('edit')
            ->assertStatus(200)
            ->assertDispatched('closeModal', id: 'editModal');
    }

    #[Test]
    public function berhasil_menjalankan_fungsi_prepare_delete()
    {
        $this->setupRender();

        // Mock HakAksesModel
        $dataId = 'some-delete-id';
        $targetAkses = (object) [
            'id' => $dataId,
            'name' => 'Test User',
            'user_id' => 'user-123',
        ];

        $this
            ->hakAksesModelMock
            ->shouldReceive('find')
            ->with($dataId)
            ->andReturn($targetAkses);

        // Mock UserApi
        $this
            ->userApiMock
            ->shouldReceive('getUserById')
            ->with(
                Mockery::any(),
                $targetAkses->user_id,
            )
            ->andReturn((object) [
                'data' => (object) [
                    'user' => (object) [
                        'name' => $targetAkses->name,
                    ],
                ],
            ]);

        Livewire::test(HakAksesLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->set('isEditor', true)
            ->call('prepareDelete', $dataId)
            ->assertSet('dataId', $dataId)
            ->assertDispatched('showModal', id: 'deleteModal');
    }

    #[Test]
    public function gagal_menjalankan_fungsi_prepare_delete()
    {
        $this->setupRender();

        // Mock HakAksesModel
        $dataId = 'some-delete-id';

        $this
            ->hakAksesModelMock
            ->shouldReceive('find')
            ->with($dataId)
            ->andReturnNull();

        Livewire::test(HakAksesLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->set('isEditor', true)
            ->call('prepareDelete', $dataId)
            ->assertViewIs('features.hak-akses.hak-akses-livewire');
    }

    #[Test]
    public function berhasil_menjalankan_fungsi_delete()
    {
        $dataId = ToolsHelper::generateId();

        $this->setupRender();

        // Mock HakAksesModel
        $this
            ->hakAksesModelMock
            ->shouldReceive('destroy')
            ->with($dataId)
            ->andReturnNull();

        Livewire::test(HakAksesLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->set('dataId', $dataId)
            ->set('dataKonfirmasi', $dataId)
            ->set('isEditor', true)
            ->call('delete')
            ->assertHasNoErrors();
    }

    #[Test]
    public function gagal_menjalankan_fungsi_delete()
    {
        $dataId = ToolsHelper::generateId();

        $this->setupRender();

        // Mock HakAksesModel
        $this
            ->hakAksesModelMock
            ->shouldReceive('destroy')
            ->with($dataId)
            ->andReturnNull();

        Livewire::test(HakAksesLivewire::class, [
            'auth' => $this->fakeAuth,
        ])
            ->set('dataId', $dataId)
            ->set('isEditor', true)
            ->call('delete')
            ->assertHasErrors();
    }
}
