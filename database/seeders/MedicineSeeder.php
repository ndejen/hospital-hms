<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\MedicineCategory;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $categories = MedicineCategory::pluck('id', 'name');

        $medicines = [
            // Antibiotics
            ['category' => 'Antibiotics', 'name' => 'Amoxicillin 500mg', 'generic_name' => 'Amoxicillin', 'dosage_form' => 'capsule', 'strength' => '500mg', 'manufacturer' => 'PharmaCo', 'stock_quantity' => 500, 'unit_price' => 0.50, 'expiry_date' => now()->addYears(2)->format('Y-m-d')],
            ['category' => 'Antibiotics', 'name' => 'Azithromycin 250mg', 'generic_name' => 'Azithromycin', 'dosage_form' => 'tablet', 'strength' => '250mg', 'manufacturer' => 'MediLabs', 'stock_quantity' => 300, 'unit_price' => 1.20, 'expiry_date' => now()->addYears(2)->format('Y-m-d')],
            ['category' => 'Antibiotics', 'name' => 'Ciprofloxacin 500mg', 'generic_name' => 'Ciprofloxacin', 'dosage_form' => 'tablet', 'strength' => '500mg', 'manufacturer' => 'GenericPharma', 'stock_quantity' => 8, 'unit_price' => 0.75, 'expiry_date' => now()->addYears(1)->format('Y-m-d')],
            // Analgesics
            ['category' => 'Analgesics', 'name' => 'Paracetamol 500mg', 'generic_name' => 'Acetaminophen', 'dosage_form' => 'tablet', 'strength' => '500mg', 'manufacturer' => 'PharmaCo', 'stock_quantity' => 1000, 'unit_price' => 0.10, 'expiry_date' => now()->addYears(3)->format('Y-m-d')],
            ['category' => 'Analgesics', 'name' => 'Ibuprofen 400mg', 'generic_name' => 'Ibuprofen', 'dosage_form' => 'tablet', 'strength' => '400mg', 'manufacturer' => 'MediLabs', 'stock_quantity' => 600, 'unit_price' => 0.15, 'expiry_date' => now()->addYears(2)->format('Y-m-d')],
            ['category' => 'Analgesics', 'name' => 'Tramadol 50mg', 'generic_name' => 'Tramadol HCl', 'dosage_form' => 'capsule', 'strength' => '50mg', 'manufacturer' => 'SpecialtyDrugs', 'stock_quantity' => 5, 'unit_price' => 0.80, 'expiry_date' => now()->addDays(20)->format('Y-m-d')],
            // Antihypertensives
            ['category' => 'Antihypertensives', 'name' => 'Amlodipine 5mg', 'generic_name' => 'Amlodipine', 'dosage_form' => 'tablet', 'strength' => '5mg', 'manufacturer' => 'CardioMeds', 'stock_quantity' => 400, 'unit_price' => 0.30, 'expiry_date' => now()->addYears(2)->format('Y-m-d')],
            ['category' => 'Antihypertensives', 'name' => 'Enalapril 10mg', 'generic_name' => 'Enalapril', 'dosage_form' => 'tablet', 'strength' => '10mg', 'manufacturer' => 'CardioMeds', 'stock_quantity' => 350, 'unit_price' => 0.25, 'expiry_date' => now()->addYears(2)->format('Y-m-d')],
            ['category' => 'Antihypertensives', 'name' => 'Losartan 50mg', 'generic_name' => 'Losartan Potassium', 'dosage_form' => 'tablet', 'strength' => '50mg', 'manufacturer' => 'PharmaCo', 'stock_quantity' => 7, 'unit_price' => 0.40, 'expiry_date' => now()->addYears(1)->format('Y-m-d')],
            // Antidiabetics
            ['category' => 'Antidiabetics', 'name' => 'Metformin 500mg', 'generic_name' => 'Metformin HCl', 'dosage_form' => 'tablet', 'strength' => '500mg', 'manufacturer' => 'DiabeCare', 'stock_quantity' => 800, 'unit_price' => 0.12, 'expiry_date' => now()->addYears(2)->format('Y-m-d')],
            ['category' => 'Antidiabetics', 'name' => 'Glibenclamide 5mg', 'generic_name' => 'Glyburide', 'dosage_form' => 'tablet', 'strength' => '5mg', 'manufacturer' => 'DiabeCare', 'stock_quantity' => 300, 'unit_price' => 0.20, 'expiry_date' => now()->addYears(2)->format('Y-m-d')],
            // Antihistamines
            ['category' => 'Antihistamines', 'name' => 'Cetirizine 10mg', 'generic_name' => 'Cetirizine HCl', 'dosage_form' => 'tablet', 'strength' => '10mg', 'manufacturer' => 'AllergyCare', 'stock_quantity' => 400, 'unit_price' => 0.18, 'expiry_date' => now()->addYears(2)->format('Y-m-d')],
            ['category' => 'Antihistamines', 'name' => 'Loratadine 10mg', 'generic_name' => 'Loratadine', 'dosage_form' => 'tablet', 'strength' => '10mg', 'manufacturer' => 'AllergyCare', 'stock_quantity' => 350, 'unit_price' => 0.22, 'expiry_date' => now()->addYears(2)->format('Y-m-d')],
            // Vitamins
            ['category' => 'Vitamins & Minerals', 'name' => 'Vitamin C 500mg', 'generic_name' => 'Ascorbic Acid', 'dosage_form' => 'tablet', 'strength' => '500mg', 'manufacturer' => 'NutriPharm', 'stock_quantity' => 1000, 'unit_price' => 0.08, 'expiry_date' => now()->addYears(3)->format('Y-m-d')],
            ['category' => 'Vitamins & Minerals', 'name' => 'Ferrous Sulfate 200mg', 'generic_name' => 'Ferrous Sulfate', 'dosage_form' => 'tablet', 'strength' => '200mg', 'manufacturer' => 'NutriPharm', 'stock_quantity' => 500, 'unit_price' => 0.15, 'expiry_date' => now()->addYears(2)->format('Y-m-d')],
            // Cardiovascular
            ['category' => 'Cardiovascular', 'name' => 'Aspirin 81mg', 'generic_name' => 'Acetylsalicylic Acid', 'dosage_form' => 'tablet', 'strength' => '81mg', 'manufacturer' => 'CardioMeds', 'stock_quantity' => 600, 'unit_price' => 0.05, 'expiry_date' => now()->addYears(3)->format('Y-m-d')],
            ['category' => 'Cardiovascular', 'name' => 'Atorvastatin 20mg', 'generic_name' => 'Atorvastatin Calcium', 'dosage_form' => 'tablet', 'strength' => '20mg', 'manufacturer' => 'CardioMeds', 'stock_quantity' => 9, 'unit_price' => 0.55, 'expiry_date' => now()->addYears(1)->format('Y-m-d')],
            // Antacids
            ['category' => 'Antacids', 'name' => 'Omeprazole 20mg', 'generic_name' => 'Omeprazole', 'dosage_form' => 'capsule', 'strength' => '20mg', 'manufacturer' => 'GastroMeds', 'stock_quantity' => 400, 'unit_price' => 0.35, 'expiry_date' => now()->addYears(2)->format('Y-m-d')],
            // Respiratory
            ['category' => 'Respiratory', 'name' => 'Salbutamol Inhaler', 'generic_name' => 'Albuterol', 'dosage_form' => 'inhaler', 'strength' => '100mcg', 'manufacturer' => 'RespiraCare', 'stock_quantity' => 150, 'unit_price' => 8.50, 'expiry_date' => now()->addYears(1)->format('Y-m-d')],
            ['category' => 'Respiratory', 'name' => 'Montelukast 10mg', 'generic_name' => 'Montelukast Sodium', 'dosage_form' => 'tablet', 'strength' => '10mg', 'manufacturer' => 'RespiraCare', 'stock_quantity' => 200, 'unit_price' => 0.60, 'expiry_date' => now()->addYears(2)->format('Y-m-d')],
        ];

        foreach ($medicines as $i => $med) {
            $categoryId = $categories[$med['category']] ?? null;
            Medicine::create([
                'category_id'   => $categoryId,
                'medicine_code' => 'MED-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'name'          => $med['name'],
                'generic_name'  => $med['generic_name'],
                'manufacturer'  => $med['manufacturer'],
                'dosage_form'   => $med['dosage_form'],
                'strength'      => $med['strength'],
                'unit'          => 'pcs',
                'stock_quantity'=> $med['stock_quantity'],
                'reorder_level' => 10,
                'unit_price'    => $med['unit_price'],
                'expiry_date'   => $med['expiry_date'],
                'is_active'     => true,
            ]);
        }
    }
}
