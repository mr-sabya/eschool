<?php

namespace App\Livewire\Backend\Routine\ExamRoutine;

use App\Models\AcademicSession;
use App\Models\ClassRoom;
use App\Models\ClassSection;
use App\Models\ClassSubjectAssign;
use App\Models\Department;
use App\Models\Exam;
use App\Models\ExamRoutine;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\TimeSlot;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    // Form properties
    public $examRoutineId, $school_class_id, $class_section_id, $department_id, $subject_id, $time_slot_id, $exam_date, $class_room_id;

    // Filter properties
    public $selectedSessionId;
    public $selectedExamId;

    // Collections for dropdowns
    public Collection $academicSessions;
    public Collection $exams;
    public Collection $schoolClasses;
    public Collection $classSections;
    public Collection $departments;
    public Collection $subjects;
    public Collection $timeSlots;
    public Collection $classRooms;

    // Table & Control properties
    public $search = '';
    public $sortField = 'exam_date';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    protected function rules()
    {
        $exam = Exam::find($this->selectedExamId);
        $dateRule = $exam ? "after_or_equal:{$exam->start_date}|before_or_equal:{$exam->end_date}" : '';

        return [
            'school_class_id' => 'required|exists:school_classes,id',
            'class_section_id' => 'required|exists:class_sections,id',
            'department_id' => 'nullable|exists:departments,id',
            'subject_id' => 'required|exists:subjects,id',
            'time_slot_id' => [
                'required',
                'exists:time_slots,id',
                Rule::unique('exam_routines')->where(
                    fn($query) =>
                    $query->where('exam_id', $this->selectedExamId)
                        ->where('class_section_id', $this->class_section_id)
                        ->where('exam_date', $this->exam_date)
                )->ignore($this->examRoutineId),
            ],
            'exam_date' => ['required', 'date', $dateRule],
            'class_room_id' => 'required|exists:class_rooms,id',
        ];
    }

    public function mount()
    {
        $this->academicSessions = AcademicSession::orderBy('id', 'desc')->get();
        $this->schoolClasses = SchoolClass::orderBy('id')->get();
        $this->departments = Department::orderBy('id')->get();
        $this->timeSlots = TimeSlot::orderBy('start_time')->get();
        $this->classRooms = ClassRoom::orderBy('room_number')->get();

        $this->exams = new Collection();
        $this->classSections = new Collection();
        $this->subjects = new Collection();

        $this->selectedSessionId = AcademicSession::where('is_active', true)->value('id') ?? $this->academicSessions->first()->id ?? null;
        $this->updatedSelectedSessionId($this->selectedSessionId);
    }

    public function updatedSelectedSessionId($sessionId)
    {
        $this->exams = $sessionId ? Exam::where('academic_session_id', $sessionId)->get() : new Collection();
        $this->reset('selectedExamId');
    }

    public function updatedSchoolClassId($classId)
    {
        $this->classSections = $classId ? ClassSection::where('school_class_id', $classId)->get() : new Collection();
        $this->reset('class_section_id', 'department_id', 'subject_id');
        $this->loadSubjects();
    }

    public function updatedDepartmentId()
    {
        $this->loadSubjects();
    }

    public function loadSubjects()
    {
        if (!$this->selectedSessionId || !$this->school_class_id) {
            $this->subjects = new Collection();
            return;
        }

        $query = ClassSubjectAssign::where('academic_session_id', $this->selectedSessionId)
            ->where('school_class_id', $this->school_class_id);

        $query->when($this->department_id, fn($q) => $q->where('department_id', $this->department_id), fn($q) => $q->whereNull('department_id'));

        $subjectIds = $query->pluck('subject_id')->unique();
        $this->subjects = $subjectIds->isNotEmpty() ? Subject::whereIn('id', $subjectIds)->orderBy('name')->get() : new Collection();
    }

    public function save()
    {
        if (!$this->selectedExamId) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Please select an exam first.']);
            return;
        }

        $data = $this->validate();
        $data['exam_id'] = $this->selectedExamId;

        ExamRoutine::updateOrCreate(['id' => $this->examRoutineId], $data);
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Exam schedule saved successfully.']);
        $this->resetForm();
    }

    public function edit($id)
    {
        $routine = ExamRoutine::findOrFail($id);
        $this->examRoutineId = $routine->id;
        $this->school_class_id = $routine->school_class_id;
        $this->updatedSchoolClassId($routine->school_class_id); // Load related sections and subjects
        $this->class_section_id = $routine->class_section_id;
        $this->department_id = $routine->department_id;
        $this->loadSubjects(); // Ensure subjects are loaded with the correct department
        $this->subject_id = $routine->subject_id;
        $this->time_slot_id = $routine->time_slot_id;
        $this->exam_date = $routine->exam_date;
        $this->class_room_id = $routine->class_room_id;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        ExamRoutine::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Schedule entry deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['examRoutineId', 'school_class_id', 'class_section_id', 'department_id', 'subject_id', 'time_slot_id', 'exam_date', 'class_room_id']);
        $this->resetErrorBag();
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
        $routinesQuery = ExamRoutine::query();

        if ($this->selectedExamId) {
            $routinesQuery->where('exam_id', $this->selectedExamId)
                ->with(['schoolClass', 'classSection', 'subject', 'timeSlot', 'classRoom'])
                ->when($this->search, fn($q) => $q->whereHas('subject', fn($sq) => $sq->where('name', 'like', '%' . $this->search . '%')))
                ->orderBy($this->sortField, $this->sortDirection);
        } else {
            $routinesQuery->where('id', false); // Return no results if no exam is selected
        }

        return view('livewire.backend.routine.exam-routine.index', [
            'routines' => $routinesQuery->paginate($this->perPage),
        ]);
    }
}
