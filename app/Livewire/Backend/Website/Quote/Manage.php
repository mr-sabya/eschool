<?php

namespace App\Livewire\Backend\Website\Quote;

use App\Models\Quote;
use Livewire\Component;
use Livewire\WithFileUploads;

class Manage extends Component
{
    use WithFileUploads;

    public $quote_id, $name, $designation, $institution, $location, $message, $order = 0, $is_active = true;
    public $image, $new_image;

    public function render()
    {
        return view('livewire.backend.website.quote.manage', [
            'quotes' => Quote::orderBy('order', 'asc')->get()
        ]);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'designation' => 'required',
            'message' => 'required',
            'new_image' => 'nullable|image|max:1024',
        ]);

        $imagePath = $this->image;
        if ($this->new_image) {
            $imagePath = $this->new_image->store('quotes', 'public');
        }

        Quote::updateOrCreate(['id' => $this->quote_id], [
            'name' => $this->name,
            'designation' => $this->designation,
            'institution' => $this->institution,
            'location' => $this->location,
            'message' => $this->message,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'image' => $imagePath,
        ]);

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Saved!']);
        $this->resetForm();
    }

    public function edit($id)
    {
        $quote = Quote::findOrFail($id);
        $this->quote_id = $quote->id;
        $this->name = $quote->name;
        $this->designation = $quote->designation;
        $this->institution = $quote->institution;
        $this->location = $quote->location;
        $this->message = $quote->message;
        $this->order = $quote->order;
        $this->is_active = $quote->is_active;
        $this->image = $quote->image;
    }

    public function delete($id)
    {
        Quote::find($id)->delete();
    }

    public function resetForm()
    {
        $this->reset();
        $this->is_active = true;
    }
}
