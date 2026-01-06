<?php

namespace App\Livewire\HakAkses;

use App\Helper\ConstHelper;
use App\Helper\ToolsHelper;
use App\Http\Api\UserApi;
use App\Models\HakAksesModel;
use Livewire\Component;

class HakAksesLivewire extends Component
{
    // Attributes
    public $auth;

    public $isEditor = false;

    public $optionRoles = [];

    public $search;

    public $searchPengguna;

    // Attributes untuk form tambah/edit/hapus
    public $dataId;

    public $dataUserId;

    public $dataHakAkses = [];

    public $dataKonfirmasi;

    public $infoDeleteMessage;

    // Fungsi untuk cek akses
    private function checkAkses()
    {
        if (! $this->isEditor) {
            abort(403, 'Unauthorized action.');
        }

        $this->resetErrorBag();
    }

    // Fungsi yang dijalankan saat komponen di-mount
    public function mount($auth = null)
    {
        $this->auth = $auth ?: request()->auth;
        $this->optionRoles = ConstHelper::getOptionRoles();
    }

    // Fungsi yang dijalankan saat render
    public function render()
    {
        // Ambil daftar akses

        $aksesList = HakAksesModel::get();

        $response = UserApi::postReqUsersByIds(
            ToolsHelper::getAuthToken(),
            $aksesList->pluck('user_id')->unique()->toArray(),
        );

        $usersList = [];
        if ($response && isset($response->data->users)) {
            $usersList = collect($response->data->users)->map(function ($user) {
                return (object) $user;
            })->all();
        }

        foreach ($aksesList as $akses) {
            $akses->user = collect($usersList)->firstWhere('id', $akses->user_id);
        }

        if ($this->search) {
            $aksesList = $aksesList->filter(function ($item) {
                $user = $item->user;
                if (! $user) {
                    return false;
                }

                return str_contains(strtolower($user->name), strtolower($this->search)) ||
                    str_contains(strtolower($user->username), strtolower($this->search));
            });
        }

        // Urutkan akses list berdasarkan nama pengguna
        $aksesList = $aksesList->sortBy(function ($item) {
            $user = $item->user;

            return $user ? strtolower($user->name) : '';
        })->values();

        // Peancarian data pengguna
        $searchPenggunaList = [];
        if ($this->searchPengguna) {
            $authToken = ToolsHelper::getAuthToken();
            $result = UserApi::getUsers($authToken, search: $this->searchPengguna, limit: 5, alias: '');
            if (isset($result->data->users)) {
                $searchPenggunaList = collect($result->data->users ?? [])->map(function ($user) {
                    return (object) $user;
                })->all();
            }
        }

        // cek akses
        $this->isEditor = $this->auth ? in_array('Admin', $this->auth->akses) || in_array('Admin', $this->auth->roles) : false;

        $data = [
            'aksesList' => $aksesList,
            'searchPenggunaList' => $searchPenggunaList,
        ];

        return view('features.hak-akses.hak-akses-livewire', $data);
    }

    // Fungsi yang menangani aksi tambah data
    public function add()
    {
        $this->checkAkses();

        // Validasi input
        $this->validate([
            'dataUserId' => 'required',
            'dataHakAkses' => 'required|array',
        ]);

        // Hapus akses lama
        HakAksesModel::where('user_id', $this->dataUserId)->delete();

        // Simpan hak akses baru
        HakAksesModel::create([
            'id' => ToolsHelper::generateId(),
            'user_id' => $this->dataUserId,
            'akses' => implode(',', $this->dataHakAkses),
        ]);

        // Reset input
        $this->reset([
            'dataUserId',
            'dataHakAkses',
        ]);

        $this->dispatch('closeModal', id: 'addModal');
    }

    // Fungsi sebelum edit data
    public function prepareEdit($id)
    {
        $targetAkses = HakAksesModel::find($id);
        if (! $targetAkses) {
            return;
        }

        $this->dataId = $targetAkses->id;
        $this->dataHakAkses = explode(',', $targetAkses->akses);

        $this->dispatch('showModal', id: 'editModal');
    }

    // Fungsi yang menangani aksi edit data
    public function edit()
    {
        $this->checkAkses();

        // Validasi input
        $this->validate([
            'dataHakAkses' => 'required|array',
        ]);

        // Update hak akses
        $akses = HakAksesModel::where('id', $this->dataId)->first();
        $akses->akses = implode(',', $this->dataHakAkses);
        $akses->save();

        $this->reset([
            'dataId',
            'dataHakAkses',
        ]);

        $this->dispatch('closeModal', id: 'editModal');
    }

    // Fungsi sebelum hapus data
    public function prepareDelete($id)
    {
        $targetAkses = HakAksesModel::find($id);
        if (! $targetAkses) {
            return;
        }

        $response = UserApi::getUserById(
            ToolsHelper::getAuthToken(),
            $targetAkses->user_id,
        );
        $name = isset($response->data->user) ? $response->data->user->name : '-';

        $this->dataId = $targetAkses->id;
        $this->infoDeleteMessage = "Apakah Anda yakin ingin menghapus hak akses untuk pengguna '$name' dengan ID <strong>'{$this->dataId}'</strong>?";

        $this->dispatch('showModal', id: 'deleteModal');
    }

    // Fungsi yang menangani aksi hapus data
    public function delete()
    {
        $this->checkAkses();

        // Konfirmasi penghapusan
        if ($this->dataKonfirmasi !== $this->dataId) {
            $this->addError('dataKonfirmasi', 'Konfirmasi penghapusan ID tidak sesuai.');

            return;
        }

        HakAksesModel::destroy($this->dataId);

        $this->reset([
            'dataId',
            'dataKonfirmasi',
            'infoDeleteMessage',
        ]);

        $this->dispatch('closeModal', id: 'deleteModal');
    }
}
