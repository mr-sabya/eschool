<?php

namespace App\Livewire\Backend\Staff;

use App\Imports\StaffImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Import extends Component
{
    use WithFileUploads;

    public $import_file;

    protected $rules = [
        'import_file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
    ];

    public function importStaff()
    {
        $this->validate();

        Excel::import(new StaffImport, $this->import_file);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Staff imported successfully!',
        ]);

        $this->reset('import_file');
        $this->dispatch('staffSaved');
    }

    public function render()
    {
        return view('livewire.backend.staff.import');
    }
}
