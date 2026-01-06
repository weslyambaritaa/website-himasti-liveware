<?php

namespace App\Livewire\Auth;

use App\Http\Api\UserApi;
use Livewire\Component;

class RegisterLivewire extends Component
{
    // Attributes
    public $username;

    public $password;

    // Fungsi yang dijalankan saat render
    public function render()
    {
        return view('features.auth.register-livewire');
    }

    // Fungsi yang menangani submit form registrasi
    public function submit()
    {
        // Validasi input
        $this->validate([
            'username' => 'required|string|max:50',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username harus diisi.',
            'username.string' => 'Username harus berupa string.',
            'username.max' => 'Username tidak boleh lebih dari 50 karakter.',
            'password.required' => 'Password harus diisi.',
            'password.string' => 'Password harus berupa string.',
        ]);

        $response = UserApi::postRegister(
            $this->username,
            $this->password
        );

        if (! $response || $response->status != 'success') {
            $this->addError('username', $response->message ?? 'Gagal melakukan pendaftaran, silakan coba lagi!');

            return;
        }

        return redirect()->route('auth.login')->with('success', 'Berhasil mendaftar, silakan masuk!');
    }
}
