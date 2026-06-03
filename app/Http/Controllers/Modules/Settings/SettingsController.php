<?php

namespace App\Http\Controllers\Modules\Settings;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = BusinessSetting::getForTenant(auth()->user()->tenant_id);
        return view('modules.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'logo'                 => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'receipt_header'       => ['nullable', 'string', 'max:500'],
            'receipt_footer'       => ['nullable', 'string', 'max:500'],
            'currency_symbol'      => ['required', 'string', 'max:10'],
            'currency_code'        => ['required', 'string', 'max:10'],
            'tax_name'             => ['nullable', 'string', 'max:50'],
            'tax_number'           => ['nullable', 'string', 'max:100'],
            'address'              => ['nullable', 'string', 'max:500'],
            'city'                 => ['nullable', 'string', 'max:100'],
            'phone'                => ['nullable', 'string', 'max:20'],
            'email'                => ['nullable', 'email'],
            'website'              => ['nullable', 'string', 'max:255'],
        ]);

        $settings = BusinessSetting::getForTenant(auth()->user()->tenant_id);

        $data = [
            'receipt_header'       => $request->receipt_header,
            'receipt_footer'       => $request->receipt_footer,
            'receipt_show_logo'    => $request->boolean('receipt_show_logo'),
            'receipt_show_address' => $request->boolean('receipt_show_address'),
            'receipt_show_phone'   => $request->boolean('receipt_show_phone'),
            'receipt_show_email'   => $request->boolean('receipt_show_email'),
            'receipt_show_tax'     => $request->boolean('receipt_show_tax'),
            'receipt_show_loyalty' => $request->boolean('receipt_show_loyalty'),
            'currency_symbol'      => $request->currency_symbol,
            'currency_code'        => $request->currency_code,
            'tax_name'             => $request->tax_name,
            'tax_number'           => $request->tax_number,
            'address'              => $request->address,
            'city'                 => $request->city,
            'phone'                => $request->phone,
            'email'                => $request->email,
            'website'              => $request->website,
        ];

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $path        = $request->file('logo')->store('logos', 'public');
            $data['logo_path'] = $path;
        }

        if ($request->boolean('remove_logo') && $settings->logo_path) {
            Storage::disk('public')->delete($settings->logo_path);
            $data['logo_path'] = null;
        }

        $settings->update($data);

        return redirect()->route('settings.index')
            ->with('success', 'Settings saved successfully.');
    }
}