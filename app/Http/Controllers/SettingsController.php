<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $businessSettings = BusinessSetting::where('user_id', $user->id)->first();
        
        $settings = [
            'business_name' => $businessSettings->business_name ?? config('app.name'),
            'business_email' => $businessSettings->business_email ?? '',
            'business_phone' => $businessSettings->business_phone ?? '',
            'business_address' => $businessSettings->business_address ?? '',
            'business_hours' => $businessSettings->business_hours ?? '',
            'appointment_lead_time' => $businessSettings->appointment_lead_time ?? 24,
            'appointment_duration' => $businessSettings->appointment_duration ?? 60,
            'notification_email' => $businessSettings->notification_email ?? false,
        ];
        
        return Inertia::render('Settings/Index', [
            'settings' => $settings
        ]);
    }
    
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_email' => 'nullable|email|max:255',
            'business_phone' => 'nullable|string|max:255',
            'business_address' => 'nullable|string',
            'business_hours' => 'nullable|string',
            'appointment_lead_time' => 'nullable|integer|min:1',
            'appointment_duration' => 'nullable|integer|min:5',
            'notification_email' => 'boolean',
            'logo' => 'nullable|image|max:2048', // 2MB max
        ]);
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Store the logo in the storage/app/public/logos directory
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $logoPath;
        }
        
        // Handle logo removal
        if ($request->boolean('_removelogo')) {
            // Get existing logo
            $existingLogo = BusinessSetting::where('user_id', $user->id)->value('logo');
            
            // Delete the file if it exists
            if ($existingLogo && \Storage::disk('public')->exists($existingLogo)) {
                \Storage::disk('public')->delete($existingLogo);
            }
            
            $validated['logo'] = null;
        }
        
        // Remove internal fields
        unset($validated['_method'], $validated['_removelogo']);
        
        // Update business settings
        BusinessSetting::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );
        
        // Clear ALL business settings cache, not just for this user
        \Cache::flush();

        // Generate a new Inertia version hash to force refresh props
        \Inertia\Inertia::version(function () {
            return uniqid();
        });

        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}