<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all clients, services, and users to randomly assign
        $clients = Client::all();
        $services = Service::all();
        $users = User::all();
        
        // Make sure we have clients, services, and users
        if ($clients->isEmpty()) {
            $this->command->error('No clients found. Please seed clients first.');
            return;
        }
        
        if ($services->isEmpty()) {
            $this->command->error('No services found. Please seed services first.');
            return;
        }
        
        if ($users->isEmpty()) {
            $this->command->error('No users found. Please seed users first.');
            return;
        }
        
        // Get today's date and end of year
        $today = Carbon::today();
        $endOfYear = Carbon::parse($today->year . '-12-31');
        
        // Status options
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        
        // For each day until end of year
        for ($date = $today->copy(); $date->lte($endOfYear); $date->addDay()) {
            $this->command->info("Creating appointments for: " . $date->format('Y-m-d'));
            
            // Create 5 appointments for each day
            for ($i = 1; $i <= 6; $i++) {
                // Generate a random hour between 9 AM and 5 PM
                $hour = rand(9, 16);
                $minute = [0, 15, 30, 45][rand(0, 3)]; // 15-minute intervals
                
                // Create start time
                $startTime = $date->copy()->setHour($hour)->setMinute($minute)->setSecond(0);
                
                // Create end time (1 hour after start time)
                $endTime = $startTime->copy()->addHour();
                
                // Randomly select client, service, and user
                $client = $clients->random();
                $service = $services->random();
                $user = $users->random();
                
                // Randomly select status, weighted toward confirmed
                $weights = [15, 60, 20, 5]; // pending, confirmed, completed, cancelled
                $randomNumber = rand(1, 100);
                $cumulativeWeight = 0;
                $status = $statuses[0];
                
                for ($j = 0; $j < count($weights); $j++) {
                    $cumulativeWeight += $weights[$j];
                    if ($randomNumber <= $cumulativeWeight) {
                        $status = $statuses[$j];
                        break;
                    }
                }
                
                // For dates in the past, never use 'pending'
                if ($date->lt($today) && $status === 'pending') {
                    $status = 'completed';
                }
                
                // Create the appointment
                Appointment::create([
                    'client_id' => $client->id,
                    'service_id' => $service->id,
                    'user_id' => $user->id, // Add the user_id
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'status' => $status,
                    'notes' => 'Auto-generated appointment #' . $i . ' for ' . $date->format('Y-m-d'),
                ]);
            }
        }
        
        $this->command->info('Successfully created appointments until the end of the year!');
    }
}