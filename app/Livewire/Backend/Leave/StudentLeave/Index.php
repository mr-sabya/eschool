<?php

namespace App\Livewire\Backend\Leave\StudentLeave;

use App\Models\StudentLeave;
use App\Models\Student;
use App\Models\LeaveType;
use App\Models\SchoolClass;
use App\Models\ClassSection;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination, WithFileUploads;

    public $school_class_id, $class_section_id, $student_id;
    public $leave_type_id, $start_date, $end_date, $reason, $status = 'pending', $leaveId;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $confirmingDeleteId = null;
    public $attachment;

    public $sections = [];
    public $students = [];

    protected function rules()
    {
        return [
            'school_class_id'  => 'required|exists:school_classes,id',
            'class_section_id' => 'required|exists:class_sections,id',
            'student_id'       => 'required|exists:students,id',
            'leave_type_id'    => 'nullable|exists:leave_types,id',
            'start_date'       => 'required|date',
            'end_date'         => 'nullable|date|after_or_equal:start_date',
            'reason'           => 'nullable|string|max:500',
            'status'           => 'required|in:pending,approved,rejected',
            'attachment'    => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ];
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function loadSections($classId)
    {
        $this->school_class_id = $classId;
        $this->sections = ClassSection::where('school_class_id', $classId)->get();

        // Reset section and students
        $this->class_section_id = null;
        $this->students = [];
        $this->student_id = null;
    }

    public function loadStudents($sectionId)
    {
        $this->class_section_id = $sectionId;
        $this->students = Student::where('class_section_id', $sectionId)->with('user')->get();

        $this->student_id = null;
    }

    public function save()
    {
        $data = $this->validate();

        // Handle file upload
        if ($this->attachment) {
            $data['attachment'] = $this->attachment->store('attachments/leaves', 'public');
        }

        if ($this->leaveId) {
            $leave = StudentLeave::findOrFail($this->leaveId);

            // If new file uploaded, replace old one
            if ($this->attachment && $leave->attachment) {
                Storage::disk('public')->delete($leave->attachment);
            }

            $leave->update($data);
            $message = 'Leave updated successfully.';
        } else {
            StudentLeave::create($data);
            $message = 'Leave created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $leave = StudentLeave::findOrFail($id);

        $this->leaveId = $leave->id;
        $this->school_class_id = $leave->student?->school_class_id;

        // populate sections first
        $this->sections = $this->school_class_id
            ? ClassSection::where('school_class_id', $this->school_class_id)->get()
            : collect();

        // set class_section_id AFTER sections loaded
        $this->class_section_id = $leave->student?->class_section_id;

        // populate students after section_id is set
        $this->students = $this->class_section_id
            ? Student::where('class_section_id', $this->class_section_id)->with('user')->get()
            : collect();

        // finally set student_id
        $this->student_id = $leave->student_id;

        $this->leave_type_id = $leave->leave_type_id;
        $this->start_date = $leave->start_date;
        $this->end_date = $leave->end_date;
        $this->reason = $leave->reason;
        $this->status = $leave->status;

        $this->attachment = null; // reset for new upload
    }





    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        StudentLeave::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Leave deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset([
            'school_class_id',
            'class_section_id',
            'student_id',
            'leave_type_id',
            'start_date',
            'end_date',
            'reason',
            'status',
            'leaveId',
            'attachment',
        ]);
        $this->sections = [];
        $this->students = [];
        $this->status = 'pending';
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $leaves = StudentLeave::with(['student.user', 'leaveType'])
            ->where(function ($query) {
                $query->whereHas('student.user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                    ->orWhereHas('leaveType', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $classes    = SchoolClass::all();
        $leaveTypes = LeaveType::all();

        return view('livewire.backend.leave.student-leave.index', compact('leaves', 'classes', 'leaveTypes'));
    }
}
