<?php

namespace App\Livewire\Backend\Website\GoverningBody;

use App\Models\GoverningBody;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Manage extends Component
{
    use WithFileUploads;

    public $name, $designation, $rank = 0, $mobile, $type = 'current', $image, $new_image;
    public $member_id, $isEdit = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'rank' => 'required|integer',
            'new_image' => $this->isEdit ? 'nullable|image|max:1024' : 'required|image|max:1024',
        ];
    }

    public function save()
    {
        $this->validate();
        $imagePath = $this->image;

        if ($this->new_image) {
            if ($this->image) Storage::disk('public')->delete($this->image);
            $imagePath = $this->new_image->store('governing-body', 'public');
        }

        GoverningBody::updateOrCreate(['id' => $this->member_id], [
            'name' => $this->name,
            'designation' => $this->designation,
            'rank' => $this->rank,
            'mobile' => $this->mobile,
            'type' => $this->type,
            'image' => $imagePath,
        ]);

        $this->reset();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Member saved successfully!']);
    }

    public function edit($id)
    {
        $member = GoverningBody::findOrFail($id);
        $this->member_id = $member->id;
        $this->name = $member->name;
        $this->designation = $member->designation;
        $this->rank = $member->rank;
        $this->mobile = $member->mobile;
        $this->image = $member->image;
        $this->isEdit = true;
    }

    public function delete($id)
    {
        $member = GoverningBody::find($id);
        if ($member->image) Storage::disk('public')->delete($member->image);
        $member->delete();
    }

    public function render()
    {
        return view('livewire.backend.website.governing-body.manage', [
            'members' => GoverningBody::orderBy('rank', 'asc')->get()
        ]);
    }
}
