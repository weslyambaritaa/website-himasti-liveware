<?php

namespace App\Livewire\Tim;

use App\Helper\ConstHelper;
use App\Helper\ToolsHelper;
use App\Http\Api\UserApi;
use App\Models\TimModel;
use Livewire\Component;

class TimLivewire extends Component
{
    // Mount
    public $auth;

    public $optionPosisiTim = [];

    public $isEditor = false;

    public function mount()
    {
        $this->auth = request()->auth;
        $this->isEditor = in_array('Tim SPM', $this->auth->akses);
        $this->optionPosisiTim = ConstHelper::getOptionPosisiTim();
    }

    // Check akses
    private function checkAkses()
    {
        if (! $this->isEditor) {
            abort(403, 'Unauthorized action.');
        }
        $this->resetErrorBag();
    }

    // Render

    public $search;

    public $searchPengguna;

    public function render()
    {
        $timList = TimModel::orderBy('is_aktif', 'desc')->get();
        $userIds = $timList->pluck('user_id')->unique()->toArray();
        $response = UserApi::postReqUsersByIds(
            ToolsHelper::getAuthToken(),
            $userIds
        );
        if (isset($response->data->users)) {
            $usersList = collect($response->data->users)->map(function ($user) {
                return $user;
            })->all();
        } else {
            $usersList = [];
        }

        foreach ($timList as $tim) {
            $tim->user = collect($usersList)->firstWhere('id', $tim->user_id);
        }

        // Urutkan berdasarkan name
        $timList = $timList->sortBy(function ($item) {
            return $item->user->name ?? '';
        });

        if ($this->search) {
            $timList = $timList->filter(function ($item) {
                $user = $item->user;
                if (! $user) {
                    return false;
                }

                return str_contains(strtolower($user->name), strtolower($this->search)) ||
                    str_contains(strtolower($user->username), strtolower($this->search));
            });
        }

        // Peancarian data pengguna
        $searchPenggunaList = [];
        if ($this->searchPengguna) {
            $authToken = ToolsHelper::getAuthToken();
            $result = UserApi::getUsers($authToken, search: $this->searchPengguna, limit: 5, alias: '');
            if (isset($result->data->users)) {
                $searchPenggunaList = collect($result->data->users)->map(function ($user) {
                    return (object) $user;
                })->all();
            }
        }

        $data = [
            'timList' => $timList,
            'searchPenggunaList' => $searchPenggunaList,
        ];

        return view('features.tim.tim-livewire', $data);
    }

    // Attribute
    public $dataUserId;

    public $dataPosisi = 'Ketua';

    public $dataTanggalBergabung;

    public $dataId;

    public $dataIsAktif;

    public $infoTitleModal;

    // Persiapkan Ubah Data
    public function prepareChange($id)
    {
        if ($id) {
            $this->infoTitleModal = 'Ubah Informasi Anggota Tim';
            $data = TimModel::where('id', $id)->first();
            if (! $data) {
                return;
            }

            $this->dataId = $data->id;
            $this->dataPosisi = $data->posisi;
            $this->dataTanggalBergabung = $data->tanggal_bergabung;
            $this->dataIsAktif = $data->is_aktif ? '1' : '0';
        } else {
            $this->infoTitleModal = 'Tambah Informasi Anggota Tim';
            $this->reset([
                'dataId',
                'dataPosisi',
                'dataTanggalBergabung',
                'dataIsAktif',
                'dataUserId',
                'searchPengguna',
            ]);
        }

        $this->dispatch('showModal', id: 'changeModal');
    }

    // Kelola Data
    public function onChange()
    {
        $this->checkAkses();

        $this->validate([
            'dataPosisi' => 'required',
            'dataTanggalBergabung' => 'required|date',
        ]);

        if ($this->dataId) {
            // Ubah Data
            $this->validate([
                'dataIsAktif' => 'required',
            ]);
            $tim = TimModel::find($this->dataId);
            if (! $tim) {
                $this->addError('dataUserId', 'Tim tidak ditemukan.');

                return;
            }

            $tim->posisi = $this->dataPosisi;
            $tim->tanggal_bergabung = $this->dataTanggalBergabung;
            $tim->is_aktif = $this->dataIsAktif === '1' ? true : false;
            $tim->save();

            $this->dispatch('showSuccessAlert', message: 'Informasi Anggota tim berhasil diubah.');
        } else {
            // Tambah Data
            $this->validate([
                'dataUserId' => 'required',
            ]);

            // Periksa apakah sudah ada data
            $existing = TimModel::where('user_id', $this->dataUserId)->first();
            if ($existing) {
                $this->addError('dataUserId', 'Pengguna ini sudah terdaftar ke dalam tim.');

                return;
            }

            // Simpan data variabel baru
            TimModel::create([
                'id' => ToolsHelper::generateId(),
                'user_id' => $this->dataUserId,
                'posisi' => $this->dataPosisi,
                'tanggal_bergabung' => $this->dataTanggalBergabung,
                'is_aktif' => true,
            ]);

            $this->dispatch('showSuccessAlert', message: 'Informasi Anggota tim berhasil ditambahkan.');
        }

        // Reset input fields
        $this->reset([
            'dataId',
            'dataPosisi',
            'dataTanggalBergabung',
            'dataIsAktif',
            'dataUserId',
        ]);

        $this->dispatch('closeModal', id: 'changeModal');
    }

    // Detail Data
    public $detail;

    public function prepareDetail($id)
    {
        $data = TimModel::where('id', $id)->first();
        if (! $data) {
            return;
        }

        $this->detail = $data;
        $this->dispatch('showModal', id: 'detailModal');
    }

    // Hapus Data
    public $dataConfirmId;

    public $infoDeleteMessage;

    public function prepareDelete($id)
    {
        $data = TimModel::where('id', $id)->first();
        if (! $data) {
            return;
        }

        $this->dataId = $data->id;
        $this->dataConfirmId = '';

        $this->infoDeleteMessage = "Apakah Anda yakin ingin menghapus anggota Tim dengan ID <br/><strong>{$this->dataId}</strong> ?";

        $this->dispatch('showModal', id: 'deleteModal');
    }

    public function delete()
    {
        $this->checkAkses();

        if ($this->dataId !== $this->dataConfirmId) {
            $this->addError('dataConfirmId', 'Konfirmasi ID tidak sesuai.');

            return;
        }

        // Periksa apakah tim ada
        $data = TimModel::find($this->dataId);
        if (! $data) {
            $this->addError('dataConfirmId', 'Tim tidak ditemukan.');

            return;
        }

        // Hapus tim
        $data->delete();

        $this->reset(['dataId', 'dataConfirmId', 'infoDeleteMessage']);
        $this->dispatch('closeModal', id: 'deleteModal');
    }
}
