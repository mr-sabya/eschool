<?php

namespace App\Livewire\Backend\Library\Member;

use App\Models\ClassSection;
use Livewire\Component;
use App\Models\LibraryMember;
use App\Models\User;
use App\Models\MemberCategory;
use App\Models\SchoolClass;

class Manage extends Component
{
    public $memberId;
    public $member_id;
    public $member_category_id;
    public $user_type;
    public $user_id;
    public $class_id;
    public $section_id;
    public $join_date;
    public $expire_date;
    public $status = true;

    public $users = [];
    public $classes = [];
    public $sections = [];
    public $students = [];
    public $member_categories = [];

    protected function rules()
    {
        return [
            'member_id' => 'required|string|unique:library_members,member_id,' . $this->memberId,
            'member_category_id' => 'required|exists:member_categories,id',
            'user_type' => 'required|in:student,teacher',
            'user_id' => 'required|exists:users,id',
            'join_date' => 'required|date',
            'expire_date' => 'required|date|after_or_equal:join_date',
            'status' => 'required|boolean',
        ];
    }

    public function mount($memberId = null)
    {
        $this->memberId = $memberId;
        $this->classes = SchoolClass::all();
        $this->member_categories = MemberCategory::all();

        if ($this->memberId) {
            $member = LibraryMember::findOrFail($this->memberId);
            $this->member_id = $member->member_id;
            $this->member_category_id = $member->member_category_id;
            $this->user_type = $member->user_type;
            $this->user_id = $member->user_id;
            $this->join_date = $member->join_date;
            $this->expire_date = $member->expire_date;
            $this->status = $member->status ? true : false;

            // Preload class/section/student if student type
            if ($member->user_type === 'student') {
                $student = User::find($member->user_id)->student;
                if ($student) {
                    $this->class_id = $student->school_class_id;
                    $this->section_id = $student->class_section_id;
                    $this->loadSections();
                    $this->loadStudents();
                }
            }
        }
    }

    // Generate unique member ID
    public function generateMemberId()
    {
        do {
            $uniqueId = 'LIB-' . rand(100000, 999999);
        } while (LibraryMember::where('member_id', $uniqueId)->exists());

        $this->member_id = $uniqueId;
    }

    public function userTypeChanged($value)
    {
        $this->user_id = null;
        $this->class_id = null;
        $this->section_id = null;
        $this->students = [];
        $this->users = [];

        if ($value === 'teacher') {
            $this->users = User::where('role', 'teacher')->where('is_admin', 1)->get();
        }
    }

    public function classChanged($value)
    {
        $this->section_id = null;
        $this->students = [];
        $this->loadSections();
    }

    public function sectionChanged($value)
    {
        $this->user_id = null;
        $this->loadStudents();
    }

    private function loadSections()
    {
        if ($this->class_id) {
            $this->sections = ClassSection::where('school_class_id', $this->class_id)->get();
        } else {
            $this->sections = [];
        }
    }

    private function loadStudents()
    {
        if ($this->class_id && $this->section_id) {
            $this->students = User::whereHas('student', function ($q) {
                $q->where('school_class_id', $this->class_id)
                    ->where('class_section_id', $this->section_id);
            })->get();
        } else {
            $this->students = [];
        }
    }

    public function save()
    {
        $data = $this->validate();
        $data['status'] = $this->status ? 1 : 0;

        if ($this->memberId) {
            LibraryMember::findOrFail($this->memberId)->update($data);
            $message = "Member updated successfully.";
        } else {
            LibraryMember::create($data);
            $message = "Member created successfully.";
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'member_id',
            'member_category_id',
            'user_type',
            'user_id',
            'class_id',
            'section_id',
            'join_date',
            'expire_date',
            'status',
            'students',
            'users',
            'sections',
        ]);
    }

    public function render()
    {
        return view('livewire.backend.library.member.manage');
    }
}
