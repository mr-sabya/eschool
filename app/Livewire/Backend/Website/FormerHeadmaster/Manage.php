<?php

namespace App\Livewire\Backend\Website\FormerHeadmaster;

use App\Models\FormerHeadmaster;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Manage extends Component
{
    use WithFileUploads;

    public $name, $qualification, $joining_date, $leaving_date, $rank = 0, $image, $new_image, $headmaster_id;
    public $isEdit = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'joining_date' => 'nullable|date',
            'leaving_date' => 'nullable|date',
            'new_image' => $this->isEdit ? 'nullable|image|max:1024' : 'required|image|max:1024',
        ];
    }

    public function save()
    {
        $this->validate();
        $imagePath = $this->image;

        if ($this->new_image) {
            if ($this->image) Storage::disk('public')->delete($this->image);
            $imagePath = $this->new_image->store('headmasters', 'public');
        }

        FormerHeadmaster::updateOrCreate(['id' => $this->headmaster_id], [
            'name' => $this->name,
            'qualification' => $this->qualification,
            'joining_date' => $this->joining_date,
            'leaving_date' => $this->leaving_date,
            'rank' => $this->rank,
            'image' => $imagePath,
        ]);

        $this->reset();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'তথ্য সংরক্ষিত হয়েছে!']);
    }

    public function edit($id)
    {
        $data = FormerHeadmaster::findOrFail($id);
        $this->headmaster_id = $data->id;
        $this->name = $data->name;
        $this->qualification = $data->qualification;
        $this->joining_date = $data->joining_date?->format('Y-m-d');
        $this->leaving_date = $data->leaving_date?->format('Y-m-d');
        $this->rank = $data->rank;
        $this->image = $data->image;
        $this->isEdit = true;
    }

    public function delete($id)
    {
        $data = FormerHeadmaster::find($id);
        if ($data->image) Storage::disk('public')->delete($data->image);
        $data->delete();
    }

    public function render()
    {
        return view('livewire.backend.website.former-headmaster.manage', [
            'headmasters' => FormerHeadmaster::orderBy('rank', 'asc')->get()
        ]);
    }
}
