<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hospital_name'    => 'required|string|max:200',
            'hospital_address' => 'nullable|string',
            'hospital_phone'   => 'nullable|string|max:20',
            'hospital_email'   => 'nullable|email|max:100',
            'currency'         => 'required|string|max:10',
            'tax_rate'         => 'required|numeric|min:0|max:100',
        ]);

        $settings = [
            'hospital_name'    => $request->hospital_name,
            'hospital_address' => $request->hospital_address,
            'hospital_phone'   => $request->hospital_phone,
            'hospital_email'   => $request->hospital_email,
            'currency'         => $request->currency,
            'tax_rate'         => $request->tax_rate,
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value, 'general');
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
