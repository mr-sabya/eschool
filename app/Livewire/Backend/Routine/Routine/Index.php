<?php

namespace App\Livewire\Backend\Routine\Routine;

use App\Models\AcademicSession;
use App\Models\ClassSection;
use App\Models\ClassSubjectAssign;
use App\Models\Day;
use App\Models\Department;
use App\Models\Routine;
use App\Models\SchoolClass;
use App\Models\Staff;
use App\Models\Subject;
use App\Models\TimeSlot;
use Illuminate\Database\Eloquent\Collection; // <-- IMPORT THIS
use Livewire\Component;

class Index extends Component
{
    // Filter properties
    public $selectedSessionId;
    public $selectedClassId;
    public $selectedSectionId;
    public $selectedDepartmentId;

    // --- CORRECTED PROPERTIES ---
    /** @var Collection<int, AcademicSession> */
    public Collection $academicSessions;

    /** @var Collection<int, SchoolClass> */
    public Collection $schoolClasses;

    /** @var Collection<int, ClassSection> */
    public Collection $classSections;

    /** @var Collection<int, Subject> */
    public Collection $subjects;

    /** @var Collection<int, Staff> */
    public Collection $staff;
    // ----------------------------

    /** @var Collection<int, Department> */
    public Collection $departments; // <-- New collection for the dropdown

    // Modal properties
    public $showModal = false;
    public $routineId;
    public $type = 'class';
    public $day_id, $time_slot_id, $subject_id, $staff_id;

    public $confirmingDeleteId = null;

    protected function rules()
    {
        return [
            'type' => 'required|in:class,exam',
            'day_id' => 'required|exists:days,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'subject_id' => 'required|exists:subjects,id',
            'staff_id' => 'required|exists:staff,id',
        ];
    }

    public function mount()
    {
        // Load static data once
        $this->academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        $this->schoolClasses = SchoolClass::orderBy('id')->get();
        $this->staff = Staff::orderBy('id')->get();
        $this->departments = Department::orderBy('id')->get(); // <-- Load departments

        // Initialize collections that are populated later
        // --- FIX IS HERE ---
        $this->classSections = new Collection();
        $this->subjects = new Collection();
        // --------------------

        // Set default filters (e.g., to the current active session)
        $this->selectedSessionId = AcademicSession::where('is_active', true)->first()->id ?? $this->academicSessions->first()->id ?? null;
    }

    // Lifecycle hook to update sections when a class is selected
    // UPDATED LIFECYCLE HOOKS
    public function updatedSelectedClassId($classId)
    {
        $this->reset('selectedSectionId', 'selectedDepartmentId'); // Reset both when class changes
        $this->subjects = new Collection();
        $this->classSections = $classId ? ClassSection::where('school_class_id', $classId)->get() : new Collection();
        $this->loadSubjects(); // Load subjects based on the new class
    }

    public function updatedSelectedDepartmentId()
    {
        $this->loadSubjects(); // Reload subjects when department changes
    }

    // NEW HELPER METHOD TO AVOID DUPLICATE CODE
    private function loadSubjects()
    {
        if (!$this->selectedClassId) {
            $this->subjects = new Collection();
            return;
        }

        // Start query on the pivot table
        $query = ClassSubjectAssign::where('academic_session_id', $this->selectedSessionId)
            ->where('school_class_id', $this->selectedClassId);

        // Conditionally filter by department
        if ($this->selectedDepartmentId) {
            $query->where('department_id', $this->selectedDepartmentId);
        } else {
            // When no department is selected, only show general subjects
            $query->whereNull('department_id');
        }

        $subjectIds = $query->pluck('subject_id')->unique();

        $this->subjects = $subjectIds->isNotEmpty()
            ? Subject::whereIn('id', $subjectIds)->orderBy('name')->get()
            : new Collection();
    }

    public function openModal($dayId, $timeSlotId)
    {
        $this->resetForm();
        $this->day_id = $dayId;
        $this->time_slot_id = $timeSlotId;
        $this->showModal = true;
    }

    public function save()
    {
        $data = $this->validate();

        // Add context from filters to the data being saved
        $data['academic_session_id'] = $this->selectedSessionId;
        $data['school_class_id'] = $this->selectedClassId;
        $data['class_section_id'] = $this->selectedSectionId;
        $data['department_id'] = $this->selectedDepartmentId; // <-- Add department ID

        Routine::updateOrCreate(['id' => $this->routineId], $data);

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Routine saved successfully.']);
        $this->closeModal();
    }


    public function edit($id)
    {
        $routine = Routine::findOrFail($id);
        $this->routineId = $routine->id;
        $this->type = $routine->type;
        $this->day_id = $routine->day_id;
        $this->time_slot_id = $routine->time_slot_id;
        $this->subject_id = $routine->subject_id;
        $this->staff_id = $routine->staff_id;
        $this->showModal = true;
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
        // This will trigger the Bootstrap modal via JS
        $this->dispatch('open-delete-modal');
    }

    public function deleteConfirmed()
    {
        Routine::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Routine entry deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['routineId', 'type', 'day_id', 'time_slot_id', 'subject_id', 'staff_id']);
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
    }

    // ... (The rest of your component logic remains the same)
    public function render()
    {
        $days = Day::orderBy('order')->get();
        $timeSlots = TimeSlot::orderBy('start_time')->get();
        $routines = collect();

        if ($this->selectedSessionId && $this->selectedClassId && $this->selectedSectionId) {
            $query = Routine::with(['subject', 'staff'])
                ->where('academic_session_id', $this->selectedSessionId)
                ->where('school_class_id', $this->selectedClassId)
                ->where('class_section_id', $this->selectedSectionId);

            // --- ADDED DEPARTMENT FILTER TO ROUTINE QUERY ---
            if ($this->selectedDepartmentId) {
                $query->where('department_id', $this->selectedDepartmentId);
            } else {
                $query->whereNull('department_id');
            }

            $routines = $query->get()->keyBy(fn($item) => $item->day_id . '-' . $item->time_slot_id);
        }

        return view('livewire.backend.routine.routine.index', [
            'days' => $days,
            'timeSlots' => $timeSlots,
            'routines' => $routines,
        ]);
    }
}
