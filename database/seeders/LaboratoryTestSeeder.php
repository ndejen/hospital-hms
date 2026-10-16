<?php

namespace Database\Seeders;

use App\Models\LaboratoryTest;
use Illuminate\Database\Seeder;

class LaboratoryTestSeeder extends Seeder
{
    public function run(): void
    {
        $tests = [
            // Hematology
            ['name' => 'Complete Blood Count (CBC)', 'category' => 'Hematology', 'price' => 25.00, 'normal_range' => 'See report', 'unit' => '', 'turnaround_hours' => 4],
            ['name' => 'Hemoglobin', 'category' => 'Hematology', 'price' => 10.00, 'normal_range' => 'Male: 13.5-17.5 g/dL, Female: 12-15.5 g/dL', 'unit' => 'g/dL', 'turnaround_hours' => 2],
            ['name' => 'Platelet Count', 'category' => 'Hematology', 'price' => 12.00, 'normal_range' => '150,000-400,000 /µL', 'unit' => '/µL', 'turnaround_hours' => 2],
            // Chemistry
            ['name' => 'Blood Glucose (Fasting)', 'category' => 'Chemistry', 'price' => 8.00, 'normal_range' => '70-100 mg/dL', 'unit' => 'mg/dL', 'turnaround_hours' => 1],
            ['name' => 'HbA1c', 'category' => 'Chemistry', 'price' => 30.00, 'normal_range' => '< 5.7%', 'unit' => '%', 'turnaround_hours' => 4],
            ['name' => 'Serum Creatinine', 'category' => 'Chemistry', 'price' => 15.00, 'normal_range' => '0.74-1.35 mg/dL', 'unit' => 'mg/dL', 'turnaround_hours' => 2],
            ['name' => 'Liver Function Test (LFT)', 'category' => 'Chemistry', 'price' => 40.00, 'normal_range' => 'See report', 'unit' => '', 'turnaround_hours' => 6],
            ['name' => 'Total Cholesterol', 'category' => 'Chemistry', 'price' => 18.00, 'normal_range' => '< 200 mg/dL', 'unit' => 'mg/dL', 'turnaround_hours' => 2],
            ['name' => 'Serum Urea', 'category' => 'Chemistry', 'price' => 12.00, 'normal_range' => '7-20 mg/dL', 'unit' => 'mg/dL', 'turnaround_hours' => 2],
            // Immunology
            ['name' => 'HIV Test', 'category' => 'Immunology', 'price' => 20.00, 'normal_range' => 'Non-reactive', 'unit' => '', 'turnaround_hours' => 24],
            ['name' => 'Hepatitis B Surface Antigen', 'category' => 'Immunology', 'price' => 22.00, 'normal_range' => 'Negative', 'unit' => '', 'turnaround_hours' => 24],
            ['name' => 'Thyroid Stimulating Hormone (TSH)', 'category' => 'Immunology', 'price' => 35.00, 'normal_range' => '0.4-4.0 mIU/L', 'unit' => 'mIU/L', 'turnaround_hours' => 6],
            // Microbiology
            ['name' => 'Urine Culture & Sensitivity', 'category' => 'Microbiology', 'price' => 45.00, 'normal_range' => 'No growth', 'unit' => '', 'turnaround_hours' => 72],
            ['name' => 'Blood Culture', 'category' => 'Microbiology', 'price' => 55.00, 'normal_range' => 'No growth', 'unit' => '', 'turnaround_hours' => 72],
            // Urinalysis
            ['name' => 'Urinalysis (Routine)', 'category' => 'Urinalysis', 'price' => 10.00, 'normal_range' => 'See report', 'unit' => '', 'turnaround_hours' => 1],
            // Radiology markers
            ['name' => 'C-Reactive Protein (CRP)', 'category' => 'Chemistry', 'price' => 20.00, 'normal_range' => '< 10 mg/L', 'unit' => 'mg/L', 'turnaround_hours' => 3],
        ];

        foreach ($tests as $i => $test) {
            LaboratoryTest::create([
                'test_code'        => 'LAB-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'name'             => $test['name'],
                'category'         => $test['category'],
                'price'            => $test['price'],
                'normal_range'     => $test['normal_range'],
                'unit'             => $test['unit'],
                'turnaround_hours' => $test['turnaround_hours'],
                'is_active'        => true,
            ]);
        }
    }
}
