<div class="card-body">
    <a href="#" class="text-nowrap logo-img text-center mb-4 d-block w-100">
        <img src="/img/logo-spm-dark-text.png" class="dark-logo" style="height: 72px;" alt="Logo-dark" />
    </a>

    <div class="position-relative text-center">
        <p class="mb-0 fs-4 px-3 d-inline-block bg-body text-dark z-index-5 position-relative">
            Masuk
        </p>
        <span class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
    </div>

    <div class="mb-4"></div>

    <form wire:submit.prevent="submit">
        <!-- Elemen tersembunyi untuk binding Livewire -->
        <input type="hidden" wire:model="systemId" id="systemIdInput">
        <input type="hidden" wire:model="info" id="infoInput">

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control @error('username') is-invalid @enderror" wire:model="username">
            @error('username')
                <div>
                    <span class="text-danger">{{ $message }}</span>
                </div>
            @enderror
            @error('systemId')
                <div>
                    <span class="text-danger">{{ $message }}</span>
                </div>
            @enderror
            @error('info')
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

        <button type="submit" class="btn btn-primary w-100 py-8 rounded-2">
            Kirim
        </button>

        <div class="position-relative text-center my-2">
            <p class="mb-0 fs-4 px-3 d-inline-block bg-body text-dark z-index-5 position-relative">
                atau
            </p>
            <span class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
        </div>

        <a href="{{ $urlLoginSSO }}" class="btn btn-success w-100 py-8 mb-4 rounded-2">
            Login dengan SSO
        </a>
    </form>
</div>

@section('others-js')
    <script src="/assets/vendor/crypto-js-4.2.0/crypto-js.js"></script>
    <script src="/scripts/auth/login-livewire-v1.js"></script>
@endsection
