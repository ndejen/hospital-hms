<?php

namespace Database\Seeders;

use App\Models\Ward;
use App\Models\Bed;
use App\Models\Department;
use Illuminate\Database\Seeder;

class WardSeeder extends Seeder
{
    public function run(): void
    {
        $cardiology  = Department::where('code', 'CARD')->first();
        $surgery     = Department::where('code', 'SURG')->first();
        $pediatrics  = Department::where('code', 'PEDI')->first();
        $emergency   = Department::where('code', 'EMRG')->first();

        $wards = [
            ['name' => 'General Ward A',       'ward_number' => 'W-001', 'type' => 'general',    'total_beds' => 20, 'department_id' => null],
            ['name' => 'General Ward B',       'ward_number' => 'W-002', 'type' => 'general',    'total_beds' => 20, 'department_id' => null],
            ['name' => 'Cardiology Ward',      'ward_number' => 'W-003', 'type' => 'general',    'total_beds' => 15, 'department_id' => $cardiology?->id],
            ['name' => 'Surgical Ward',        'ward_number' => 'W-004', 'type' => 'general',    'total_beds' => 15, 'department_id' => $surgery?->id],
            ['name' => 'Pediatric Ward',       'ward_number' => 'W-005', 'type' => 'pediatric',  'total_beds' => 10, 'department_id' => $pediatrics?->id],
            ['name' => 'ICU',                  'ward_number' => 'W-006', 'type' => 'icu',        'total_beds' => 8,  'department_id' => $emergency?->id],
            ['name' => 'Private Room Block A', 'ward_number' => 'W-007', 'type' => 'private',    'total_beds' => 10, 'department_id' => null],
        ];

        foreach ($wards as $wardData) {
            $beds = $wardData['total_beds'];
            unset($wardData['total_beds']);
            $wardData['is_active'] = true;

            $ward = Ward::create($wardData + ['total_beds' => $beds]);

            // Create beds
            for ($b = 1; $b <= $beds; $b++) {
                Bed::create([
                    'ward_id'    => $ward->id,
                    'bed_number' => $ward->ward_number . '-B' . str_pad($b, 2, '0', STR_PAD_LEFT),
                    'status'     => $b <= 3 ? 'occupied' : 'available', // Some occupied for realism
                ]);
            }
        }
    }
}
