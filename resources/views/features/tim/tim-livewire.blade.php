<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex">
                <div class="flex-fill">
                    <h3>
                        <i class="ti ti-users"></i>
                        Tim SPM
                        <span class="badge bg-info">
                            {{ $timList->count() }}
                        </span>
                    </h3>
                </div>
                <div>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari..."
                            wire:model.live.debounce.500ms="search">

                        @if ($isEditor)
                            <button wire:click.prevent="prepareChange('')" class="btn btn-primary" type="button"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="prepareChange">
                                    <i class="ti ti-plus"></i>
                                </span>
                                <span wire:loading wire:target="prepareChange"
                                    class="spinner-border spinner-border-sm"></span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered">
                <tr class="table-light">
                    <th class="text-center">No</th>
                    <th>Identitas</th>
                    <th>Posisi</th>
                    <th>Kontak</th>
                    <th>Status</th>
                    <th>Tanggal Bergabung</th>
                    <th>Tindakan</th>
                </tr>

                @foreach ($timList as $tim)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            <small class="text-muted">{{ '@' . ($tim->user ? $tim->user->username : '') }}</small><br />
                            {{ $tim->user ? $tim->user->name : '' }}
                        </td>
                        <td>
                            {{ $tim->posisi }}
                        </td>
                        <td>
                            @if (isset($tim->user->whatsapp))
                                <a target="_blank" class="text-primary"
                                    href="https://wa.me/{{ $tim->user->whatsapp ?? '' }}">
                                    <i class="ti ti-brand-whatsapp"></i>
                                    {{ $tim->user->whatsapp ?? '-' }} <br>
                                </a>
                            @endif
                            <a target="_blank" class="text-primary" href="mailto:{{ $tim->user->email ?? '' }}">
                                <i class="ti ti-mail"></i>
                                {{ $tim->user->email ?? '-' }}
                            </a> <br>
                        </td>
                        <td>
                            @if ($tim->is_aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            {{ $tim->created_at ? $tim->created_at->format('d F Y') : '-' }}
                        </td>
                        <td>
                            @if ($isEditor)
                                <div class="dropdown" wire:loading.remove wire:target="prepareChange,prepareDelete">
                                    <button class="btn btn-sm btn-secondary" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="#" class="dropdown-item text-warning"
                                                wire:click.prevent="prepareChange('{{ $tim->id }}')">
                                                <i class="ti ti-edit me-2"></i> Ubah
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="dropdown-item text-danger"
                                                wire:click.prevent="prepareDelete('{{ $tim->id }}')">
                                                <i class="ti ti-trash me-2"></i> Hapus
                                            </a>
                                        </li>

                                    </ul>
                                </div>

                                {{-- Loading --}}
                                <div wire:loading wire:target="prepareChange,prepareDelete">
                                    <span class="spinner-border spinner-border-sm text-info"></span>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach

                @if (sizeof($timList) <= 0)
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data pengguna yang tersedia.</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>

    {{-- Modal --}}
    @include('features.tim.modals.change')
    @include('features.tim.modals.delete')
    {{-- End Modal --}}
</div>

@section('others-css')
@endsection

@section('others-js')
@endsection
