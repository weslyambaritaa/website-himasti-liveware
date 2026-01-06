<div>
    <form wire:submit.prevent="onChange">
        <div wire:ignore.self class="modal fade" id="changeModal" tabindex="-1" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    {{-- Modal Header --}}
                    <div class="modal-header modal-colored-header bg-primary text-white sticky-top">
                        <h4 class="modal-title text-white">
                            {{ $infoTitleModal }}
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="modal-body">

                        @if(!$dataId)
                            {{-- Cari Pengguna --}}
                            <div class="mb-3">
                                <label class="form-label">Cari Pengguna</label>
                                <input type="text" class="form-control" placeholder="Cari Pengguna"
                                    wire:model.live.debounce.500ms="searchPengguna">
                            </div>
                            {{-- Pilih Pengguna --}}
                            <div class="mb-3">
                                <label class="form-label">Pilih Pengguna</label>
                                @if ($searchPenggunaList && sizeof($searchPenggunaList) > 0)
                                    <select wire:model="dataUserId" class="form-select" size="5">
                                        @foreach ($searchPenggunaList as $pengguna)
                                            <option value="{{ $pengguna->id }}">
                                                [{{ $pengguna->username }}] {{ $pengguna->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="text-muted">
                                        Tidak ada pengguna yang ditemukan. Silakan cari dengan nama atau username.
                                    </div>
                                @endif
                                @error('dataUserId')
                                    <div>
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Posisi</label>
                            <select wire:model="dataPosisi" class="form-select">
                                @foreach ($optionPosisiTim as $posisi)
                                    <option value="{{ $posisi }}">{{ $posisi }}</option>
                                @endforeach
                            </select>
                            @error('dataPosisi')
                                <div>
                                    <span class="text-danger">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Bergabung</label>
                            <input type="date" class="form-control" wire:model="dataTanggalBergabung">
                            @error('dataTanggalBergabung')
                                <div>
                                    <span class="text-danger">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        @if($dataId)
                            {{-- Is Aktif --}}
                            <div class="mb-3">
                                <label class="form-label">Masih Aktif?</label>
                                <select wire:model="dataIsAktif" class="form-select">
                                    <option value="1">Ya</option>
                                    <option value="0">Tidak</option>
                                </select>
                                @error('dataIsAktif')
                                    <div>
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        @endif
                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer bg-light sticky-bottom">
                        <button type="submit" class="btn btn-outline-primary" wire:loading.attr="disabled">
                            <span wire:loading wire:target="onChange" class="spinner-border spinner-border-sm me-1"></span>
                            <span wire:loading.remove wire:target="onChange">Simpan</span>
                            <span wire:loading wire:target="onChange">Menyimpan...</span>
                        </button>
                    </div>

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </form>
</div>
