<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $dashboardData = $this->getDashboardStats();
        
        return Inertia::render('Dashboard', [
            'stats' => $dashboardData['stats'],
            'todaysAppointments' => $dashboardData['todaysAppointments'],
            'appointmentsByDay' => $dashboardData['appointmentsByDay'],
            'statusStats' => $dashboardData['statusStats'],
            'topServices' => [] 
        ]);
    }

    private function getDashboardStats()
    {
        $user = Auth::user();
        
        // Initialize with empty data to prevent errors
        $stats = [
            'upcoming_appointments' => 0,
            'today_appointments' => 0,
            'tomorrow_appointments' => 0,
        ];
        
        $todaysAppointments = [];
        $statusStats = [
            'labels' => [],
            'data' => [],
            'backgrounds' => []
        ];
        $appointmentsByDay = [
            'labels' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            'data' => [0, 0, 0, 0, 0, 0, 0]
        ];
        $recentClients = [];
        
        try {
            // Basic stats
            $stats = [
                'upcoming_appointments' => Appointment::where('user_id', $user->id)
                    ->where('start_time', '>=', now())
                    ->count(),
                'today_appointments' => Appointment::where('user_id', $user->id)
                    ->whereDate('start_time', today())
                    ->count(),
                'tomorrow_appointments' => Appointment::where('user_id', $user->id)
                    ->whereDate('start_time', today()->addDay())
                    ->count(),
            ];
            
            // Today's appointments
            $todaysAppointments = Appointment::where('user_id', $user->id)
                ->whereDate('start_time', today())
                ->with(['client', 'service'])
                ->orderBy('start_time')
                ->get();
            
            // Status chart data
            $statusCounts = Appointment::where('user_id', $user->id)
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
            
            $backgrounds = [
                'pending' => '#f6c23e',    // yellow
                'confirmed' => '#1cc88a',  // green
                'completed' => '#4e73df',  // blue
                'cancelled' => '#e74a3b',  // red
            ];
            
            foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status) {
                if (isset($statusCounts[$status]) && $statusCounts[$status] > 0) {
                    $statusStats['labels'][] = ucfirst($status);
                    $statusStats['data'][] = $statusCounts[$status];
                    $statusStats['backgrounds'][] = $backgrounds[$status]; 
                }
            }
            
            // Day of week distribution
            $dayCounts = Appointment::where('user_id', $user->id)
                ->select(DB::raw('DAYOFWEEK(start_time) - 1 as day_index'), DB::raw('count(*) as count'))
                ->groupBy('day_index')
                ->pluck('count', 'day_index')
                ->toArray();
            
            foreach ($dayCounts as $dayIndex => $count) {
                if (isset($appointmentsByDay['data'][$dayIndex])) {
                    $appointmentsByDay['data'][$dayIndex] = $count;
                }
            }
            
            // Recent clients
            $recentClients = Client::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            // Log the error but don't throw it
            \Log::error('Dashboard data error: ' . $e->getMessage());
        }
        
        return [
            'stats' => $stats,
            'todaysAppointments' => $todaysAppointments,
            'statusStats' => $statusStats,
            'appointmentsByDay' => $appointmentsByDay,
            'recentClients' => $recentClients,
        ];
    }
}