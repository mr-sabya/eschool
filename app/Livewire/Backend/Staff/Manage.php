<?php

namespace App\Livewire\Backend\Staff;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Gender;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Manage extends Component
{
    use WithFileUploads;

    public $staff_id, $user_id;

    // User fields (keep your original naming)
    public $name, $username, $password, $password_confirmation, $status = true, $role;

    // Staff fields
    public $staffUID, $department_id, $first_name, $last_name, $father_name, $mother_name, $email,
        $phone, $nid, $date_of_birth, $current_address, $permanent_address,
        $designation_id, $gender_id, $marital_status = 'single', $basic_salary,
        $date_of_joining, $profile_picture, $new_profile_picture;

    public $is_admin = false; // To mark staff users as admin
    public $is_staff = true;

    public $departments, $designations, $genders;

    protected function rules()
    {
        $staffId = $this->staff_id ?? 'NULL';
        $userId = $this->user_id ?? 'NULL';

        return [
            // User validations
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', Rule::unique('users', 'username')->ignore($userId)],
            'password' => $this->user_id ? 'nullable|confirmed|min:6' : 'required|confirmed|min:6',
            'status' => 'boolean',
            'role' => ['required', Rule::in(['admin', 'teacher', 'librarian', 'accountant'])],

            // Staff validations
            'staffUID' => ['nullable', Rule::unique('staff', 'staff_id')->ignore($staffId)],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('staff', 'email')->ignore($staffId)],
            'phone' => 'nullable|string|max:20',
            'nid' => ['nullable', 'string', 'max:50', Rule::unique('staff', 'nid')->ignore($staffId)],
            'date_of_birth' => 'nullable|date',
            'current_address' => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'gender_id' => 'nullable|exists:genders,id',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'basic_salary' => 'nullable|numeric',
            'date_of_joining' => 'nullable|date',
            'new_profile_picture' => 'nullable|image|max:2048',
        ];
    }

    public function mount($userId = null)
    {
        $this->departments = Department::all();
        $this->designations = Designation::all();
        $this->genders = Gender::all();

        // Defaults
        $this->status = true;
        $this->password = null;
        $this->password_confirmation = null;

        if ($userId) {
            $user = User::findOrFail($userId);

            $this->user_id = $user->id;
            $this->name = $user->name;
            $this->username = $user->username;
            $this->role = $user->role;
            $this->status = $user->status;
            $this->is_admin = $user->is_admin;

            $staff = Staff::where('user_id', $userId)->first();
            if ($staff) {
                $this->staff_id = $staff->id;
                // dd($this->staff_id);
                $this->staffUID = $staff->staff_id;
                $this->fill($staff->only([
                    'department_id',
                    'first_name',
                    'last_name',
                    'father_name',
                    'mother_name',
                    'email',
                    'phone',
                    'nid',
                    'date_of_birth',
                    'current_address',
                    'permanent_address',
                    'designation_id',
                    'gender_id',
                    'marital_status',
                    'basic_salary',
                    'date_of_joining',
                ]));

                $this->profile_picture = $staff->profile_picture;
            }
        }
    }

    public function save()
    {
        $this->validate();

        // Save or update User
        $userData = [
            'name' => $this->name,
            'username' => $this->username,
            'role' => $this->role,
            'status' => $this->status,
            'is_admin' => true, // Mark staff user as admin
        ];

        if (!$this->user_id || $this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->user_id], $userData);
        $this->user_id = $user->id;

        // Handle profile picture upload
        $profilePath = $this->profile_picture;
        if ($this->new_profile_picture) {
            $profilePath = $this->new_profile_picture->store('staff/profile', 'public');
        }

        Staff::updateOrCreate(
            ['id' => $this->staff_id],
            [
                'user_id' => $user->id,
                'staff_id' => $this->staffUID ?? 'STF-' . strtoupper(Str::random(6)),
                'department_id' => $this->department_id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'father_name' => $this->father_name,
                'mother_name' => $this->mother_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'nid' => $this->nid,
                'date_of_birth' => $this->date_of_birth,
                'current_address' => $this->current_address,
                'permanent_address' => $this->permanent_address,
                'designation_id' => $this->designation_id,
                'gender_id' => $this->gender_id,
                'marital_status' => $this->marital_status,
                'basic_salary' => $this->basic_salary,
                'date_of_joining' => $this->date_of_joining,
                'profile_picture' => $profilePath,
                'is_admin' => $this->is_admin,
                'is_staff' => true, // Always mark as staff
            ]
        );

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $this->staff_id ? 'Staff updated successfully!' : 'Staff added successfully!',
        ]);

        $this->resetForm();
        $this->dispatch('staffSaved');
        return $this->redirect(route('admin.staff.index'), navigate: true);
    }

    public function resetForm()
    {
        $this->reset();
        $this->status = true;
        $this->role = 'admin';
    }

    public function render()
    {
        return view('livewire.backend.staff.manage', [
            'departments' => $this->departments,
            'designations' => $this->designations,
            'genders' => $this->genders,
        ]);
    }
}
