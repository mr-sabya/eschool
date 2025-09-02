<?php

namespace App\Livewire\Backend\Website\Banner;

use App\Models\Banner;
use Livewire\Component;
use Livewire\WithFileUploads;

class Manage extends Component
{
    use WithFileUploads;

    public $banner_id;

    public $title, $text, $order = 0, $is_active = true;
    public $image, $new_image;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'text' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'new_image' => 'nullable|image|max:2048', // 2MB limit
        ];
    }

    public function mount($bannerId = null)
    {
        if ($bannerId) {
            $banner = Banner::findOrFail($bannerId);

            $this->banner_id = $banner->id;
            $this->title = $banner->title;
            $this->text = $banner->text;
            $this->order = $banner->order;
            $this->is_active = $banner->is_active ? true : false;
            $this->image = $banner->image;
        }
    }

    public function save()
    {
        $this->validate();

        $imagePath = $this->image;

        if ($this->new_image) {
            $imagePath = $this->new_image->store('banners', 'public');
        }

        Banner::updateOrCreate(
            ['id' => $this->banner_id],
            [
                'title' => $this->title,
                'text' => $this->text,
                'order' => $this->order,
                'is_active' => (bool) $this->is_active,
                'image' => $imagePath,
            ]
        );

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $this->banner_id ? 'Banner updated successfully!' : 'Banner created successfully!',
        ]);

        $this->resetForm();
        return $this->redirect(route('admin.website.banner.index'), navigate: true);
    }

    public function resetForm()
    {
        $this->reset(['banner_id', 'title', 'text', 'order', 'is_active', 'image', 'new_image']);
        $this->is_active = true;
        $this->order = 0;
    }
    
    public function render()
    {
        return view('livewire.backend.website.banner.manage');
    }
}
