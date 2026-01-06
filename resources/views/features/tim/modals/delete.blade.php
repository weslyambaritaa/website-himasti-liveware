<div>
    <form wire:submit.prevent="delete">
        <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    {{-- Modal Header --}}
                    <div class="modal-header modal-colored-header bg-danger text-white sticky-top">
                        <h4 class="modal-title text-white">
                            Hapus Pengguna dari Tim SPM
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="modal-body">
                        {{-- Message --}}
                        <div>
                            <p>{!! $infoDeleteMessage !!}</p>
                        </div>
                        {{-- Konfirmasi ID --}}
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi ID</label>
                            <input type="text" wire:model="dataConfirmId"
                                class="form-control @error('dataConfirmId') is-invalid @enderror">
                            @error('dataConfirmId')
                                <div>
                                    <span class="text-danger">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer bg-light sticky-bottom">
                        <button type="submit" class="btn btn-outline-danger" wire:loading.attr="disabled">
                            <span wire:loading wire:target="delete"
                                class="spinner-border spinner-border-sm me-1"></span>
                            <span wire:loading.remove wire:target="delete">Hapus</span>
                            <span wire:loading wire:target="delete">Menghapus...</span>
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
