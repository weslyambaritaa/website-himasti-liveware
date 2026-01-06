<?php

namespace App\Livewire\Profile;

use App\Helper\ToolsHelper;
use App\Http\Api\UserApi;
use Livewire\Component;

class ProfileLivewire extends Component
{
    // Attributes
    public $auth;

    // Attributes untuk form
    public $dataPasswordLama;

    public $dataPasswordBaru;

    public $dataWhatsapp;

    public $dataPassword;

    // Fungsi yang dijalankan saat komponen di-mount
    public function mount($auth = null)
    {
        $this->auth = $auth ?? request()->auth;
        $this->dataWhatsapp = $this->auth->whatsapp ?? '';
    }

    // Fungsi yang dijalankan saat render
    public function render()
    {
        return view('features.profile.profile-livewire');
    }

    // Fungsi untuk submit edit password
    public function editPassword()
    {
        $this->resetErrorBag();
        $this->validate([
            'dataPasswordLama' => 'required',
            'dataPasswordBaru' => 'required',
        ]);

        $authToken = ToolsHelper::getAuthToken();
        $response = UserApi::putMePassword($authToken, $this->dataPasswordBaru, $this->dataPasswordLama);
        if (! isset($response->status) || $response->status != 'success') {
            $message = isset($response->message) ? $response->message : 'Gagal mengirimkan data.';
            $this->addError('dataPasswordLama', $message);

            return;
        }

        $this->reset([
            'dataPasswordLama',
            'dataPasswordBaru',
        ]);

        $this->dispatch('closeModal', id: 'editPasswordModal');

        // Reload halaman
        $this->dispatch('reloadPage');
    }

    // Fungsi untuk submit edit whatsapp
    public function editWhatsapp()
    {
        $this->resetErrorBag();
        $this->validate([
            'dataWhatsapp' => 'required',
            'dataPassword' => 'required',
        ]);

        $authToken = ToolsHelper::getAuthToken();
        $response = UserApi::putMeWhatsapp($authToken, $this->dataWhatsapp, $this->dataPassword);
        if (! isset($response->status) || $response->status != 'success') {
            $message = isset($response->message) ? $response->message : 'Gagal mengirimkan data.';
            $this->addError('dataWhatsapp', $message);

            return;
        }

        $this->reset([
            'dataWhatsapp',
            'dataPassword',
        ]);

        $this->dispatch('closeModal', id: 'editWhatsappModal');

        // Reload halaman
        $this->dispatch('reloadPage');
    }
}
