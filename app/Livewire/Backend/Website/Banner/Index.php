<?php

namespace App\Livewire\Backend\Website\Banner;

use App\Models\Banner;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $confirmingDelete = false;
    public $deleteId = null;

    protected $listeners = ['banner-saved' => '$refresh'];

    public function updatingSearch()
    {
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
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        if ($this->deleteId) {
            $banner = Banner::findOrFail($this->deleteId);

            if ($banner->image && file_exists(storage_path('app/public/' . $banner->image))) {
                unlink(storage_path('app/public/' . $banner->image));
            }

            $banner->delete();

            $this->dispatch('notify', 'Banner deleted successfully!');
            $this->confirmingDelete = false;
            $this->deleteId = null;
        }
    }

    public function render()
    {
        $banners = Banner::query()
            ->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('text', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.website.banner.index', [
            'banners' => $banners,
        ]);
    }

}
