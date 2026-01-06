<div>
    <form wire:submit.prevent="edit">
        <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    {{-- Modal Header --}}
                    <div class="modal-header modal-colored-header bg-primary text-white sticky-top">
                        <h4 class="modal-title text-white">
                            Ubah Hak Akses
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="modal-body">
                        {{-- Pilih Hak Akses --}}
                        <div class="mb-3">
                            <label class="form-label">Pilih Hak Akses</label>
                            <select wire:model="dataHakAkses" class="form-select" size="{{ count($optionRoles) + 1 }}"
                                multiple>
                                @foreach ($optionRoles as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                            @error('dataHakAkses')
                                <div>
                                    <span class="text-danger">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer bg-light sticky-bottom">
                        <button type="submit" class="btn btn-outline-primary" wire:loading.attr="disabled">
                            <span wire:loading wire:target="edit" class="spinner-border spinner-border-sm me-1"></span>
                            <span wire:loading.remove wire:target="edit">Simpan</span>
                            <span wire:loading wire:target="edit">Menyimpan...</span>
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
