<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Client;
use App\Models\Service;
use App\Models\Appointment;
use Carbon\Carbon;

class MigrateOldData extends Command
{
    protected $signature = 'migrate:old-data {--user_id= : The ID of the user to assign the imported data to}';
    protected $description = 'Migrate data from old web_lara system to new webschedulr system';
    
    public function handle()
    {
        // Check if user_id option provided or select a user
        $userId = $this->option('user_id');
        
        if (!$userId) {
            $users = User::all(['id', 'name', 'email'])->toArray();
            if (empty($users)) {
                $this->error('No users found in the system. Please create a user first.');
                return 1;
            }
            
            $this->info('Select a user to assign the imported data:');
            foreach ($users as $index => $user) {
                $this->line("[{$user['id']}] {$user['name']} ({$user['email']})");
            }
            
            $userId = $this->ask('Enter user ID');
        }
        
        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return 1;
        }
        
        $this->info("Starting data migration for user: {$user->name}");
        
        // Test connection to old database
        try {
            DB::connection('old_mysql')->getPdo();
            $this->info('Successfully connected to old database.');
        } catch (\Exception $e) {
            $this->error('Failed to connect to old database: ' . $e->getMessage());
            return 1;
        }
        
        // Begin migration with progress bars
        DB::beginTransaction();
        
        try {
            // 1. Migrate clients
            $this->info('Migrating clients...');
            $oldClients = DB::connection('old_mysql')->table('clients')->get();
            $clientBar = $this->output->createProgressBar(count($oldClients));
            $clientBar->start();
            $clientMap = []; // To map old IDs to new IDs
            
            foreach ($oldClients as $oldClient) {
                $client = Client::create([
                    'user_id' => $userId,
                    'name' => $oldClient->name ?? 'Unknown',
                    'email' => $oldClient->email ?? null,
                    'phone' => $oldClient->phone ?? null,
                    'notes' => $oldClient->notes ?? null,
                    'status' => $oldClient->status ?? 'active',
                    'created_at' => isset($oldClient->created_at) ? Carbon::parse($oldClient->created_at) : now(),
                    'updated_at' => isset($oldClient->updated_at) ? Carbon::parse($oldClient->updated_at) : now(),
                ]);
                
                $clientMap[$oldClient->id] = $client->id;
                $clientBar->advance();
            }
            
            $clientBar->finish();
            $this->newLine();
            $this->info(count($clientMap) . ' clients migrated successfully');
            
            // 2. Migrate services
            $this->info('Migrating services...');
            $oldServices = DB::connection('old_mysql')->table('services')->get();
            $serviceBar = $this->output->createProgressBar(count($oldServices));
            $serviceBar->start();
            $serviceMap = []; // To map old IDs to new IDs
            
            foreach ($oldServices as $oldService) {
                $service = Service::create([
                    'user_id' => $userId,
                    'name' => $oldService->name ?? 'Unknown Service',
                    'duration' => $oldService->duration ?? 60,
                    'price' => $oldService->price ?? 0,
                    'color' => $oldService->color ?? '#4f46e5',
                    'description' => $oldService->description ?? null,
                    'is_active' => $oldService->is_active ?? true,
                    'created_at' => isset($oldService->created_at) ? Carbon::parse($oldService->created_at) : now(),
                    'updated_at' => isset($oldService->updated_at) ? Carbon::parse($oldService->updated_at) : now(),
                ]);
                
                $serviceMap[$oldService->id] = $service->id;
                $serviceBar->advance();
            }
            
            $serviceBar->finish();
            $this->newLine();
            $this->info(count($serviceMap) . ' services migrated successfully');
            
            // 3. Migrate appointments
            $this->info('Migrating appointments...');
            $oldAppointments = DB::connection('old_mysql')->table('appointments')->get();
            $appointmentBar = $this->output->createProgressBar(count($oldAppointments));
            $appointmentBar->start();
            $migratedAppointments = 0;
            $skippedAppointments = 0;
            
            foreach ($oldAppointments as $oldAppointment) {
                // Skip if client or service doesn't exist in new system
                if (!isset($clientMap[$oldAppointment->client_id]) || 
                    !isset($serviceMap[$oldAppointment->service_id])) {
                    $skippedAppointments++;
                    $appointmentBar->advance();
                    continue;
                }
                
                try {
                    Appointment::create([
                        'user_id' => $userId,
                        'client_id' => $clientMap[$oldAppointment->client_id],
                        'service_id' => $serviceMap[$oldAppointment->service_id],
                        'start_time' => Carbon::parse($oldAppointment->start_time),
                        'end_time' => Carbon::parse($oldAppointment->end_time),
                        'status' => $oldAppointment->status ?? 'pending',
                        'notes' => $oldAppointment->notes ?? null,
                        'is_recurring' => $oldAppointment->is_recurring ?? false,
                        'recurring_group_id' => $oldAppointment->recurring_group_id ?? null,
                        'created_at' => isset($oldAppointment->created_at) ? Carbon::parse($oldAppointment->created_at) : now(),
                        'updated_at' => isset($oldAppointment->updated_at) ? Carbon::parse($oldAppointment->updated_at) : now(),
                    ]);
                    
                    $migratedAppointments++;
                } catch (\Exception $e) {
                    $this->warn("Error migrating appointment {$oldAppointment->id}: {$e->getMessage()}");
                    $skippedAppointments++;
                }
                
                $appointmentBar->advance();
            }
            
            $appointmentBar->finish();
            $this->newLine();
            $this->info($migratedAppointments . ' appointments migrated successfully');
            if ($skippedAppointments > 0) {
                $this->warn($skippedAppointments . ' appointments skipped due to missing clients or services');
            }
            
            // Commit the transaction
            DB::commit();
            
            $this->info('Data migration completed successfully');
        } catch (\Exception $e) {
            // Rollback in case of error
            DB::rollback();
            $this->error('Migration failed: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
