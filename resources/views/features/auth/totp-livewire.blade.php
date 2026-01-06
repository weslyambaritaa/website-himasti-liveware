<div class="card-body">
    <a href="#" class="text-nowrap logo-img text-center mb-4 d-block w-100">
        <img src="/img/logo-spm-dark-text.png" class="dark-logo" style="height: 72px;" alt="Logo-dark" />
    </a>

    @if ($qrCode != null)
        <div>
            <div class="position-relative text-center">
                <p class="mb-0 fs-4 px-3 d-inline-block bg-body text-dark z-index-5 position-relative">
                    QRCODE-TOTP
                </p>
                <span class="border-top w-100 position-absolute top-50 start-50 translate-middle"> </span>
            </div>

            <div class="text-center">
                Scan QRCode berikut pada smartphone kamu, menggunakan aplikasi Authenticator seperti <a
                    class="text-primary" href="https://play.google.com/store/apps/details?id=com.azure.authenticator"
                    target="_blank">Microsoft
                    Authenticator</a>
                atau <a class="text-primary"
                    href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                    target="_blank">Google Authenticator</a>
            </div>

            <div class="mb-4 text-center">
                <img src="{{ $qrCode }}" alt="QR Code" class="img-fluid rounded-2" style="max-width: 100%;">
            </div>
        </div>
    @endif


    <div class="position-relative text-center">
        <p class="mb-0 fs-4 px-3 d-inline-block bg-body text-dark z-index-5 position-relative">
            Verifikasi-TOTP
        </p>
        <span class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
    </div>

    <div class="mb-4 text-center">Silahkan menyelesaikan verifikasi 2 langkah dengan memasukkan kode yang ditampilkan
        pada
        aplikasi Authenticator untuk melanjutkan.</div>

    <form wire:submit.prevent="submit">
        <div class="mb-3">
            <label class="form-label">Token</label>
            <input type="number" class="form-control @error('token') is-invalid @enderror" wire:model="token">
            @error('token')
                <div>
                    <span class="text-danger">{{ $message }}</span>
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 py-8 rounded-2">
            Kirim
        </button>

        <hr>

        <a onclick="onLogout()" class="btn btn-danger w-100 py-8 mb-4 rounded-2">
            Keluar
        </a>

    </form>
</div>


@section('others-js')
    <script>
        document.addEventListener("livewire:initialized", () => {
            hidePreloader();
        });
    </script>
@endsection
