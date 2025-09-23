<?php

namespace App\Livewire\Backend\Exam;

use App\Models\AcademicSession;
use App\Models\Exam;
use App\Models\ExamCategory;
use App\Models\MarkDistribution; // Import the MarkDistribution model
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $academic_session_id, $exam_category_id, $start_at, $end_at, $examId;

    // Property to hold the selected Mark Distribution IDs
    public $selectedMarkDistributions = [];

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    protected function rules()
    {
        return [
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'exam_category_id' => 'required|exists:exam_categories,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
            // Add validation for our new property
            'selectedMarkDistributions' => 'nullable|array',
            'selectedMarkDistributions.*' => 'exists:mark_distributions,id',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->examId) {
            $exam = Exam::findOrFail($this->examId);
            $exam->update($data);
            $message = 'Exam updated successfully.';
        } else {
            $exam = Exam::create($data);
            $message = 'Exam created successfully.';
        }

        // Sync the selected mark distributions with the pivot table
        // The sync() method is perfect for many-to-many relationships.
        // It automatically adds/removes records from the pivot table to match the given array.
        $exam->markDistributionTypes()->sync($this->selectedMarkDistributions);

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        // Eager load the relationship to improve performance
        $exam = Exam::with('markDistributionTypes')->findOrFail($id);
        $this->examId = $exam->id;
        $this->academic_session_id = $exam->academic_session_id;
        $this->exam_category_id = $exam->exam_category_id;
        $this->start_at = $exam->start_at;
        $this->end_at = $exam->end_at;

        // Populate the selectedMarkDistributions array with the IDs of the currently associated types
        $this->selectedMarkDistributions = $exam->markDistributionTypes->pluck('id')->toArray();
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        Exam::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Exam deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        // Add the new property to the reset list
        $this->reset(['academic_session_id', 'exam_category_id', 'start_at', 'end_at', 'examId', 'selectedMarkDistributions']);
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
        $exams = Exam::with(['academicSession', 'examCategory'])
            ->where(function ($query) {
                $query->whereHas('academicSession', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                    ->orWhereHas('examCategory', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.exam.index', [
            'exams' => $exams,
            'academicSessions' => AcademicSession::orderBy('name')->get(),
            'examCategories' => ExamCategory::orderBy('name')->get(),
            // Pass all available mark distribution types to the view
            'markDistributionTypes' => MarkDistribution::orderBy('name')->get(),
        ]);
    }
}
