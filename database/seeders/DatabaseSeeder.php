<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            DepartmentSeeder::class,
            UserSeeder::class,
            MedicineCategorySeeder::class,
            MedicineSeeder::class,
            LaboratoryTestSeeder::class,
            WardSeeder::class,
            PatientSeeder::class,
            AppointmentSeeder::class,
            MedicalRecordSeeder::class,
            AdmissionSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}
