<?php

namespace App\Livewire\Backend\Guardian;


use App\Models\Guardian;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Manage extends Component
{
    use WithFileUploads;

    public $guardian_id, $user_id;

    // User fields
    public $name, $email, $password, $password_confirmation;
    public $status = true;

    // Guardian fields
    public $phone, $address, $date_of_birth, $occupation, $national_id, $place_of_birth;
    public $nationality, $language, $profile_picture, $new_profile_picture, $notes;

    protected function rules()
    {
        return [
            // User validations
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user_id)],
            'password' => $this->user_id ? 'nullable|confirmed|min:6' : 'required|confirmed|min:6',

            // Guardian validations
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'occupation' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:50',
            'place_of_birth' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:100',
            'language' => 'nullable|string|max:100',
            'new_profile_picture' => 'nullable|image|max:2048',
            'status' => 'boolean',
            'notes' => 'nullable|string',
        ];
    }

    public function mount($userId = null)
    {
        if ($userId) {
            // Get user with guardian relation
            $user = User::with('guardian')->findOrFail($userId);

            $guardian = $user->guardian;

            $this->user_id = $user->id;
            $this->guardian_id = $guardian?->id;

            // User data
            $this->name = $user->name;
            $this->email = $user->email;
            $this->status = $user->status ? true : false;

            if ($guardian) {
                // Guardian data
                $this->phone = $guardian->phone;
                $this->address = $guardian->address;
                $this->date_of_birth = $guardian->date_of_birth;
                $this->occupation = $guardian->occupation;
                $this->national_id = $guardian->national_id;
                $this->place_of_birth = $guardian->place_of_birth;
                $this->nationality = $guardian->nationality;
                $this->language = $guardian->language;
                $this->profile_picture = $guardian->profile_picture;
                $this->notes = $guardian->notes;
            }
        }
    }

    public function save()
    {
        $this->validate();

        // Create or update User
        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status ? 1 : 0,
            'is_parent' => 1, // Assuming guardian is a parent
        ];

        if (!$this->user_id || $this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->user_id], $userData);
        $this->user_id = $user->id;

        // Handle profile picture
        $profilePath = $this->profile_picture;
        if ($this->new_profile_picture) {
            $profilePath = $this->new_profile_picture->store('guardians/profile', 'public');
        }

        Guardian::updateOrCreate(
            ['id' => $this->guardian_id],
            [
                'user_id' => $this->user_id,
                'phone' => $this->phone,
                'address' => $this->address,
                'date_of_birth' => $this->date_of_birth,
                'occupation' => $this->occupation,
                'national_id' => $this->national_id,
                'place_of_birth' => $this->place_of_birth,
                'nationality' => $this->nationality,
                'language' => $this->language,
                'profile_picture' => $profilePath,
                'notes' => $this->notes,
            ]
        );

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $this->guardian_id ? 'Guardian updated successfully!' : 'Guardian added successfully!',
        ]);

        $this->resetForm();
        // 
        // redirect to guardian index
        return $this->redirect(route('admin.guardian.index'), navigate: true);
    }

    public function resetForm()
    {
        $this->reset();
        $this->status = true;
    }

    public function render()
    {
        return view('livewire.backend.guardian.manage');
    }
}
