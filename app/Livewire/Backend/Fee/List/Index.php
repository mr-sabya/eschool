<?php

namespace App\Livewire\Backend\Fee\List;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\FeeList;
use App\Models\FeeType;
use App\Models\AcademicSession;
use App\Models\SchoolClass;
use App\Models\ClassSection;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $feeListId;
    public $academic_session_id;
    public $fee_type_id;
    public $school_class_id;
    public $class_section_id;
    public $name;
    public $amount;
    public $due_date;
    public $is_active = true;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    // dynamic lists
    public $academicSessions;
    public $feeTypes;
    public $classes;
    public $sections = [];

    protected function rules()
    {
        return [
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'fee_type_id'         => 'required|exists:fee_types,id',
            'name'                => 'required|string|max:255',
            'amount'              => 'required|numeric|min:0',
            'due_date'            => 'nullable|date',
            'school_class_id'     => 'nullable|exists:school_classes,id',
            'class_section_id'    => 'nullable|exists:class_sections,id',
            'is_active'           => 'boolean',
        ];
    }

    public function mount()
    {
        $this->academicSessions = AcademicSession::where('is_active', true)->get();
        $this->feeTypes         = FeeType::where('is_active', true)->get();
        $this->classes          = SchoolClass::where('is_active', true)->get();
        $this->sections         = collect();
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    // custom function called by wire:change on class select
    public function onClassChange($classId)
    {
        $this->school_class_id = $classId ?: null;
        $this->class_section_id = null;
        $this->sections = $this->school_class_id
            ? ClassSection::where('school_class_id', $this->school_class_id)->get()
            : collect();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Class changed and sections loaded.'
        ]);
    }

    public function save()
    {
        $data = $this->validate();

        // ensure boolean value
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        FeeList::updateOrCreate(
            ['id' => $this->feeListId],
            $data
        );

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $this->feeListId ? 'Fee list updated successfully.' : 'Fee list created successfully.'
        ]);

        $this->resetForm();
        // refresh lists (in case user added sessions/types elsewhere)
        $this->academicSessions = AcademicSession::where('is_active', true)->get();
        $this->feeTypes = FeeType::where('is_active', true)->get();
        $this->classes = SchoolClass::where('is_active', true)->get();
    }

    public function edit($id)
    {
        $feeList = FeeList::findOrFail($id);

        $this->feeListId = $feeList->id;
        $this->academic_session_id = $feeList->academic_session_id;
        $this->fee_type_id = $feeList->fee_type_id;
        $this->school_class_id = $feeList->school_class_id;

        // populate sections after setting school_class_id so select shows correctly
        $this->sections = $this->school_class_id
            ? ClassSection::where('school_class_id', $this->school_class_id)->get()
            : collect();

        $this->class_section_id = $feeList->class_section_id;
        $this->name = $feeList->name;
        $this->amount = $feeList->amount;
        $this->due_date = $feeList->due_date ? $feeList->due_date->format('Y-m-d') : null;
        $this->is_active = (bool)$feeList->is_active;

        // ensure select bindings re-render properly
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => 'Loaded fee list for editing.'
        ]);
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        if ($this->confirmingDeleteId) {
            FeeList::findOrFail($this->confirmingDeleteId)->delete();
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Fee list deleted successfully.'
            ]);
            $this->confirmingDeleteId = null;
        }
    }

    public function resetForm()
    {
        $this->reset([
            'feeListId',
            'academic_session_id',
            'fee_type_id',
            'school_class_id',
            'class_section_id',
            'name',
            'amount',
            'due_date',
            'is_active',
        ]);
        $this->is_active = true;
        $this->sections = collect();
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
        $feeLists = FeeList::with(['academicSession', 'feeType', 'schoolClass', 'classSection'])
            ->when($this->search, function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhereHas('feeType', fn($q2) => $q2->where('name', 'like', "%{$this->search}%"))
                    ->orWhereHas('academicSession', fn($q2) => $q2->where('name', 'like', "%{$this->search}%"));
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.fee.list.index', [
            'feeLists' => $feeLists,
        ]);
    }
}
