<?php

namespace App\Livewire\Backend\SubjectMarkDistribution;

use App\Models\SubjectMarkDistribution;
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

    protected $paginationTheme = 'bootstrap';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = ($this->sortDirection === 'asc') ? 'desc' : 'asc';
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

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Subject mark distribution deleted successfully.']);
        $this->resetPage();
    }

    public function render()
    {
        $query = SubjectMarkDistribution::with(['schoolClass', 'classSection', 'subject', 'markDistribution'])
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
            });

        $distributions = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.subject-mark-distribution.index', [
            'distributions' => $distributions,
        ]);
    }
}
