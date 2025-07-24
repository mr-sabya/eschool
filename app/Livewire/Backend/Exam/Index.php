<?php

namespace App\Livewire\Backend\Exam;

use App\Models\AcademicSession;
use App\Models\Exam;
use App\Models\ExamCategory;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $academic_session_id, $exam_category_id, $start_at, $end_at, $examId;
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
            Exam::findOrFail($this->examId)->update($data);
            $message = 'Exam updated successfully.';
        } else {
            Exam::create($data);
            $message = 'Exam created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $exam = Exam::findOrFail($id);
        $this->examId = $exam->id;
        $this->academic_session_id = $exam->academic_session_id;
        $this->exam_category_id = $exam->exam_category_id;
        $this->start_at = $exam->start_at;
        $this->end_at = $exam->end_at;
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
        $this->reset(['academic_session_id', 'exam_category_id', 'start_at', 'end_at', 'examId']);
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
            ->whereHas('academicSession', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('examCategory', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.exam.index', [
            'exams' => $exams,
            'academicSessions' => AcademicSession::orderBy('name')->get(),
            'examCategories' => ExamCategory::orderBy('name')->get(),
        ]);
    }
}
