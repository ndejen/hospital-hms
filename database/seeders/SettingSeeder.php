<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'hospital_name',    'value' => 'MediCare General Hospital',  'group' => 'general'],
            ['key' => 'hospital_address', 'value' => '123 Health Avenue, Medical City, MC 10001', 'group' => 'general'],
            ['key' => 'hospital_phone',   'value' => '+1 (555) 000-1234',          'group' => 'general'],
            ['key' => 'hospital_email',   'value' => 'info@medicarehospital.com',  'group' => 'general'],
            ['key' => 'currency',         'value' => 'USD',                        'group' => 'general'],
            ['key' => 'currency_symbol',  'value' => '$',                          'group' => 'general'],
            ['key' => 'tax_rate',         'value' => '5',                          'group' => 'billing'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
