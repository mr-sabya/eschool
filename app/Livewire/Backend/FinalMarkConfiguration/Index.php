<?php

namespace App\Livewire\Backend\FinalMarkConfiguration;

use App\Models\FinalMarkConfiguration;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

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
        FinalMarkConfiguration::findOrFail($this->confirmingDeleteId)->delete();
        $this->confirmingDeleteId = null;

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Final mark configuration deleted successfully.']);
        $this->resetPage();
    }

    public function render()
    {
        $query = FinalMarkConfiguration::with(['schoolClass', 'subject'])
            ->when($this->search, function ($q) {
                $q->whereHas('schoolClass', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('subject', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%'));
            });

        $configs = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.final-mark-configuration.index', [
            'configs' => $configs,
        ]);
    }
}
