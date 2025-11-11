<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SeatPlanExport implements FromQuery, WithHeadings, WithMapping
{
    protected $search;
    protected $classId;
    protected $sectionId;
    protected $departmentId;

    public function __construct($search, $classId, $sectionId, $departmentId)
    {
        $this->search = $search;
        $this->classId = $classId;
        $this->sectionId = $sectionId;
        $this->departmentId = $departmentId;
    }

    /**
     * This query uses the same filters as your main student list
     * to ensure the exported data matches what is being displayed.
     */
    public function query()
    {
        return User::query()
            ->with(['student']) // We only need the student relationship for roll_number
            ->whereHas('student', function ($query) {
                $query->when($this->classId, fn($q) => $q->where('school_class_id', $this->classId))
                    ->when($this->sectionId, fn($q) => $q->where('class_section_id', $this->sectionId))
                    ->when($this->departmentId, fn($q) => $q->where('department_id', $this->departmentId));
            })
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhereHas('student', function ($query) {
                        $query->where('roll_number', 'like', "%{$this->search}%")
                            ->orWhere('phone', 'like', "%{$this->search}%");
                    });
            });
    }

    /**
     * Defines the column headings for the seat plan.
     */
    public function headings(): array
    {
        return [
            'Roll Number',
            'Name',
        ];
    }

    /**
     * Maps only the roll number and name for each student.
     *
     * @param User $user
     */
    public function map($user): array
    {
        return [
            $user->student->roll_number ?? 'N/A',
            $user->name,
        ];
    }
}
