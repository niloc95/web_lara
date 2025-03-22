<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ClientSeeder::class,
            ServiceSeeder::class,
            AppointmentSeeder::class,
        ]);
    }
}
