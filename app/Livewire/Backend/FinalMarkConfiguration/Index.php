<?php

namespace App\Livewire\Backend\FinalMarkConfiguration;

use App\Models\FinalMarkConfiguration;
use App\Models\SchoolClass;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Filters
    public $filterClass = '';
    public $filterDepartment = '';

    public $confirmingDeleteId = null;

    protected $paginationTheme = 'bootstrap';

    public function changeClass($value)
    {
        $this->filterClass = $value;
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

    public function resetFilters()
    {
        $this->filterClass = '';
        $this->filterDepartment = '';
        $this->search = '';
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        FinalMarkConfiguration::findOrFail($this->confirmingDeleteId)->delete();
        $this->confirmingDeleteId = null;

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Final mark configuration deleted successfully.'
        ]);
        $this->resetPage();
    }

    public function render()
    {
        $query = FinalMarkConfiguration::with(['schoolClass', 'subject', 'department'])
            ->when($this->search, function ($q) {
                $q->whereHas('schoolClass', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('subject', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%'));
            })
            ->when($this->filterClass, fn($q) => $q->where('school_class_id', $this->filterClass))
            ->when($this->filterDepartment, fn($q) => $q->where('department_id', $this->filterDepartment));

        $configs = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.final-mark-configuration.index', [
            'configs' => $configs,
            'classes' => SchoolClass::orderBy('id')->get(),
            'departments' => Department::orderBy('id')->get(),
        ]);
    }
}
