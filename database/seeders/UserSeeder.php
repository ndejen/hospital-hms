<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Nurse;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Admin
        User::updateOrCreate(['email' => 'admin@hospital.com'], [
            'name'      => 'System Administrator',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'phone'     => '+1 555-100-0001',
            'is_active' => true,
        ]);

        // Receptionist
        User::updateOrCreate(['email' => 'receptionist@hospital.com'], [
            'name'      => 'Sarah Johnson',
            'password'  => Hash::make('password'),
            'role'      => 'receptionist',
            'phone'     => '+1 555-100-0002',
            'is_active' => true,
        ]);

        // Lab Staff
        User::updateOrCreate(['email' => 'lab@hospital.com'], [
            'name'      => 'Michael Chen',
            'password'  => Hash::make('password'),
            'role'      => 'laboratory',
            'phone'     => '+1 555-100-0003',
            'is_active' => true,
        ]);

        // Pharmacist
        User::updateOrCreate(['email' => 'pharmacist@hospital.com'], [
            'name'      => 'Emily Rodriguez',
            'password'  => Hash::make('password'),
            'role'      => 'pharmacist',
            'phone'     => '+1 555-100-0004',
            'is_active' => true,
        ]);

        // Doctors
        $specializations = [
            'Cardiology'       => ['CARD', ['Cardiologist', 'Interventional Cardiologist']],
            'General Surgery'  => ['SURG', ['General Surgeon', 'Laparoscopic Surgeon']],
            'Pediatrics'       => ['PEDI', ['Pediatrician', 'Neonatologist']],
            'Orthopedics'      => ['ORTH', ['Orthopedic Surgeon', 'Sports Medicine']],
            'Neurology'        => ['NEUR', ['Neurologist', 'Neurosurgeon']],
            'Internal Medicine'=> ['INMD', ['Internist', 'Gastroenterologist']],
        ];

        $docNames = [
            'James Wilson', 'Robert Anderson', 'Jennifer Martinez',
            'David Thompson', 'Lisa Garcia', 'William Brown',
            'Patricia Jones', 'Richard White', 'Sandra Davis', 'Joseph Miller',
        ];

        foreach ($docNames as $i => $name) {
            $deptKeys = array_keys($specializations);
            $deptName = $deptKeys[$i % count($deptKeys)];
            [$deptCode, $specs] = $specializations[$deptName];

            $email = strtolower(str_replace(' ', '.', $name)) . '@hospital.com';

            $user = User::updateOrCreate(['email' => $email], [
                'name'      => $name,
                'password'  => Hash::make('password'),
                'role'      => 'doctor',
                'phone'     => '+1 555-200-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'is_active' => true,
            ]);

            $dept = Department::where('name', $deptName)->first();

            if (!Doctor::where('user_id', $user->id)->exists()) {
                Doctor::create([
                    'user_id'        => $user->id,
                    'department_id'  => $dept?->id,
                    'doctor_number'  => 'DOC-' . str_pad(Doctor::count() + 1, 4, '0', STR_PAD_LEFT),
                    'specialization' => $specs[$i % 2],
                    'phone'          => $user->phone,
                    'license_number' => 'LIC-' . strtoupper(substr($name, 0, 3)) . '-' . ($i + 1001),
                    'joining_date'   => now()->subYears(rand(1, 10))->subMonths(rand(0, 11)),
                    'bio'            => "Experienced " . $specs[$i % 2] . " with " . rand(5, 20) . " years of practice.",
                ]);
            }
        }

        // Nurses
        $nurseNames = ['Nancy Clark', 'Karen Lewis', 'Betty Walker', 'Helen Hall', 'Dorothy Young'];
        $shifts = ['morning', 'afternoon', 'night'];
        $departments = Department::all();

        foreach ($nurseNames as $i => $name) {
            $email = strtolower(str_replace(' ', '.', $name)) . '@hospital.com';

            $user = User::updateOrCreate(['email' => $email], [
                'name'      => $name,
                'password'  => Hash::make('password'),
                'role'      => 'nurse',
                'phone'     => '+1 555-300-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'is_active' => true,
            ]);

            $dept = $departments->get($i % $departments->count());

            if (!Nurse::where('user_id', $user->id)->exists()) {
                Nurse::create([
                    'user_id'        => $user->id,
                    'department_id'  => $dept?->id,
                    'nurse_number'   => 'NUR-' . str_pad(Nurse::count() + 1, 4, '0', STR_PAD_LEFT),
                    'phone'          => $user->phone,
                    'license_number' => 'RN-' . strtoupper(substr($name, 0, 3)) . '-' . ($i + 2001),
                    'joining_date'   => now()->subYears(rand(1, 5)),
                    'shift'          => $shifts[$i % 3],
                ]);
            }
        }

        // Demo patient user
        $patientUser = User::updateOrCreate(['email' => 'patient@hospital.com'], [
            'name'      => 'Demo Patient',
            'password'  => Hash::make('password'),
            'role'      => 'patient',
            'is_active' => true,
        ]);
    }
}
