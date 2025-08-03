<div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title m-0">Create Subject Mark Distribution</h5>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Class</label>
                        <select class="form-select" wire:model="school_class_id" wire:change="onClassChange">
                            <option value="">Select Class</option>
                            @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('school_class_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Section</label>
                        <select class="form-select" wire:model="class_section_id">
                            <option value="">Select Section</option>
                            @foreach ($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        @error('class_section_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- The rest of your form remains unchanged -->

                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Subject</th>
                            <th>Mark Type</th>
                            <th>Mark</th>
                            <th>Pass Mark</th>
                            <th style="width: 40px;">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $index => $row)
                        <tr>
                            <td>
                                <select class="form-select" wire:model="rows.{{ $index }}.subject_id">
                                    <option value="">Select Subject</option>
                                    @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error("rows.$index.subject_id") <small class="text-danger">{{ $message }}</small> @enderror
                            </td>

                            <td>
                                <select class="form-select" wire:model="rows.{{ $index }}.mark_distribution_id">
                                    <option value="">Select Mark Type</option>
                                    @foreach ($distributions as $dist)
                                    <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                                    @endforeach
                                </select>
                                @error("rows.$index.mark_distribution_id") <small class="text-danger">{{ $message }}</small> @enderror
                            </td>

                            <td>
                                <input type="number" step="0.01" class="form-control" placeholder="Mark"
                                    wire:model="rows.{{ $index }}.mark">
                                @error("rows.$index.mark") <small class="text-danger">{{ $message }}</small> @enderror
                            </td>

                            <td>
                                <input type="number" step="0.01" class="form-control" placeholder="Pass Mark"
                                    wire:model="rows.{{ $index }}.pass_mark">
                                @error("rows.$index.pass_mark") <small class="text-danger">{{ $message }}</small> @enderror
                            </td>

                            <td class="text-center">
                                @if (count($rows) > 1)
                                <button type="button" wire:click="removeRow({{ $index }})"
                                    class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-between">
                    <button type="button" wire:click="addRow" class="btn btn-sm btn-secondary">
                        + Add Row
                    </button>

                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-save me-1"></i> Save All
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>