<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get or create business settings for the user
        $settings = BusinessSetting::firstOrNew(
            ['user_id' => $user->id]
        );
        
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
            'appointment_lead_time' => 'nullable|integer|min:0',
            'appointment_duration' => 'nullable|integer|min:5',
            'notification_email' => 'boolean',
        ]);
        
        $settings = BusinessSetting::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );
        
        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully');
    }
}