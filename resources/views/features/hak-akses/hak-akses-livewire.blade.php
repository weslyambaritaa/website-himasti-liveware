<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex">
                <div class="flex-fill">
                    <h3>
                        <i class="ti ti-lock"></i>
                        Hak Akses
                    </h3>
                </div>
                <div>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari..."
                            wire:model.live.debounce.700ms="search">
                        @if ($this->isEditor)
                            <button data-bs-toggle="modal" data-bs-target="#addModal" class="btn btn-primary"
                                type="button">
                                <i class="ti ti-plus"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered">
                <tr class="table-light">
                    <th>Identitas</th>
                    <th>Akses</th>
                    <th>Tindakan</th>
                </tr>

                @foreach ($aksesList as $akses)
                    <tr>
                        <td>
                            <small
                                class="text-muted">{{ '@' . ($akses->user ? $akses->user->username : '') }}</small><br />
                            <span>{{ $akses->user ? $akses->user->name : '' }}</span>
                        </td>
                        <td>
                            @if ($akses->akses)
                                <ul class="list-unstyled">
                                    @foreach (explode(',', $akses->akses) as $hakAkses)
                                        <li>
                                            <i class="ti ti-minus"></i>
                                            {{ $hakAkses }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">Tidak ada hak akses</span>
                            @endif
                        </td>
                        <td>
                            @if ($this->isEditor)
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="#" class="dropdown-item text-warning"
                                                wire:click.prevent="prepareEdit('{{ $akses->id }}')">
                                                <i class="ti ti-edit me-2"></i> Ubah
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="dropdown-item text-danger"
                                                wire:click.prevent="prepareDelete('{{ $akses->id }}')">
                                                <i class="ti ti-trash me-2"></i> Hapus
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach

                @if (sizeof($aksesList) <= 0)
                    <tr>
                        <td colspan="3" class="text-center">Belum ada data pengguna yang tersedia.</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>

    {{-- Modal --}}
    @include('features.hak-akses.modals.add')
    @include('features.hak-akses.modals.edit')
    @include('features.hak-akses.modals.delete')
    {{-- End Modal --}}
</div>

@section('others-css')
    <link rel="stylesheet" href="/assets/vendor/quill-2.0.3/dist/quill.snow.min.css">
@endsection

@section('others-js')
    <script src="/assets/vendor/quill-2.0.3/dist/quill.min.js"></script>

    {{-- Add Data with Quill --}}
    <script>
        document.addEventListener("livewire:initialized", () => {

        });
    </script>
@endsection
