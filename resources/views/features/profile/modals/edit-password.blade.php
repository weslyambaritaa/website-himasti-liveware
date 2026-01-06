<div>
    <form wire:submit.prevent="editPassword">
        <div wire:ignore.self class="modal fade" id="editPasswordModal" tabindex="-1" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    {{-- Modal Header --}}
                    <div class="modal-header modal-colored-header bg-primary text-white sticky-top">
                        <h4 class="modal-title text-white">
                            Ubah Kata Sandi
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="modal-body">
                        {{-- Kata Sandi Lama --}}
                        <div class="mb-3">
                            <label class="form-label">Kata Sandi Lama</label>
                            <input type="password" class="form-control" wire:model="dataPasswordLama">
                            @error('dataPasswordLama')
                                <div>
                                    <span class="text-danger">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        {{-- Kata Sandi Baru --}}
                        <div class="mb-3">
                            <label class="form-label">Kata Sandi Baru</label>
                            <input type="password" class="form-control" wire:model="dataPasswordBaru">
                            @error('dataPasswordBaru')
                                <div>
                                    <span class="text-danger">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-primary" wire:loading.attr="disabled">
                            <span wire:loading wire:target="editPassword"
                                class="spinner-border spinner-border-sm me-1"></span>
                            <span wire:loading.remove wire:target="editPassword">Simpan</span>
                            <span wire:loading wire:target="editPassword">Menyimpan...</span>
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
