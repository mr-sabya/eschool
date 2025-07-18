<?php

namespace App\Livewire\Backend\Student;

use App\Models\Blood;
use App\Models\ClassSection;
use App\Models\Gender;
use App\Models\Religion;
use App\Models\SchoolClass;
use App\Models\Shift;
use App\Models\Student;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class Manage extends Component
{
    use WithFileUploads;

    public $student_id, $user_id;

    // User fields
    public $name, $email, $password;

    // Student fields
    public $first_name, $last_name, $roll_number, $school_class_id, $class_section_id, $shift_id;
    public $guardian_id, $phone, $address, $date_of_birth, $admission_date, $admission_number;
    public $category, $gender_id, $blood_id, $religion_id, $national_id, $place_of_birth;
    public $nationality, $language, $health_status, $rank_in_family, $number_of_siblings;
    public $profile_picture, $new_profile_picture;
    public $emergency_contact_name, $emergency_contact_phone;
    public $previous_school_attended = false, $previous_school, $previous_school_document, $new_previous_school_document;
    public $is_active = true, $notes;

    protected $listeners = ['edit' => 'edit'];

    protected function rules()
    {
        return [
            // User validations
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . ($this->user_id ?? 'NULL') . ',id',
            'password' => $this->user_id ? 'nullable|min:6' : 'required|min:6',

            // Student validations
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'roll_number' => 'nullable|unique:students,roll_number,' . ($this->student_id ?? 'NULL') . ',id',
            'school_class_id' => 'required|exists:school_classes,id',
            'class_section_id' => 'nullable|exists:class_sections,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'guardian_id' => 'nullable|exists:users,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'admission_date' => 'nullable|date',
            'admission_number' => 'nullable|unique:students,admission_number,' . ($this->student_id ?? 'NULL') . ',id',
            'category' => 'nullable|string|max:100',
            'gender_id' => 'nullable|exists:genders,id',
            'blood_id' => 'nullable|exists:bloods,id',
            'religion_id' => 'nullable|exists:religions,id',
            'national_id' => 'nullable|string|max:50',
            'place_of_birth' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:100',
            'language' => 'nullable|string|max:100',
            'health_status' => 'nullable|string',
            'rank_in_family' => 'nullable|integer',
            'number_of_siblings' => 'nullable|integer',
            'new_profile_picture' => 'nullable|image|max:2048', // 2MB max
            'new_previous_school_document' => 'nullable|file|max:4096', // 4MB max
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'previous_school_attended' => 'boolean',
            'previous_school' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ];
    }

    public function mount($studentId = null)
    {
        if ($studentId) {
            $student = Student::with('user')->findOrFail($studentId);

            $this->student_id = $student->id;
            $this->user_id = $student->user_id;

            // User data
            $this->name = $student->user->name;
            $this->email = $student->user->email;

            // Student data
            $this->first_name = $student->first_name;
            $this->last_name = $student->last_name;
            $this->roll_number = $student->roll_number;
            $this->school_class_id = $student->school_class_id;
            $this->class_section_id = $student->class_section_id;
            $this->shift_id = $student->shift_id;
            $this->guardian_id = $student->guardian_id;
            $this->phone = $student->phone;
            $this->address = $student->address;
            $this->date_of_birth = $student->date_of_birth?->format('Y-m-d');
            $this->admission_date = $student->admission_date?->format('Y-m-d');
            $this->admission_number = $student->admission_number;
            $this->category = $student->category;
            $this->gender_id = $student->gender_id;
            $this->blood_id = $student->blood_id;
            $this->religion_id = $student->religion_id;
            $this->national_id = $student->national_id;
            $this->place_of_birth = $student->place_of_birth;
            $this->nationality = $student->nationality;
            $this->language = $student->language;
            $this->health_status = $student->health_status;
            $this->rank_in_family = $student->rank_in_family;
            $this->number_of_siblings = $student->number_of_siblings;
            $this->profile_picture = $student->profile_picture;
            $this->emergency_contact_name = $student->emergency_contact_name;
            $this->emergency_contact_phone = $student->emergency_contact_phone;
            $this->previous_school_attended = $student->previous_school_attended;
            $this->previous_school = $student->previous_school;
            $this->previous_school_document = $student->previous_school_document;
            $this->is_active = $student->is_active;
            $this->notes = $student->notes;
        }
    }

    public function save()
    {
        $this->validate();

        // ✅ Create or update User
        $userData = [
            'name' => $this->name,
            'email' => $this->email,
        ];
        if (!$this->user_id || $this->password) {
            $userData['password'] = bcrypt($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->user_id], $userData);

        // ✅ Handle Profile Picture
        $profilePath = $this->profile_picture;
        if ($this->new_profile_picture) {
            $profilePath = $this->new_profile_picture->store('students/profile', 'public');
        }

        // ✅ Handle Previous School Document
        $previousSchoolDocPath = $this->previous_school_document;
        if ($this->previous_school_attended && $this->new_previous_school_document) {
            $previousSchoolDocPath = $this->new_previous_school_document->store('students/previous_school', 'public');
        }

        // ✅ Create or Update Student
        Student::updateOrCreate(
            ['id' => $this->student_id],
            [
                'user_id' => $user->id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'roll_number' => $this->roll_number,
                'school_class_id' => $this->school_class_id,
                'class_section_id' => $this->class_section_id,
                'shift_id' => $this->shift_id,
                'guardian_id' => $this->guardian_id,
                'phone' => $this->phone,
                'address' => $this->address,
                'date_of_birth' => $this->date_of_birth,
                'admission_date' => $this->admission_date,
                'admission_number' => $this->admission_number,
                'category' => $this->category,
                'gender_id' => $this->gender_id,
                'blood_id' => $this->blood_id,
                'religion_id' => $this->religion_id,
                'national_id' => $this->national_id,
                'place_of_birth' => $this->place_of_birth,
                'nationality' => $this->nationality,
                'language' => $this->language,
                'health_status' => $this->health_status,
                'rank_in_family' => $this->rank_in_family,
                'number_of_siblings' => $this->number_of_siblings,
                'profile_picture' => $profilePath,
                'emergency_contact_name' => $this->emergency_contact_name,
                'emergency_contact_phone' => $this->emergency_contact_phone,
                'previous_school_attended' => (bool) $this->previous_school_attended,
                'previous_school' => $this->previous_school,
                'previous_school_document' => $previousSchoolDocPath,
                'is_active' => (bool) $this->is_active,
                'notes' => $this->notes,
            ]
        );

        $this->dispatch('notify', $this->student_id ? 'Student updated successfully!' : 'Student added successfully!');
        $this->resetForm();
        $this->dispatch('student-saved');
    }

    public function resetForm()
    {
        $this->reset();
        $this->is_active = true;
        $this->previous_school_attended = false;
    }

    public function render()
    {
        return view('livewire.backend.student.manage', [
            'classes' => SchoolClass::all(),
            'sections' => ClassSection::all(),
            'shifts' => Shift::all(),
            'genders' => Gender::all(),
            'bloods' => Blood::all(),
            'religions' => Religion::all(),
            'guardians' => User::where('is_parent', true)->get(),
        ]);
    }
}
