<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-primary">
                <div class="card-title m-0 text-white">Import Staff</div>
            </div>
            <div class="card-body">

                <div class="mb-4">
                    <h5>Instructions:</h5>
                    <ul>
                        <li>Please download the <a href="{{ route('admin.staff.download.template') }}" target="_blank">Excel Import Template</a>.</li>
                        <li>Fill the template with staff data. The email field is required for every row.</li>
                        <li>The first row must contain the headers as shown in the template.</li>
                        <li>Default password will be set as <code>password</code> unless changed manually later.</li>
                    </ul>
                </div>

                <form wire:submit.prevent="importStaff" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Excel/CSV File</label>
                        <input type="file" wire:model="import_file" class="form-control">
                        @error('import_file') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Import Staff</button>

                    {{-- Loading indicator --}}
                    <div wire:loading wire:target="importStaff" class="mt-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Importing...</span>
                        </div>
                        <span class="ms-2">Importing staff, please wait...</span>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>