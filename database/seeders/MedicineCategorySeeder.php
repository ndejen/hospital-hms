<?php

namespace Database\Seeders;

use App\Models\MedicineCategory;
use Illuminate\Database\Seeder;

class MedicineCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Antibiotics',        'description' => 'Medicines to treat bacterial infections'],
            ['name' => 'Analgesics',          'description' => 'Pain relievers and pain management drugs'],
            ['name' => 'Antihypertensives',   'description' => 'Blood pressure medications'],
            ['name' => 'Antidiabetics',       'description' => 'Diabetes management medicines'],
            ['name' => 'Antihistamines',      'description' => 'Allergy medications'],
            ['name' => 'Antacids',            'description' => 'Stomach acid and heartburn medicines'],
            ['name' => 'Vitamins & Minerals', 'description' => 'Nutritional supplements'],
            ['name' => 'Cardiovascular',      'description' => 'Heart and circulation medications'],
            ['name' => 'Respiratory',         'description' => 'Breathing and lung medications'],
            ['name' => 'Neurological',        'description' => 'Brain and nervous system medications'],
        ];

        foreach ($categories as $cat) {
            MedicineCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
