<?php
// Create ClientResource.php and AppointmentResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            // Exclude notes or other large/sensitive fields
            'status' => $this->status,
            // Include only summary counts instead of full data
            'appointment_count' => $this->appointments->count(),
            'latest_appointment' => optional($this->appointments->first())->start_time
        ];
    }
}

// app/Http/Resources/AppointmentResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'service' => [
                'id' => $this->service->id,
                'name' => $this->service->name,
                'color' => $this->service->color,
            ],
        ];
    }
}