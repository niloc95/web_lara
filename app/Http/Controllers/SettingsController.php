<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $businessSettings = BusinessSetting::where('user_id', $user->id)->first();
        
        // Debug the incoming data from DB
        \Log::debug('Business hours from DB:', [
            'raw' => $businessSettings->business_hours ?? null,
            'type' => gettype($businessSettings->business_hours)
        ]);
        
        \Log::debug('Raw business hours:', [
            'data' => $businessSettings->business_hours ?? 'null',
            'type' => gettype($businessSettings->business_hours),
        ]);

        if (is_string($businessSettings->business_hours)) {
            $decodedHours = json_decode($businessSettings->business_hours, true);
            \Log::debug('Decoded hours:', [
                'count' => is_array($decodedHours) ? count($decodedHours) : 'not array',
                'sample' => is_array($decodedHours) ? json_encode(array_slice($decodedHours, 0, 2)) : 'none'
            ]);
        }

        $settings = [
            'business_name' => $businessSettings->business_name ?? config('app.name'),
            'business_email' => $businessSettings->business_email ?? '',
            'business_phone' => $businessSettings->business_phone ?? '',
            'business_mobile' => $businessSettings->business_mobile ?? '', // Add this line
            'business_whatsapp' => $businessSettings->business_whatsapp ?? '', // Add this line
            'business_address' => $businessSettings->business_address ?? '',
            'business_hours' => $businessSettings->business_hours ?? $this->getDefaultBusinessHours(),
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
        
        // Validate all fields, including the new ones
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_email' => 'nullable|email|max:255',
            'business_phone' => 'nullable|string|max:255',
            'business_mobile' => 'nullable|string|max:255', // Add validation for mobile
            'business_whatsapp' => 'nullable|string|max:255', // Add validation for WhatsApp
            'business_address' => 'nullable|string',
            'appointment_lead_time' => 'nullable|integer|min:0',
            'appointment_duration' => 'nullable|integer|min:5',
            'notification_email' => 'boolean',
        ]);
        
        // Get existing settings
        $settings = BusinessSetting::where('user_id', $user->id)->first();
        if (!$settings) {
            $settings = new BusinessSetting();
            $settings->user_id = $user->id;
        }
        
        // Update all fields
        $settings->business_name = $validated['business_name'];
        $settings->business_email = $validated['business_email'];
        $settings->business_phone = $validated['business_phone'];
        $settings->business_mobile = $validated['business_mobile']; // Explicitly set mobile
        $settings->business_whatsapp = $validated['business_whatsapp']; // Explicitly set WhatsApp
        $settings->business_address = $validated['business_address'];
        $settings->appointment_lead_time = $validated['appointment_lead_time'];
        $settings->appointment_duration = $validated['appointment_duration'];
        $settings->notification_email = $validated['notification_email'];
        
        // Handle file upload if there is one
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            // Delete old logo if exists
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            
            $logoPath = $request->file('logo')->store('logos', 'public');
            $settings->logo = $logoPath;
        }
        
        // Handle logo removal
        if ($request->has('_removelogo') && $request->_removelogo == '1') {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $settings->logo = null;
        }
        
        $settings->save();
        
        return back()->with('success', 'Settings updated successfully');
    }

    // Add this helper method
    private function getDefaultBusinessHours()
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $result = [];
        
        foreach ($days as $index => $day) {
            $isWeekday = $index > 0 && $index < 6;
            $result[] = [
                'dayName' => $day,
                'isOpen' => $isWeekday,
                'ranges' => $isWeekday ? [['startTime' => 540, 'endTime' => 1020]] : []
            ];
        }
        
        return $result;
    }

    // Helper method to check if a string is valid JSON
    private function isJson($string) {
        if (!is_string($string)) return false;
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    // Add these new methods:

    // General settings (text-based fields)
    public function updateGeneral(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_email' => 'nullable|email|max:255',
            'business_phone' => 'nullable|string|max:255',
            'business_mobile' => 'nullable|string|max:255', // Add this line
            'business_whatsapp' => 'nullable|string|max:255', // Add this line
            'business_address' => 'nullable|string',
            'appointment_lead_time' => 'nullable|integer|min:0',
            'appointment_duration' => 'nullable|integer|min:5',
            'notification_email' => 'boolean',
        ]);
        
        BusinessSetting::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );
        
        return back()->with('success', 'Settings updated successfully');
    }

    // Business hours only
    public function updateBusinessHours(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'business_hours' => 'required', 
        ]);
        
        $businessHours = $validated['business_hours'];
        
        // If it's a string that looks like JSON, parse it
        if (is_string($businessHours) && $this->isJson($businessHours)) {
            $businessHours = json_decode($businessHours, true);
        }
        
        // Ensure each day has proper boolean values for isOpen
        if (is_array($businessHours)) {
            foreach ($businessHours as &$day) {
                if (isset($day['isOpen'])) {
                    // Convert string "0"/"1" to actual boolean
                    $day['isOpen'] = filter_var($day['isOpen'], FILTER_VALIDATE_BOOLEAN);
                }
            }
        }
        
        // Store as JSON string
        $businessSetting = BusinessSetting::updateOrCreate(
            ['user_id' => $user->id],
            ['business_hours' => json_encode($businessHours)]
        );
        
        return response()->json([
            'success' => true, 
            'message' => 'Business hours updated',
            'updated_at' => $businessSetting->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    // Logo only
    public function updateLogo(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'logo' => 'nullable|image|max:2048',
            'remove' => 'nullable|boolean',
        ]);
        
        $currentSettings = BusinessSetting::where('user_id', $user->id)->first();
        
        // Handle logo removal
        if ($request->has('remove') && $request->boolean('remove')) {
            if ($currentSettings && $currentSettings->logo) {
                \Storage::disk('public')->delete($currentSettings->logo);
            }
            
            BusinessSetting::updateOrCreate(
                ['user_id' => $user->id],
                ['logo' => null]
            );
            
            return response()->json(['success' => true, 'message' => 'Logo removed']);
        }
        
        // Handle logo upload
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            // Delete existing logo if exists
            if ($currentSettings && $currentSettings->logo) {
                \Storage::disk('public')->delete($currentSettings->logo);
            }
            
            $logoPath = $request->file('logo')->store('logos', 'public');
            
            BusinessSetting::updateOrCreate(
                ['user_id' => $user->id],
                ['logo' => $logoPath]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Logo updated',
                'logo' => $logoPath
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'No changes made'], 400);
    }
}