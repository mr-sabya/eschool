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

        $name = $row['name'] ?? 'Unknown';
        $baseUsername = strtolower(str_replace(' ', '', explode(' ', $name)[0])); // first part of name, lowercase

        // Generate a unique username if not provided
        $username = $row['username'] ?? $this->generateUniqueUsername($baseUsername);

        $user = User::firstOrCreate(
            ['username' => $username],
            [
                'name' => $name,
                'password' => Hash::make($row['password'] ?? '12345678'),
            ]
        );

        return Student::updateOrCreate(
            ['user_id' => $user->id, 'academic_session_id' => $this->sessionId],
            [
                'roll_number' => $row['roll_number'] ?? null,
                'school_class_id' => $this->classId,
                'class_section_id' => $this->sectionId,
                'is_active' => true,
            ]
        );
    }

    protected function generateUniqueUsername($base)
    {
        $username = $base;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $base . $counter;
            $counter++;
        }

        return $username;
    }
}
