<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'business_name',
        'business_email',
        'business_phone',
        'business_address',
        'business_hours',
        'appointment_lead_time',
        'appointment_duration',
        'notification_email',
        'logo', // Make sure 'logo' is included here
    ];

    protected $casts = [
        'business_hours' => 'array',
        'notification_email' => 'boolean',
    ];
}
