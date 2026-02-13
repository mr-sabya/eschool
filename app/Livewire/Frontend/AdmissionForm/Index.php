<?php

namespace App\Livewire\Frontend\AdmissionForm;

use App\Models\AdmissionInfo;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    public function download()
    {
        $info = AdmissionInfo::first();

        if ($info && $info->form_path && Storage::disk('public')->exists($info->form_path)) {
            return Storage::download('public/' . $info->form_path);
        }

        $this->dispatch('notify', [
            'type' => 'error',
            'message' => 'দুঃখিত, বর্তমানে ডাউনলোড করার মতো কোনো ফরম খুঁজে পাওয়া যায়নি।',
        ]);
    }

    public function render()
    {
        return view('livewire.frontend.admission-form.index', [
            'hasForm' => AdmissionInfo::whereNotNull('form_path')->exists()
        ]);
    }
}
