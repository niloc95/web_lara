<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1; // Assuming the first user ID
        $clients = Client::where('user_id', $userId)->get();
        $services = Service::where('user_id', $userId)->get();
        
        if ($clients->isEmpty() || $services->isEmpty()) {
            return; // No clients or services to create appointments for
        }
        
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        $today = Carbon::today();
        
        // Past appointments (last 30 days)
        for ($i = 1; $i <= 15; $i++) {
            $client = $clients->random();
            $service = $services->random();
            $pastDay = $today->copy()->subDays(rand(1, 30));
            $startTime = $pastDay->copy()->setHour(rand(9, 16))->setMinute(rand(0, 3) * 15);
            $endTime = $startTime->copy()->addMinutes($service->duration);
            
            // More likely to be completed or cancelled since they're in the past
            $status = rand(0, 10) > 2 ? 'completed' : (rand(0, 10) > 7 ? 'cancelled' : 'pending');
            
            Appointment::create([
                'user_id' => $userId,
                'client_id' => $client->id,
                'service_id' => $service->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => $status,
                'notes' => rand(0, 5) > 3 ? 'Sample appointment notes' : null,
                'is_recurring' => false,
            ]);
        }
        
        // Today's appointments
        for ($i = 1; $i <= 5; $i++) {
            $client = $clients->random();
            $service = $services->random();
            $startTime = $today->copy()->setHour(9 + $i * 2)->setMinute(rand(0, 3) * 15);
            $endTime = $startTime->copy()->addMinutes($service->duration);
            
            $currentHour = Carbon::now()->hour;
            $status = $startTime->hour < $currentHour ? 'completed' : ($startTime->hour === $currentHour ? 'confirmed' : 'pending');
            
            Appointment::create([
                'user_id' => $userId,
                'client_id' => $client->id,
                'service_id' => $service->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => $status,
                'notes' => rand(0, 5) > 3 ? 'Today\'s appointment' : null,
                'is_recurring' => false,
            ]);
        }
        
        // Future appointments (next 30 days)
        for ($i = 1; $i <= 20; $i++) {
            $client = $clients->random();
            $service = $services->random();
            $futureDay = $today->copy()->addDays(rand(1, 30));
            $startTime = $futureDay->copy()->setHour(rand(9, 16))->setMinute(rand(0, 3) * 15);
            $endTime = $startTime->copy()->addMinutes($service->duration);
            
            // More likely to be pending or confirmed since they're in the future
            $status = rand(0, 10) > 7 ? 'pending' : 'confirmed';
            
            Appointment::create([
                'user_id' => $userId,
                'client_id' => $client->id,
                'service_id' => $service->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => $status,
                'notes' => rand(0, 5) > 3 ? 'Future appointment' : null,
                'is_recurring' => false,
            ]);
        }
    }
}
