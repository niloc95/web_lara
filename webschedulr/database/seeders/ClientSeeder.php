<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1; // Assuming the first user ID
        
        $clients = [
            [
                'name' => 'John Smith',
                'email' => 'john@example.com',
                'phone' => '555-123-4567',
                'notes' => 'Prefers afternoon appointments',
                'status' => 'active',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@example.com',
                'phone' => '555-234-5678',
                'notes' => 'Allergic to certain products',
                'status' => 'active',
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael@example.com',
                'phone' => '555-345-6789',
                'notes' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Emma Wilson',
                'email' => 'emma@example.com',
                'phone' => '555-456-7890',
                'notes' => 'New client',
                'status' => 'active',
            ],
            [
                'name' => 'David Garcia',
                'email' => 'david@example.com',
                'phone' => '555-567-8901',
                'notes' => 'Referred by Sarah Johnson',
                'status' => 'inactive',
            ],
        ];
        
        foreach ($clients as $clientData) {
            Client::create(array_merge(
                $clientData,
                ['user_id' => $userId]
            ));
        }
    }
}
