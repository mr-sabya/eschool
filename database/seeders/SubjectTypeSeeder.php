<?php

namespace Database\Seeders;

use App\Models\SubjectType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Theory', 'code' => 'TH'],
            ['name' => 'Practical', 'code' => 'PR'],
            ['name' => 'Lab', 'code' => 'LB'],
            ['name' => 'Project', 'code' => 'PJ'],
        ];

        foreach ($types as $type) {
            SubjectType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
