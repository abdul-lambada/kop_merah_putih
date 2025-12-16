<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillageSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VillageSettingsController extends Controller
{
    public function index()
    {
        $settings = VillageSettings::getSettings();
        return view('admin.village-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'village_name' => 'required|string|max:255',
            'village_code' => 'nullable|string|max:20',
            'village_address' => 'nullable|string|max:500',
            'village_phone' => 'nullable|string|max:20',
            'village_email' => 'nullable|email|max:255',
            'village_head' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'province' => 'nullable|string|max:255',
            'regency' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['logo', 'facebook', 'instagram', 'twitter', 'youtube', 'website']);

        // Handle social media
        $socialMedia = [];
        if ($request->facebook) $socialMedia['facebook'] = $request->facebook;
        if ($request->instagram) $socialMedia['instagram'] = $request->instagram;
        if ($request->twitter) $socialMedia['twitter'] = $request->twitter;
        if ($request->youtube) $socialMedia['youtube'] = $request->youtube;
        if ($request->website) $socialMedia['website'] = $request->website;
        
        $data['social_media'] = $socialMedia;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoPath = $logo->store('logos', 'public');
            $data['logo_path'] = $logoPath;

            // Delete old logo if exists
            $settings = VillageSettings::getSettings();
            if ($settings->logo_path && Storage::disk('public')->exists($settings->logo_path)) {
                Storage::disk('public')->delete($settings->logo_path);
            }
        }

        VillageSettings::updateSettings($data);

        return redirect()->back()->with('success', 'Pengaturan desa berhasil diperbarui!');
    }

    public function removeLogo()
    {
        $settings = VillageSettings::getSettings();
        
        if ($settings->logo_path && Storage::disk('public')->exists($settings->logo_path)) {
            Storage::disk('public')->delete($settings->logo_path);
            $settings->update(['logo_path' => null]);
        }

        return redirect()->back()->with('success', 'Logo berhasil dihapus!');
    }
}
