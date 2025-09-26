<div>
    {{-- CSS for ID Card Design and Printing (No changes here) --}}
    <style>
        .id-card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .id-card {
            width: 320px;
            height: 500px;
            border: 1px solid #ccc;
            border-radius: 15px;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            page-break-inside: avoid;
        }

        .id-card-header {
            background-color: #004a99;
            color: white;
            padding: 15px;
            text-align: center;
            border-bottom: 5px solid #fdb913;
        }

        .id-card-header img {
            max-width: 50px;
            margin-bottom: 5px;
        }

        .id-card-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .id-card-body {
            flex-grow: 1;
            padding: 15px;
            text-align: center;
        }

        .student-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #004a99;
            object-fit: cover;
            margin: 0 auto 15px auto;
        }

        .student-name {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .student-details {
            font-size: 14px;
            color: #555;
            text-align: left;
            padding: 0 10px;
        }

        .student-details p {
            margin: 8px 0;
        }

        .student-details strong {
            display: inline-block;
            width: 100px;
            color: #333;
        }

        .id-card-footer {
            padding: 10px;
            text-align: center;
        }

        .barcode {
            width: 80%;
            height: 40px;
            margin: 0 auto;
            background-image: repeating-linear-gradient(to right, black, black 2px, white 2px, white 4px);
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .id-card-container,
            .id-card-container * {
                visibility: visible;
            }

            .id-card-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
                margin: 0;
            }

            .card {
                display: none;
            }

            @page {
                size: A4;
                margin: 1cm;
            }
        }
    </style>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Student ID Card Generator</h3>
        </div>
        <div class="card-body">
            {{-- Filter Section --}}
            <div class="row border-bottom pb-3 mb-3">
                <div class="col-md-3">
                    <label>Generation Type</label>
                    <select wire:model="generationType" class="form-control">
                        <option value="individual">Individual Student</option>
                        <option value="bulk">Bulk by Class</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="validUntil">Valid Until</label>
                    <input type="date" wire:model="validUntil" class="form-control" id="validUntil">
                    @error('validUntil') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Conditional Filters --}}
            @if ($generationType === 'individual')
            <div class="row">
                <div class="col-md-6">
                    <label>Search Student (by Name or Roll)</label>
                    <input type="text" wire:model.debounce.300ms="searchQuery" class="form-control" placeholder="Type to search...">
                    @if(count($searchableStudents) > 0)
                    <select wire:model="selectedStudentId" class="form-control mt-2">
                        <option value="">-- Select a student --</option>
                        @foreach ($searchableStudents as $student)
                        <option value="{{ $student->id }}">{{ $student->user->name }} (Roll: {{ $student->roll_number }})</option>
                        @endforeach
                    </select>
                    @endif
                    @error('selectedStudentId') <span class="text-danger">Please select a student from the list.</span> @enderror
                </div>
            </div>
            @else
            <div class="row">
                {{-- THE FIX: Added wire:change here to reset the class dropdown --}}
                <div class="col-md-3"><label>Session</label><select wire:model="selectedAcademicSessionId" wire:change="handleSessionChange" class="form-control">
                        <option value="">Select</option>@foreach($academicSessions as $s)<option value="{{$s->id}}">{{$s->name}}</option>@endforeach
                    </select> @error('selectedAcademicSessionId') <span class="text-danger">{{ $message }}</span> @enderror</div>

                {{-- THE FIX: Added wire:change here to trigger the section/department population --}}
                <div class="col-md-3"><label>Class</label><select wire:model="selectedSchoolClassId" wire:change="handleSchoolClassChange" class="form-control" @if(!$selectedAcademicSessionId) disabled @endif>
                        <option value="">Select</option>@foreach($schoolClasses as $c)<option value="{{$c->id}}">{{$c->name}}</option>@endforeach
                    </select> @error('selectedSchoolClassId') <span class="text-danger">{{ $message }}</span> @enderror</div>

                <div class="col-md-3"><label>Section</label><select wire:model="selectedClassSectionId" class="form-control" @if(count($classSections)==0) disabled @endif>
                        <option value="">Select</option>@foreach($classSections as $s)<option value="{{$s->id}}">{{$s->name}}</option>@endforeach
                    </select> @error('selectedClassSectionId') <span class="text-danger">{{ $message }}</span> @enderror</div>

                @if($showDepartmentFilter)
                <div class="col-md-3"><label>Department</label><select wire:model="selectedDepartmentId" class="form-control" @if(count($classSections)==0) disabled @endif>
                        <option value="">All</option>@foreach($departments as $d)<option value="{{$d->id}}">{{$d->name}}</option>@endforeach
                    </select></div>
                @endif
            </div>
            @endif

            <div class="mt-4">
                <button wire:click="generateCards" class="btn btn-primary"><i class="fas fa-id-card"></i> Generate Cards</button>
            </div>
        </div>
    </div>

    {{-- Results Area (No changes from here down) --}}
    @if(count($studentsToPrint) > 0)
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Generated ID Cards ({{ count($studentsToPrint) }})</h3>
            <div class="card-tools">
                <button onclick="window.print()" class="btn btn-success"><i class="fas fa-print"></i> Print All</button>
            </div>
        </div>
        <div class="card-body">
            <div class="id-card-container">
                @foreach($studentsToPrint as $student)
                <div class="id-card">
                    <div class="id-card-header">
                        <img src="{{ asset('storage/' . $schoolSettings->logo) }}" alt="School Logo">
                        <h3>{{ $schoolSettings->school_name }}</h3>
                    </div>
                    <div class="id-card-body">
                        <img class="student-photo" src="{{ $student->user->avatar_url }}" alt="Student Photo">
                        <div class="student-name">{{ $student->user->name }}</div>
                        <div class="student-details">
                            <p><strong>Class:</strong> {{ $student->schoolClass->name }} ({{ $student->classSection->name }})</p>
                            <p><strong>Roll No:</strong> {{ $student->roll_number }}</p>
                            <p><strong>Guardian:</strong> {{ optional($student->guardian)->name }}</p>
                            <p><strong>Phone:</strong> {{ $student->phone }}</p>
                            <p><strong>Valid Until:</strong> {{ \Carbon\Carbon::parse($validUntil)->format('d M, Y') }}</p>
                        </div>
                    </div>
                    <div class="id-card-footer">
                        <div class="barcode" title="ID: {{ $student->admission_number }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>