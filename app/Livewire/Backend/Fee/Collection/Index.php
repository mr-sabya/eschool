<?php

namespace App\Livewire\Backend\Fee\Collection;

use App\Models\FeeCollection;
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
        $collections = FeeCollection::with(['student.user', 'feeList', 'schoolClass', 'classSection', 'paymentMethod'])
            ->whereHas('student.user', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('feeList', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.fee.collection.index', compact('collections'));
    }

}
