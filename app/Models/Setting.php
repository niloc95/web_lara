<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];
    
    /**
     * Get a setting by key with optional default value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        // Try to get from cache first
        return Cache::remember('setting.'.$key, 3600, function() use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }
    
    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @return Setting
     */
    public static function set(string $key, $value)
    {
        // Clear cache for this key
        Cache::forget('setting.'.$key);
        
        // Update or create the setting - FIXED HERE
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value] // This was missing the value
        );
    }
    
    /**
     * Cast value based on key prefix (optional)
     * For example: bool_notification_enabled would cast to boolean
     */
    public function getValueAttribute($value)
    {
        $key = $this->key;
        
        // Cast booleans
        if (str_starts_with($key, 'bool_')) {
            return (bool) $value;
        }
        
        // Cast integers
        if (str_starts_with($key, 'int_')) {
            return (int) $value;
        }
        
        // Cast arrays/objects
        if (str_starts_with($key, 'json_') && is_string($value)) {
            return json_decode($value, true);
        }
        
        return $value;
    }
}

// Get a setting with automatic caching
$siteName = Setting::get('business_name', 'Default Name');

// Set a setting with automatic cache clearing
Setting::set('business_name', 'New Company Name');

// Types will be automatically handled
Setting::set('bool_notifications_enabled', true);
$enabled = Setting::get('bool_notifications_enabled'); // Returns a boolean

// JSON is handled too
Setting::set('json_preferences', ['theme' => 'dark']);
$prefs = Setting::get('json_preferences'); // Returns an array
