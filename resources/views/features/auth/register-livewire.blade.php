<div class="card-body">
    <a href="#" class="text-nowrap logo-img text-center mb-4 d-block w-100">
        <img src="/img/logo-spm-dark-text.png" class="dark-logo" style="height: 72px;" alt="Logo-dark" />
    </a>

    <div class="position-relative text-center">
        <p class="mb-0 fs-4 px-3 d-inline-block bg-body text-dark z-index-5 position-relative">
            Daftar
        </p>
        <span class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
    </div>

    <div class="mb-4">
        <p>Gunakan akun CIS kamu untuk melakukan pendaftaran.</p>
    </div>

    <form wire:submit.prevent="submit">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control @error('username') is-invalid @enderror" wire:model="username">
            @error('username')
                <div>
                    <span class="text-danger">{{ $message }}</span>
                </div>
            @enderror
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" wire:model="password">
            @error('password')
                <div>
                    <span class="text-danger">{{ $message }}</span>
                </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">
            Kirim
        </button>
        <div class="d-flex align-items-center justify-content-center">
            <p class="mb-0 fw-medium">Sudah memiliki akun?</p>
            <a class="text-primary fw-medium ms-2" href="{{ route('auth.login') }}">Masuk</a>
        </div>
    </form>
</div>

@section('others-js')
    <script>
        document.addEventListener("livewire:initialized", () => {
            hidePreloader();
        });
    </script>
@endsection
