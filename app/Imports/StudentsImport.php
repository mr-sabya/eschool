<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    protected $classId;
    protected $sectionId;
    protected $sessionId;

    public function __construct($classId, $sectionId, $sessionId)
    {
        $this->classId = $classId;
        $this->sectionId = $sectionId;
        $this->sessionId = $sessionId;
    }

    public function model(array $row)
    {
        // Skip row if 'import' column is set to 'no' (case-insensitive)
        if (isset($row['import']) && strtolower(trim($row['import'])) === 'no') {
            return null;
        }

        $fullName = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));

        $user = User::firstOrCreate(
            ['email' => $row['email']],
            [
                'name' => $fullName,
                'password' => Hash::make($row['password'] ?? '12345678'),
            ]
        );

        return Student::updateOrCreate(
            ['user_id' => $user->id, 'academic_session_id' => $this->sessionId],
            [
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'roll_number' => $row['roll_number'] ?? null,
                'school_class_id' => $this->classId,
                'class_section_id' => $this->sectionId,
                'phone' => $row['phone'] ?? null,
                'address' => $row['address'] ?? null,
                'admission_date' => $row['admission_date'] ?? null,
                'admission_number' => $row['admission_number'] ?? null,
                'is_active' => true,
            ]
        );
    }
}
