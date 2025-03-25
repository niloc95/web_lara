<?php
// database/seeders/AppointmentTestDataSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppointmentTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Turn off foreign key checks to allow truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing data
        $this->command->info('Clearing existing appointments and clients...');
        Appointment::truncate();
        Client::truncate();
        
        // Turn foreign key checks back on
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Check if we have a user to assign clients to
        $user = User::first();
        $userId = $user ? $user->id : null;
        
        // If no users exist, create one
        if (!$userId) {
            $this->command->info('No users found, creating a default user...');
            $user = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
            $userId = $user->id;
        }
        
        // Create services if needed
        if (Service::count() === 0) {
            $this->createBasicServices();
        }
        
        // Get all services for assigning to appointments
        $services = Service::all();
        
        // Create 200 clients
        $this->command->info('Creating 200 new clients...');
        
        $clients = [];
        for ($i = 1; $i <= 200; $i++) {
            $clients[] = [
                'name' => $this->getRandomName(),
                'email' => "client{$i}@example.com",
                'phone' => $this->getRandomPhone(),
                'user_id' => $userId, // Add the user_id field
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            if ($i % 50 === 0) {
                Client::insert($clients);
                $clients = [];
                $this->command->info("Created {$i} clients");
            }
        }
        
        // Insert any remaining clients
        if (!empty($clients)) {
            Client::insert($clients);
        }
        
        // Get all clients for assigning to appointments
        $clientIds = Client::pluck('id')->toArray();
        
        // Create appointments - 3 per day until Dec 31, 2025
        $this->command->info('Creating appointments (3 per day until Dec 31, 2025)...');
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::parse('2025-12-31');
        $dayCount = $startDate->diffInDays($endDate);
        
        $appointments = [];
        $appointmentCount = 0;
        
        for ($day = 0; $day <= $dayCount; $day++) {
            $date = $startDate->copy()->addDays($day);
            
            // Skip appointments on weekends if you want
            // if ($date->isWeekend()) continue;
            
            // Create 3 appointments for this day
            for ($i = 0; $i < 3; $i++) {
                $startHour = rand(9, 16); // Between 9am and 4pm
                $appointmentStart = $date->copy()->setHour($startHour)->setMinute(rand(0, 3) * 15);
                $duration = rand(1, 4) * 15; // 15, 30, 45, or 60 minutes
                $appointmentEnd = $appointmentStart->copy()->addMinutes($duration);
                
                $serviceId = $services->random()->id;
                $clientId = $clientIds[array_rand($clientIds)];
                $status = $this->getRandomStatus();
                
                $appointments[] = [
                    'client_id' => $clientId,
                    'service_id' => $serviceId,
                    'start_time' => $appointmentStart,
                    'end_time' => $appointmentEnd,
                    'status' => $status,
                    'notes' => "Test appointment #{$appointmentCount}",
                    'user_id' => $userId, // Add this line to include user_id
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $appointmentCount++;
                
                // Insert in batches to avoid memory issues
                if (count($appointments) >= 100) {
                    Appointment::insert($appointments);
                    $appointments = [];
                    $this->command->info("Created {$appointmentCount} appointments");
                }
            }
        }
        
        // Insert any remaining appointments
        if (!empty($appointments)) {
            Appointment::insert($appointments);
        }
        
        $this->command->info("Completed! Created {$appointmentCount} appointments for 200 clients.");
    }
    
    /**
     * Create basic services if none exist
     */
    private function createBasicServices()
    {
        $services = [
            ['name' => 'Haircut', 'duration' => 30, 'price' => 25.00, 'color' => '#4CAF50'],
            ['name' => 'Hair Coloring', 'duration' => 90, 'price' => 65.00, 'color' => '#2196F3'],
            ['name' => 'Manicure', 'duration' => 45, 'price' => 35.00, 'color' => '#FFC107'],
            ['name' => 'Pedicure', 'duration' => 60, 'price' => 45.00, 'color' => '#9C27B0'],
            ['name' => 'Facial', 'duration' => 60, 'price' => 55.00, 'color' => '#E91E63'],
        ];
        
        foreach ($services as $service) {
            Service::create($service);
        }
    }
    
    /**
     * Generate a random name
     */
    private function getRandomName()
    {
        $firstNames = ['Nilo','Simran','Shriya','Sitori','John', 'Jane', 'Michael', 'Emily', 'David', 'Sarah', 'Robert', 'Linda', 'William', 'Patricia', 'James', 'Jennifer', 'Charles', 'Mary', 'Joseph', 'Elizabeth', 'Thomas', 'Susan', 'Daniel', 'Jessica'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Garcia', 'Rodriguez', 'Wilson', 'Martinez', 'Anderson', 'Taylor', 'Thomas', 'Hernandez', 'Moore', 'Martin', 'Jackson', 'Thompson', 'White'];
        
        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }
    
    /**
     * Generate a random phone number
     */
    private function getRandomPhone()
    {
        return sprintf('(%d) %d-%d', 
            rand(200, 999), 
            rand(200, 999), 
            rand(1000, 9999)
        );
    }
    
    /**
     * Get a random appointment status
     */
    private function getRandomStatus()
    {
        $statuses = ['confirmed', 'pending', 'completed', 'cancelled'];
        $weights = [70, 15, 10, 5]; // Higher probability of confirmed appointments
        
        return $this->getRandomWeighted($statuses, $weights);
    }
    
    /**
     * Get random element with weights
     */
    private function getRandomWeighted($items, $weights)
    {
        $totalWeight = array_sum($weights);
        $rand = mt_rand(1, $totalWeight);
        
        $currentWeight = 0;
        foreach ($items as $index => $item) {
            $currentWeight += $weights[$index];
            if ($rand <= $currentWeight) {
                return $item;
            }
        }
        
        return $items[0]; // Fallback
    }
}