<div>
    <form wire:submit.prevent="editWhatsapp">
        <div wire:ignore.self class="modal fade" id="editWhatsappModal" tabindex="-1" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    {{-- Modal Header --}}
                    <div class="modal-header modal-colored-header bg-primary text-white sticky-top">
                        <h4 class="modal-title text-white">
                            Ubah Whatsapp
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="modal-body">
                        {{-- Whatsapp --}}
                        <div class="mb-3">
                            <label class="form-label">
                                <span>
                                    Whatsapp
                                    <small class="text-muted">(awali dengan kode negara)</small>
                                </span><br />
                                <small class="text-muted">Contoh valid: 6281234567890</small>
                            </label>
                            <input type="text" class="form-control" wire:model="dataWhatsapp">
                            @error('dataWhatsapp')
                                <div>
                                    <span class="text-danger">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" wire:model="dataPassword">
                            @error('dataPassword')
                                <div>
                                    <span class="text-danger">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer bg-light sticky-bottom">
                        <button type="submit" class="btn btn-outline-primary" wire:loading.attr="disabled">
                            <span wire:loading wire:target="editWhatsapp"
                                class="spinner-border spinner-border-sm me-1"></span>
                            <span wire:loading.remove wire:target="editWhatsapp">Simpan</span>
                            <span wire:loading wire:target="editWhatsapp">Menyimpan...</span>
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
