<?php

namespace App\Livewire\Frontend\AdmissionInfo;

use App\Models\AdmissionInfo;
use Livewire\Component;
use Illuminate\Support\Facades\Storage; // IMPORTANT: Must be here

class Index extends Component
{
    // The method that the button calls
    public function download()
    {
        $info = AdmissionInfo::first();

        if ($info && $info->form_path) {
            $exists = Storage::disk('public')->exists($info->form_path);

            if ($exists) {
                // This is the correct way to trigger a download in Livewire
                return Storage::download('public/' . $info->form_path);
            }
        }

        // Optional: Flash message if file not found
        session()->flash('error', 'ফাইলটি সার্ভারে খুঁজে পাওয়া যায়নি।');
    }

    public function render()
    {
        $admissionInfo = AdmissionInfo::first();

        return view('livewire.frontend.admission-info.index', [
            'info' => $admissionInfo,
        ]);
    }
}
