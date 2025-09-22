<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $search;
    protected $classId;
    protected $sectionId;
    protected $departmentId;

    // REMOVED sortField and sortDirection from constructor
    public function __construct($search, $classId, $sectionId, $departmentId)
    {
        $this->search = $search;
        $this->classId = $classId;
        $this->sectionId = $sectionId;
        $this->departmentId = $departmentId;
    }

    /**
     * This is the exact query from your Index component, ensuring data matches.
     */
    public function query()
    {
        return User::query()
            ->with(['student.schoolClass', 'student.classSection', 'student.department'])
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
        // REMOVED ->orderBy(...) clause
    }

    /**
     * Defines the column headings for the export.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Roll Number',
            'Class',
            'Section',
            'Department',
            'Phone',
            'Gender',
            'Date of Birth',
        ];
    }

    /**
     * Maps the data for each row.
     *
     * @param User $user
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->student->roll_number ?? 'N/A',
            $user->student->schoolClass->name ?? 'N/A',
            $user->student->classSection->name ?? 'N/A',
            $user->student->department->name ?? 'N/A',
            $user->student->phone ?? 'N/A',
            $user->student->gender ?? 'N/A',
            $user->student->date_of_birth ?? 'N/A',
        ];
    }
}
