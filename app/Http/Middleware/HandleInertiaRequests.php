<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Cache;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        
        // Get fresh business settings with caching
        $businessSettings = null;
        if ($user) {
            $businessSettings = Cache::remember('business_settings_'.$user->id, 10, function() use ($user) {
                return BusinessSetting::where('user_id', $user->id)->first();
            });
        }
        
        // Get logo URL
        $logoUrl = null;
        if ($businessSettings && $businessSettings->logo) {
            $logoUrl = asset('storage/' . $businessSettings->logo);
        }
        
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user,
            ],
            'appName' => config('app.name'),
            'businessSettings' => [
                'name' => $businessSettings->business_name ?? config('app.name'),
                'logo' => $logoUrl,
                // Add other business settings fields as needed
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ]);
    }
}
