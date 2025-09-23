<?php

namespace App\Livewire\Backend\FinalMarkConfiguration;

use App\Models\FinalMarkConfiguration;
use App\Models\SchoolClass;
use App\Models\Department;
use App\Models\Exam;
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

    // Filters
    public $filterClass = '';
    public $filterDepartment = '';
    public $filterExam = '';

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

    public function changeExam($value)
    {
        $this->filterExam = $value;
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
        $this->filterExam = '';
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

    /**
     * Gathers filtered data and redirects to the create page for duplication.
     */
    public function duplicate()
    {
        $query = FinalMarkConfiguration::query()
            ->when($this->search, function ($q) {
                $q->whereHas('schoolClass', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('subject', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%'));
            })
            ->when($this->filterClass, fn($q) => $q->where('school_class_id', $this->filterClass))
            ->when($this->filterDepartment, fn($q) => $q->where('department_id', $this->filterDepartment))
            ->when($this->filterExam, fn($q) => $q->where('exam_id', $this->filterExam));

        $configsToDuplicate = $query->get();

        if ($configsToDuplicate->isEmpty()) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'No records to duplicate.']);
            return;
        }

        $firstConfig = $configsToDuplicate->first();

        $duplicateData = [
            'school_class_id' => $firstConfig->school_class_id,
            'department_id' => $firstConfig->department_id,
            'rows' => $configsToDuplicate->map(function ($config) {
                return [
                    'subject_id' => $config->subject_id,
                    'class_test_total' => $config->class_test_total,
                    'other_parts_total' => $config->other_parts_total,
                    'final_result_weight_percentage' => $config->final_result_weight_percentage,
                    'grading_scale' => $config->grading_scale,
                    'exclude_from_gpa' => (bool)$config->exclude_from_gpa,
                ];
            })->toArray(),
        ];

        // We use session flash to handle larger datasets that might exceed URL length limits.
        session()->flash('duplicate_data', $duplicateData);

        return $this->redirect(route('admin.final-mark-configuration.create'), navigate: true);
    }


    public function render()
    {
        $query = FinalMarkConfiguration::with(['schoolClass', 'subject', 'department', 'exam'])
            ->when($this->search, function ($q) {
                $q->whereHas('schoolClass', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('subject', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%'));
            })
            ->when($this->filterClass, fn($q) => $q->where('school_class_id', $this->filterClass))
            ->when($this->filterDepartment, fn($q) => $q->where('department_id', $this->filterDepartment))
            ->when($this->filterExam, fn($q) => $q->where('exam_id', $this->filterExam));

        $showResults = $this->filterClass || $this->filterDepartment || $this->filterExam || $this->search;

        $configs = $showResults
            ? $query->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage)
            : collect([]); // empty collection

        return view('livewire.backend.final-mark-configuration.index', [
            'configs' => $configs,
            'classes' => SchoolClass::orderBy('id')->get(),
            'departments' => Department::orderBy('id')->get(),
            'exams' => Exam::orderBy('id')->get(),
            'showResults' => $showResults,
        ]);
    }
}
