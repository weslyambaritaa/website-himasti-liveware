<?php

namespace App\Livewire\Tim;

use App\Helper\ToolsHelper;
use App\Http\Api\UserApi;
use App\Models\TimModel;
use Livewire\Component;

class StandarLivewire extends Component
{
    // Mount
    public $auth;

    public $isEditor = false;

    public function mount()
    {
        $this->auth = request()->auth;
        $this->isEditor = in_array('Standar', $this->auth->akses);
    }

    // Check akses
    private function checkAkses()
    {
        if (!$this->isEditor) {
            abort(403, 'Unauthorized action.');
        }
        $this->resetErrorBag();
    }

    // Render

    public $search;

    public $searchPengguna;

    public function render()
    {
        $tahunList = TimModel::orderBy('tahun', 'desc')->get();
        if ($this->search) {
            $tahunList = $tahunList->filter(function ($item) {
                $user = $item->user;
                if (!$user) {
                    return false;
                }

                return str_contains(strtolower($item->tahun), strtolower($this->search));
            });
        }

        $data = [
            'tahunList' => $tahunList,
        ];

        return view('features.tahun.tahun-livewire', $data);
    }

    // Attribute
    public $dataId;
    public $dataTahun;
    public $infoTitleModal;

    // Persiapkan Ubah Data
    public function prepareChange($id)
    {
        if ($id) {
            $this->infoTitleModal = 'Ubah Tahun Standar';
            $data = a::where('id', $id)->first();
            if (!$data) {
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
            if (!$tim) {
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
        if (!$data) {
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
        if (!$data) {
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
        if (!$data) {
            $this->addError('dataConfirmId', 'Tim tidak ditemukan.');

            return;
        }

        // Hapus tim
        $data->delete();

        $this->reset(['dataId', 'dataConfirmId', 'infoDeleteMessage']);
        $this->dispatch('closeModal', id: 'deleteModal');
    }
}
