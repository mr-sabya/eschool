<?php

namespace App\Livewire\Backend\MarkDistribution;

use App\Models\MarkDistribution;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $name, $markDistributionId;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;

    public $confirmingDeleteId = null;

    protected function rules()
    {
        $id = $this->markDistributionId ?? 'NULL';

        return [
            'name' => 'required|unique:mark_distributions,name,' . $id,
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->markDistributionId) {
            $markDistribution = MarkDistribution::findOrFail($this->markDistributionId);
            $markDistribution->update($data);
            $message = 'Mark distribution updated successfully.';
        } else {
            MarkDistribution::create($data);
            $message = 'Mark distribution created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $markDistribution = MarkDistribution::findOrFail($id);
        $this->markDistributionId = $markDistribution->id;
        $this->name = $markDistribution->name;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        MarkDistribution::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Mark distribution deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['name', 'markDistributionId']);
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
        $distributions = MarkDistribution::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.mark-distribution.index', [
            'distributions' => $distributions,
        ]);
    }

}
