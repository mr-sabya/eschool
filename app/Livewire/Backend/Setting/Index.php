<?php

namespace App\Livewire\Backend\Setting;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    public $setting;

    public $school_name;
    public $school_address;
    public $school_email;
    public $school_phone;
    public $school_history;
    public $eiin_no;
    public $school_code;
    public $registration_no;
    public $timezone;
    public $copyright;

    // For images
    public $logo;           // current logo path from DB
    public $favicon;        // current favicon path from DB

    public $new_logo;       // temporary uploaded file for logo
    public $new_favicon;    // temporary uploaded file for favicon

    public function mount()
    {
        $this->setting = Setting::find(1);

        if ($this->setting) {
            $this->school_name = $this->setting->school_name;
            $this->school_address = $this->setting->school_address;
            $this->school_email = $this->setting->school_email;
            $this->school_phone = $this->setting->school_phone;
            $this->school_history = $this->setting->school_history;
            $this->eiin_no = $this->setting->eiin_no;
            $this->school_code = $this->setting->school_code;
            $this->registration_no = $this->setting->registration_no;
            $this->timezone = $this->setting->timezone;
            $this->copyright = $this->setting->copyright;

            $this->logo = $this->setting->logo;
            $this->favicon = $this->setting->favicon;
        } else {
            $this->setting = new Setting();
        }
    }

    protected $rules = [
        'school_name' => 'required|string|max:255',
        'school_email' => 'nullable|email|max:255',
        'school_phone' => 'nullable|string|max:50',
        'school_address' => 'nullable|string',
        'school_history' => 'nullable|string',
        'eiin_no' => 'nullable|string|max:50',
        'school_code' => 'nullable|string|max:50',
        'registration_no' => 'nullable|string|max:50',
        'timezone' => 'required|string|max:50',
        'new_logo' => 'nullable|image|max:2048',     // max 2MB
        'new_favicon' => 'nullable|image|max:2048',
    ];

    public function updatedNewLogo()
    {
        $this->validateOnly('new_logo');
    }

    public function updatedNewFavicon()
    {
        $this->validateOnly('new_favicon');
    }

    public function save()
    {
        $this->validate();

        if (!$this->setting->exists) {
            $this->setting = new Setting();
            $this->setting->id = 1; // force ID 1 if needed
        }

        $this->setting->school_name = $this->school_name;
        $this->setting->school_address = $this->school_address;
        $this->setting->school_email = $this->school_email;
        $this->setting->school_phone = $this->school_phone;
        $this->setting->school_history = $this->school_history;
        $this->setting->eiin_no = $this->eiin_no;
        $this->setting->school_code = $this->school_code;
        $this->setting->registration_no = $this->registration_no;
        $this->setting->timezone = $this->timezone;
        $this->setting->copyright = $this->copyright;

        // Handle logo upload
        if ($this->new_logo) {
            // Delete old logo if exists
            if ($this->setting->logo && Storage::disk('public')->exists($this->setting->logo)) {
                Storage::disk('public')->delete($this->setting->logo);
            }

            $path = $this->new_logo->store('settings', 'public');
            $this->setting->logo = $path;
            $this->logo = $path; // update current logo path
            $this->new_logo = null; // reset uploaded file
        }

        // Handle favicon upload
        if ($this->new_favicon) {
            // Delete old favicon if exists
            if ($this->setting->favicon && Storage::disk('public')->exists($this->setting->favicon)) {
                Storage::disk('public')->delete($this->setting->favicon);
            }

            $path = $this->new_favicon->store('settings', 'public');
            $this->setting->favicon = $path;
            $this->favicon = $path;
            $this->new_favicon = null;
        }

        $this->setting->save();

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Setting has been updated successfully.']);
    }

    public function render()
    {
        return view('livewire.backend.setting.index');
    }
}
