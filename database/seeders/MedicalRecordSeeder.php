<?php

namespace Database\Seeders;

use App\Models\MedicalRecord;
use App\Models\Diagnosis;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Appointment;
use App\Models\Medicine;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MedicalRecordSeeder extends Seeder
{
    public function run(): void
    {
        $faker    = Faker::create();
        $medicines = Medicine::all();

        $completedAppts = Appointment::where('status', 'completed')->with(['patient', 'doctor'])->get();

        $diagnoses = [
            ['name' => 'Hypertension',           'icd' => 'I10',   'severity' => 'moderate'],
            ['name' => 'Type 2 Diabetes',         'icd' => 'E11',   'severity' => 'moderate'],
            ['name' => 'Upper Respiratory Tract Infection', 'icd' => 'J06', 'severity' => 'mild'],
            ['name' => 'Migraine',                'icd' => 'G43',   'severity' => 'moderate'],
            ['name' => 'Gastroenteritis',         'icd' => 'K59',   'severity' => 'mild'],
            ['name' => 'Pneumonia',               'icd' => 'J18',   'severity' => 'severe'],
            ['name' => 'Anemia',                  'icd' => 'D50',   'severity' => 'mild'],
            ['name' => 'Urinary Tract Infection', 'icd' => 'N39',   'severity' => 'mild'],
            ['name' => 'Osteoarthritis',          'icd' => 'M15',   'severity' => 'moderate'],
            ['name' => 'Asthma',                  'icd' => 'J45',   'severity' => 'moderate'],
        ];

        foreach ($completedAppts->take(30) as $i => $appt) {
            $record = MedicalRecord::create([
                'patient_id'          => $appt->patient_id,
                'doctor_id'           => $appt->doctor_id,
                'appointment_id'      => $appt->id,
                'record_number'       => 'MR-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'visit_date'          => $appt->appointment_date,
                'chief_complaint'     => $faker->randomElement(['Chest pain', 'Headache', 'Fatigue', 'Back pain', 'Cough', 'Fever', 'Shortness of breath']),
                'history_of_illness'  => $faker->paragraph(),
                'physical_examination'=> $faker->paragraph(),
                'diagnosis'           => $faker->randomElement(array_column($diagnoses, 'name')),
                'treatment_plan'      => $faker->paragraph(),
                'follow_up_instructions' => $faker->boolean(60) ? 'Return in ' . $faker->numberBetween(1, 4) . ' weeks if symptoms persist.' : null,
            ]);

            // Add diagnosis
            $diag = $faker->randomElement($diagnoses);
            Diagnosis::create([
                'medical_record_id' => $record->id,
                'patient_id'        => $appt->patient_id,
                'doctor_id'         => $appt->doctor_id,
                'icd_code'          => $diag['icd'],
                'diagnosis_name'    => $diag['name'],
                'description'       => $faker->sentence(),
                'severity'          => $diag['severity'],
                'diagnosed_date'    => $appt->appointment_date,
                'is_chronic'        => $faker->boolean(20),
            ]);

            // Add prescription
            if ($medicines->isNotEmpty() && $faker->boolean(70)) {
                $prescription = Prescription::create([
                    'patient_id'          => $appt->patient_id,
                    'doctor_id'           => $appt->doctor_id,
                    'medical_record_id'   => $record->id,
                    'prescription_number' => 'PRX-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                    'prescribed_date'     => $appt->appointment_date,
                    'valid_until'         => $appt->appointment_date->addDays(30),
                    'status'              => $faker->randomElement(['pending', 'dispensed', 'dispensed']),
                    'notes'               => $faker->boolean(40) ? $faker->sentence() : null,
                ]);

                $selectedMeds = $medicines->random(min(3, $medicines->count()));
                foreach ($selectedMeds as $med) {
                    PrescriptionItem::create([
                        'prescription_id' => $prescription->id,
                        'medicine_id'     => $med->id,
                        'dosage'          => $med->strength,
                        'frequency'       => $faker->randomElement(['Once daily', 'Twice daily', 'Three times daily', 'Every 8 hours']),
                        'duration_days'   => $faker->randomElement([5, 7, 10, 14, 30]),
                        'quantity'        => $faker->numberBetween(10, 60),
                        'instructions'    => $faker->randomElement(['Take after meals', 'Take with water', 'Take before sleep', 'Take on empty stomach']),
                        'is_dispensed'    => $prescription->status === 'dispensed',
                    ]);
                }
            }
        }
    }
}
