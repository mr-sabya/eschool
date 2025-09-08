<?php

namespace App\Livewire\Backend\Fee\Collection;

use Livewire\Component;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\ClassSection;
use App\Models\FeeList;
use App\Models\FeeCollection;
use App\Models\PaymentMethod;

class Create extends Component
{
    public $school_class_id, $class_section_id, $roll_number;
    public $students = [];
    public $sections = []; // add this property
    public $selectedStudent;

    // Payment form fields
    public $fee_list_id, $amount_paid, $discount = 0, $fine = 0, $balance = 0, $payment_date, $payment_method_id, $transaction_no, $note;

    public function classChanged($classId)
    {
        $this->school_class_id = $classId;
        $this->class_section_id = null;
        $this->students = [];

        // Load sections related to the selected class
        $this->sections = ClassSection::where('school_class_id', $classId)->get();
    }

    // Custom function triggered from wire:change on section select
    public function sectionChanged($sectionId)
    {
        $this->class_section_id = $sectionId;
        $this->students = [];
    }

    public function loadStudents()
    {
        $query = Student::with('user');

        if ($this->school_class_id) $query->where('school_class_id', $this->school_class_id);
        if ($this->class_section_id) $query->where('class_section_id', $this->class_section_id);
        if ($this->roll_number) $query->where('roll_number', 'like', '%' . $this->roll_number . '%');

        $this->students = $query->get();
    }

    public function selectStudent($studentId)
    {
        $this->selectedStudent = Student::with('user')->findOrFail($studentId);
        $this->fee_list_id = null;
        $this->amount_paid = null;
        $this->discount = 0;
        $this->fine = 0;
        $this->balance = 0;
        $this->payment_date = now()->format('Y-m-d');
        $this->payment_method_id = null;
        $this->transaction_no = null;
        $this->note = null;
    }

    public function savePayment()
    {
        $this->validate([
            'fee_list_id' => 'required|exists:fee_lists,id',
            'amount_paid' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'fine' => 'nullable|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $feeList = FeeList::findOrFail($this->fee_list_id);
        $balance = $feeList->amount - $this->amount_paid - $this->discount + $this->fine;

        FeeCollection::create([
            'student_id' => $this->selectedStudent->id,
            'fee_list_id' => $this->fee_list_id,
            'school_class_id' => $this->selectedStudent->school_class_id,
            'class_section_id' => $this->selectedStudent->class_section_id,
            'amount_paid' => $this->amount_paid,
            'discount' => $this->discount,
            'fine' => $this->fine,
            'balance' => $balance,
            'payment_date' => $this->payment_date,
            'payment_method_id' => $this->payment_method_id,
            'transaction_no' => $this->transaction_no,
            'note' => $this->note,
        ]);

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Payment collected successfully!']);
        $this->selectedStudent = null;
        $this->students = [];
        $this->school_class_id = $this->class_section_id = $this->roll_number = null;
    }

    public function render()
    {
        $classes = SchoolClass::all();
        $sections = $this->school_class_id ? ClassSection::where('school_class_id', $this->school_class_id)->get() : [];
        $paymentMethods = PaymentMethod::all();
        $feeLists = $this->selectedStudent ? FeeList::where('school_class_id', $this->selectedStudent->school_class_id)->get() : [];

        return view('livewire.backend.fee.collection.create', compact('classes', 'sections', 'paymentMethods', 'feeLists'));
    }
}
