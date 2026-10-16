<?php

namespace Database\Seeders;

use App\Models\Admission;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Ward;
use App\Models\Bed;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AdmissionSeeder extends Seeder
{
    public function run(): void
    {
        $faker    = Faker::create();
        $patients = Patient::all();
        $doctors  = Doctor::all();
        $wards    = Ward::with('beds')->get();

        if ($patients->isEmpty() || $doctors->isEmpty() || $wards->isEmpty()) return;

        $reasons = [
            'Post-operative monitoring', 'Severe pneumonia treatment',
            'Cardiac observation', 'Diabetic ketoacidosis management',
            'Orthopedic surgery recovery', 'Asthma exacerbation',
            'Hypertensive crisis', 'Urinary tract infection', 'Anemia treatment',
        ];

        for ($i = 1; $i <= 15; $i++) {
            $patient = $patients->random();
            $doctor  = $doctors->random();
            $ward    = $wards->random();
            $availBed = $ward->beds->where('status', 'available')->first();

            $admissionDate = now()->subDays(rand(1, 30));
            $isDischarge   = $i <= 8; // Some discharged

            $admission = Admission::create([
                'patient_id'       => $patient->id,
                'doctor_id'        => $doctor->id,
                'ward_id'          => $ward->id,
                'bed_id'           => $availBed?->id,
                'admission_number' => 'ADM-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'admission_date'   => $admissionDate,
                'discharge_date'   => $isDischarge ? $admissionDate->copy()->addDays(rand(2, 10)) : null,
                'status'           => $isDischarge ? 'discharged' : 'admitted',
                'admission_reason' => $faker->randomElement($reasons),
                'discharge_summary' => $isDischarge ? $faker->paragraph() : null,
                'discharge_condition' => $isDischarge ? $faker->randomElement(['improved', 'recovered', 'referred']) : null,
            ]);

            // Mark bed as occupied for active admissions
            if (!$isDischarge && $availBed) {
                $availBed->update(['status' => 'occupied']);
            }
        }
    }
}
