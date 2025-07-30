<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StaffImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Skip rows that shouldn't be imported or have no email
        if (strtolower($row['import'] ?? 'yes') !== 'yes' || empty($row['email'])) {
            return null;
        }

        // Create full name from first_name + last_name
        $fullName = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));

        // Create or find User
        $user = User::updateOrCreate(
            ['email' => $row['email']],
            [
                'name' => $fullName,
                'password' => Hash::make('password'),
                'role' => $row['role'] ?? 'admin',
                'status' => 1,
                'is_admin' => 1,
            ]
        );

        // Create or update Staff
        return Staff::updateOrCreate(
            ['user_id' => $user->id],
            [
                'staff_id' => $row['staff_id'] ?? 'STF-' . strtoupper(Str::random(6)),
                'first_name' => $row['first_name'] ?? '',
                'last_name' => $row['last_name'] ?? '',
                'phone' => $row['phone'] ?? null,
                'nid' => $row['nid'] ?? null,
                'date_of_birth' => $row['date_of_birth'] ?? null,
                'current_address' => $row['current_address'] ?? null,
                'permanent_address' => $row['permanent_address'] ?? null,
                'marital_status' => $row['marital_status'] ?? 'single',
                'basic_salary' => $row['basic_salary'] ?? null,
                'date_of_joining' => $row['date_of_joining'] ?? null,
            ]
        );
    }
}
