<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1; // Assuming the first user ID
        
        $services = [
            [
                'name' => 'Regular Haircut',
                'duration' => 30,
                'price' => 25.00,
                'color' => '#3498db',
                'description' => 'Standard haircut service',
                'is_active' => true,
            ],
            [
                'name' => 'Deluxe Haircut & Style',
                'duration' => 60,
                'price' => 45.00,
                'color' => '#e74c3c',
                'description' => 'Haircut with professional styling',
                'is_active' => true,
            ],
            [
                'name' => 'Hair Coloring',
                'duration' => 120,
                'price' => 85.00,
                'color' => '#9b59b6',
                'description' => 'Full hair coloring service',
                'is_active' => true,
            ],
            [
                'name' => 'Quick Trim',
                'duration' => 15,
                'price' => 15.00,
                'color' => '#2ecc71',
                'description' => 'Quick trim service',
                'is_active' => true,
            ],
            [
                'name' => 'Hair Treatment',
                'duration' => 45,
                'price' => 35.00,
                'color' => '#f39c12',
                'description' => 'Deep conditioning treatment',
                'is_active' => false,
            ],
        ];
        
        foreach ($services as $serviceData) {
            Service::create(array_merge(
                $serviceData,
                ['user_id' => $userId]
            ));
        }
    }
}
