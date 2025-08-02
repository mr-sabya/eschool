<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-primary">
                <div class="card-title m-0 text-white">Import Students</div>
            </div>
            <div class="card-body">

                <div class="mb-4">
                    <h5>Instructions:</h5>
                    <ul>
                        <li>Please download the <a href="{{ route('admin.student.download.sample') }}" target="_blank">Excel Import Template</a>.</li>
                        <li>Fill the template with student data. Use the <code>import</code> column to mark rows as <code>yes</code> (import) or <code>no</code> (skip).</li>
                        <li>The first row must contain the headers as shown in the template.</li>
                        <li>Select Class and Section before importing. These will be applied to all imported students.</li>
                    </ul>
                </div>

                <form wire:submit.prevent="importStudents" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Class</label>
                            <select wire:model="import_class_id" class="form-control" wire:change="getSections($event.target.value)">
                                <option value="">-- Select Class --</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error('import_class_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Section</label>
                            <select wire:model="import_section_id" class="form-control">
                                <option value="">-- Optional --</option>
                                @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                            @error('import_section_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Excel/CSV File</label>
                        <input type="file" wire:model="import_file" class="form-control">
                        @error('import_file') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Import Students</button>
                    {{-- Loading indicator --}}
                    <div wire:loading wire:target="importStudents" class="mt-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Importing...</span>
                        </div>
                        <span class="ms-2">Importing students, please wait...</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>