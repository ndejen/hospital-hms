<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Cardiology',      'code' => 'CARD', 'description' => 'Heart and cardiovascular diseases',       'location' => 'Building A, Floor 2'],
            ['name' => 'General Surgery',  'code' => 'SURG', 'description' => 'Surgical procedures and operations',     'location' => 'Building B, Floor 3'],
            ['name' => 'Pediatrics',       'code' => 'PEDI', 'description' => 'Child and infant healthcare',            'location' => 'Building A, Floor 1'],
            ['name' => 'Orthopedics',      'code' => 'ORTH', 'description' => 'Bone, joint and muscle disorders',       'location' => 'Building C, Floor 2'],
            ['name' => 'Neurology',        'code' => 'NEUR', 'description' => 'Brain, spine and nervous system',        'location' => 'Building B, Floor 4'],
            ['name' => 'Gynecology',       'code' => 'GYNE', 'description' => "Women's reproductive health",           'location' => 'Building A, Floor 3'],
            ['name' => 'Emergency',        'code' => 'EMRG', 'description' => 'Emergency and critical care',           'location' => 'Building A, Ground Floor'],
            ['name' => 'Laboratory',       'code' => 'LABR', 'description' => 'Diagnostic laboratory services',        'location' => 'Building D, Floor 1'],
            ['name' => 'Pharmacy',         'code' => 'PHRM', 'description' => 'Medicine dispensing and management',    'location' => 'Building A, Ground Floor'],
            ['name' => 'Radiology',        'code' => 'RADI', 'description' => 'Imaging and diagnostic radiology',      'location' => 'Building D, Floor 2'],
            ['name' => 'Internal Medicine','code' => 'INMD', 'description' => 'General internal medicine',             'location' => 'Building B, Floor 1'],
            ['name' => 'Dermatology',      'code' => 'DERM', 'description' => 'Skin disorders and conditions',         'location' => 'Building C, Floor 1'],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(['code' => $dept['code']], $dept);
        }
    }
}
