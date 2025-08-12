<?php

namespace App\Livewire\Backend\SubjectMarkDistribution;

use App\Models\SubjectMarkDistribution;
use App\Models\SchoolClass;
use App\Models\ClassSection;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;

    public $confirmingDeleteId = null;

    // Filters
    public $filterClass = '';
    public $filterSection = '';
    public $filterDepartment = '';

    // Dynamic dropdown data
    public $sections = [];

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->sections = [];
    }

    // Called from wire:change on selects
    public function changeClass($value)
    {
        $this->filterClass = $value;
        $this->filterSection = '';
        $this->sections = $value
            ? ClassSection::where('school_class_id', $value)->orderBy('name')->get()
            : [];
        $this->resetPage();
    }

    public function changeSection($value)
    {
        $this->filterSection = $value;
        $this->resetPage();
    }

    public function changeDepartment($value)
    {
        $this->filterDepartment = $value;
        $this->resetPage();
    }

    public function changePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        SubjectMarkDistribution::findOrFail($this->confirmingDeleteId)->delete();
        $this->confirmingDeleteId = null;

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Subject mark distribution deleted successfully.'
        ]);
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->filterClass = '';
        $this->filterSection = '';
        $this->filterDepartment = '';
        $this->search = '';
        $this->sections = [];
        $this->resetPage();
    }

    public function render()
    {
        $query = SubjectMarkDistribution::with(['schoolClass', 'classSection', 'subject', 'markDistribution', 'department'])
            ->when($this->search, function ($q) {
                $q->whereHas('subject', function ($q2) {
                    $q2->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('schoolClass', function ($q2) {
                    $q2->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('classSection', function ($q2) {
                    $q2->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('markDistribution', function ($q2) {
                    $q2->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterClass, function ($q) {
                $q->where('school_class_id', $this->filterClass);
            })
            ->when($this->filterSection, function ($q) {
                $q->where('class_section_id', $this->filterSection);
            })
            ->when($this->filterDepartment, function ($q) {
                $q->where('department_id', $this->filterDepartment);
            });

        $distributions = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.subject-mark-distribution.index', [
            'distributions' => $distributions,
            'classes' => SchoolClass::orderBy('id')->get(),
            'departments' => Department::orderBy('id')->get(),
            'sections' => $this->sections
        ]);
    }
}
