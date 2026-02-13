<?php

namespace App\Livewire\Backend\Website\Admission;

use App\Models\AdmissionInfo;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Manage extends Component
{
    use WithFileUploads;

    public $admission_id;
    public $content;
    public $form_path, $new_form;

    protected function rules()
    {
        return [
            'content' => 'required|string',
            'new_form' => 'nullable|mimes:pdf,doc,docx|max:5120', // 5MB limit
        ];
    }

    public function mount()
    {
        // Target the first record (ID 1)
        $info = AdmissionInfo::first();

        if ($info) {
            $this->admission_id = $info->id;
            $this->content = $info->content;
            $this->form_path = $info->form_path;
        }
    }

    public function save()
    {
        $this->validate();

        $filePath = $this->form_path;

        if ($this->new_form) {
            // Delete old file if exists
            if ($this->form_path) {
                Storage::disk('public')->delete($this->form_path);
            }
            $filePath = $this->new_form->store('admission-forms', 'public');
        }

        AdmissionInfo::updateOrCreate(
            ['id' => 1],
            [
                'content' => $this->content,
                'form_path' => $filePath,
            ]
        );

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Admission information updated successfully!',
        ]);

        $this->form_path = $filePath;
        $this->new_form = null;
    }

    public function render()
    {
        return view('livewire.backend.website.admission.manage');
    }
}
