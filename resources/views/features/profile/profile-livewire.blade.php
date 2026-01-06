<div class="container-fluid">
    {{-- Breadcrumb --}}
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">
                        <i class="ti ti-settings"></i>
                        Pengaturan Akun
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none"
                                    href="{{ route('app.beranda') }}">Beranda</a></li>
                            <li class="breadcrumb-item" aria-current="page">Pengaturan Akun</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img alt="bg-breadcrumb" class="img-fluid mb-n4" src="/assets/images/breadcrumb/ChatBc.png">
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Konten --}}
    <div class="card mt-3">
        <div class="card-header bg-info-subtle">
            <h4 class="mb-0">
                <i class="ti ti-door"></i>&nbsp;SDI
            </h4>
            <span>Pengaturan yang lebih lengkap di website SDI</span>
        </div>
        <div class="card-body px-4 py-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="py-2">
                            <a href="https://sdi.del.ac.id/app/profile" target="_blank" class="text-decoration-none text-primary">
                                <i class="ti ti-external-link"></i>
                                <strong class="ms-2">Buka Pengaturan SDI</strong>
                            </a>
                        </div>
                        <div></div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-info-subtle">
            <h4 class="mb-0"><i class="ti ti-user"></i>&nbsp;Umum</h4><span>Perbarui informasi umum akun Anda</span>
        </div>
        <div class="card-body px-4 py-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="ti ti-id"></i><strong class="ms-2">ID</strong><br />
                            <small class="text-muted">{{ $auth->id }}</small>
                        </div>
                        <div></div>
                    </div>
                </li>
            </ul>
            <ul class="list-group mt-3">
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="ti ti-brand-whatsapp"></i><strong class="ms-2">Whatsapp</strong><br />
                            <small class="text-muted">{{ $auth->whatsapp ?? '[!] Belum ditambahkan' }}</small>
                        </div>
                        <div>
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#editWhatsappModal">
                                Ubah
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-info-subtle">
            <h4 class="mb-0">
                <i class="ti ti-lock"></i>&nbsp;
                Pribadi
            </h4>
            <span>
                Perbarui informasi pribadi akun Anda
            </span>
        </div>
        <div class="card-body px-4 py-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="ti ti-key"></i><strong class="ms-2">Kata Sandi</strong><br />
                            <small class="text-muted">
                                Terakhir diperbarui:
                                {{ date('d F Y - H:i', strtotime($auth->password_updated_at ?? '-')) }}
                            </small>
                        </div>
                        <div>
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#editPasswordModal">
                                Ubah
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    @include('features.profile.modals.edit-whatsapp')
    @include('features.profile.modals.edit-password')
</div>
