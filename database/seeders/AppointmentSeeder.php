<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $faker    = Faker::create();
        $patients = Patient::all();
        $doctors  = Doctor::all();

        if ($patients->isEmpty() || $doctors->isEmpty()) return;

        $statuses = ['scheduled', 'confirmed', 'checked_in', 'completed', 'cancelled'];
        $types    = ['new', 'follow_up', 'emergency'];
        $times    = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '14:00', '14:30', '15:00', '15:30', '16:00'];
        $reasons  = [
            'Routine check-up', 'Chest pain', 'Headache', 'Fever', 'Back pain',
            'Follow-up visit', 'Skin rash', 'Shortness of breath', 'Joint pain',
            'Abdominal pain', 'Dizziness', 'Fatigue', 'High blood pressure monitoring'
        ];

        for ($i = 1; $i <= 80; $i++) {
            $patient    = $patients->random();
            $doctor     = $doctors->random();
            $statusIndex = $faker->numberBetween(0, 4);
            $daysOffset  = $statusIndex <= 2 ? $faker->numberBetween(-30, 30) : $faker->numberBetween(-60, -1);

            Appointment::create([
                'patient_id'         => $patient->id,
                'doctor_id'          => $doctor->id,
                'department_id'      => $doctor->department_id,
                'appointment_number' => 'APT-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'appointment_date'   => now()->addDays($daysOffset)->format('Y-m-d'),
                'appointment_time'   => $faker->randomElement($times),
                'status'             => $statuses[$statusIndex],
                'reason'             => $faker->randomElement($reasons),
                'type'               => $faker->randomElement($types),
                'notes'              => $faker->boolean(30) ? $faker->sentence() : null,
            ]);
        }
    }
}
