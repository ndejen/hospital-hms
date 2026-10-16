<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $genders     = ['male', 'female'];
        $allergies   = ['Penicillin', 'Aspirin', 'Sulfa drugs', 'Ibuprofen', 'None', 'Codeine', 'Latex'];
        $diseases    = ['Hypertension', 'Diabetes Type 2', 'Asthma', 'None', 'Arthritis', 'Hypothyroidism'];

        // Attach demo user to a patient
        $demoUser = User::where('email', 'patient@hospital.com')->first();

        for ($i = 1; $i <= 50; $i++) {
            $gender    = $faker->randomElement($genders);
            $firstName = $gender === 'male' ? $faker->firstNameMale() : $faker->firstNameFemale();
            $lastName  = $faker->lastName();

            $patientData = [
                'patient_number'              => 'PAT-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'first_name'                  => $firstName,
                'last_name'                   => $lastName,
                'gender'                      => $gender,
                'date_of_birth'               => $faker->dateTimeBetween('-80 years', '-1 year')->format('Y-m-d'),
                'phone'                       => $faker->phoneNumber(),
                'email'                       => $faker->unique()->safeEmail(),
                'address'                     => $faker->streetAddress(),
                'city'                        => $faker->city(),
                'state'                       => $faker->state(),
                'zip_code'                    => $faker->postcode(),
                'emergency_contact_name'      => $faker->name(),
                'emergency_contact_phone'     => $faker->phoneNumber(),
                'emergency_contact_relation'  => $faker->randomElement(['Spouse', 'Parent', 'Sibling', 'Child', 'Friend']),
                'blood_group'                 => $faker->randomElement($bloodGroups),
                'occupation'                  => $faker->jobTitle(),
                'known_allergies'             => $faker->randomElement($allergies),
                'chronic_diseases'            => $faker->randomElement($diseases),
                'is_active'                   => true,
            ];

            // Link first patient to demo user
            if ($i === 1 && $demoUser) {
                $patientData['user_id'] = $demoUser->id;
            }

            Patient::create($patientData);
        }
    }
}
