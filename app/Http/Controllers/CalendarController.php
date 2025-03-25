<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use App\Models\BusinessSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ? intval($request->month) - 1 : Carbon::now()->month - 1;
        $year = $request->year ?: Carbon::now()->year;
        
        $startOfMonth = Carbon::createFromDate($year, $month + 1, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month + 1, 1)->endOfMonth();
        
        // We'll extend a bit before and after to show full weeks
        $startDate = $startOfMonth->copy()->subDays($startOfMonth->dayOfWeek);
        $endDate = $endOfMonth->copy()->addDays(6 - $endOfMonth->dayOfWeek);
        
        $appointments = Appointment::where('user_id', Auth::id())
            ->whereBetween('start_time', [$startDate, $endDate])
            ->with(['client', 'service'])
            ->get();
        
        return Inertia::render('Calendar/Index', [
            'appointments' => $appointments,
            'currentDate' => Carbon::createFromDate($year, $month + 1, 1)->toDateString()
        ]);
    }
    
    public function day(Request $request, $date = null)
    {
        $viewDate = $date ? Carbon::parse($date) : Carbon::today();
        
        $appointments = Appointment::where('user_id', Auth::id())
            ->whereDate('start_time', $viewDate)
            ->with(['client', 'service'])
            ->orderBy('start_time')
            ->get();
        
        return Inertia::render('Calendar/Index', [
            'appointments' => $appointments,
            'currentDate' => $viewDate->toDateString(),
            'viewMode' => 'day'
        ]);
    }
    
    public function week(Request $request, $date = null)
    {
        $viewDate = $date ? Carbon::parse($date) : Carbon::today();
        
        $startOfWeek = $viewDate->copy()->startOfWeek(Carbon::SUNDAY);
        $endOfWeek = $viewDate->copy()->endOfWeek(Carbon::SATURDAY);
        
        $appointments = Appointment::where('user_id', Auth::id())
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->with(['client', 'service'])
            ->orderBy('start_time')
            ->get();
        
        return Inertia::render('Calendar/Index', [
            'appointments' => $appointments,
            'currentDate' => $viewDate->toDateString(),
            'viewMode' => 'week'
        ]);
    }
    
    public function month(Request $request, $date = null)
    {
        return $this->index($request);
    }
    
    /**
     * Get the user's calendar settings.
     */
    private function getUserSettings()
    {
        $settings = BusinessSetting::where('user_id', Auth::id())
            ->get()
            ->pluck('value', 'key')
            ->toArray();
            
        return array_merge([
            'business_hours_start' => '09:00',
            'business_hours_end' => '17:00',
            'slot_duration' => 15,
            'working_days' => '1,2,3,4,5', // Monday to Friday
        ], $settings);
    }
    
    /**
     * Get availability for a specific date.
     */
    public function getAvailability(Request $request)
    {
        $date = Carbon::parse($request->date);
        
        // Get user settings
        $settings = $this->getUserSettings();
        
        // Check if this is a working day
        $dayOfWeek = $date->dayOfWeek;
        $workingDays = explode(',', $settings['working_days'] ?? '1,2,3,4,5');
        
        if (!in_array($dayOfWeek, $workingDays)) {
            return response()->json([
                'available' => false,
                'message' => 'Not a working day',
                'slots' => []
            ]);
        }
        
        // Get business hours
        $businessStart = Carbon::parse($settings['business_hours_start'])->format('H:i');
        $businessEnd = Carbon::parse($settings['business_hours_end'])->format('H:i');
        
        // Get all appointments for the date
        $appointments = Appointment::where('user_id', Auth::id())
            ->whereDate('start_time', $date)
            ->get();
            
        // Generate time slots
        $slotDuration = intval($settings['slot_duration'] ?? 15);
        $slots = $this->generateTimeSlots($date, $businessStart, $businessEnd, $slotDuration, $appointments);
        
        return response()->json([
            'available' => true,
            'date' => $date->format('Y-m-d'),
            'slots' => $slots
        ]);
    }
    
    /**
     * Generate available time slots for a date.
     */
    private function generateTimeSlots($date, $businessStart, $businessEnd, $slotDuration, $appointments)
    {
        // Create date objects for start and end times
        $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $businessStart);
        $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $businessEnd);
        
        // Create array of all possible time slots
        $slots = [];
        $current = $startTime->copy();
        
        while ($current < $endTime) {
            $slotEnd = $current->copy()->addMinutes($slotDuration);
            
            // Check if this slot conflicts with any appointments
            $available = true;
            foreach ($appointments as $appointment) {
                $appointmentStart = Carbon::parse($appointment->start_time);
                $appointmentEnd = Carbon::parse($appointment->end_time);
                
                // Check for overlap
                if (
                    ($current >= $appointmentStart && $current < $appointmentEnd) || 
                    ($slotEnd > $appointmentStart && $slotEnd <= $appointmentEnd) ||
                    ($current <= $appointmentStart && $slotEnd >= $appointmentEnd)
                ) {
                    $available = false;
                    break;
                }
            }
            
            // Add slot to array
            $slots[] = [
                'time' => $current->format('H:i'),
                'available' => $available
            ];
            
            // Move to next slot
            $current->addMinutes($slotDuration);
        }
        
        return $slots;
    }
    
    /**
     * Export calendar data for the selected range.
     */
    public function export(Request $request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        $appointments = Appointment::query()
            ->with(['client', 'service'])
            ->where('user_id', Auth::id())
            ->forDateRange($startDate, $endDate)
            ->get();
            
        // Format data for export
        $exportData = $appointments->map(function($appointment) {
            return [
                'client' => $appointment->client->name,
                'service' => $appointment->service->name,
                'start_time' => Carbon::parse($appointment->start_time)->format('Y-m-d H:i:s'),
                'end_time' => Carbon::parse($appointment->end_time)->format('Y-m-d H:i:s'),
                'status' => $appointment->status,
                'notes' => $appointment->notes
            ];
        });
        
        // Return as JSON or CSV based on request format
        $format = $request->format ?? 'json';
        
        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="calendar-export.csv"',
            ];
            
            $callback = function() use ($exportData) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Client', 'Service', 'Start Time', 'End Time', 'Status', 'Notes']);
                
                foreach ($exportData as $row) {
                    fputcsv($file, [
                        $row['client'],
                        $row['service'],
                        $row['start_time'],
                        $row['end_time'],
                        $row['status'],
                        $row['notes']
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }
        
        return response()->json($exportData);
    }
    
    /**
     * Return a public view of the calendar for embedding.
     */
    public function publicView($userId, $token)
    {
        // Verify token matches the user's public calendar token
        $setting = BusinessSetting::where('user_id', $userId)
            ->where('key', 'public_calendar_token')
            ->first();
            
        if (!$setting || $setting->value !== $token) {
            abort(404);
        }
        
        // Get settings for this business
        $settings = BusinessSetting::where('user_id', $userId)
            ->get()
            ->pluck('value', 'key')
            ->toArray();
        
        $businessName = $settings['business_name'] ?? 'Calendar';
        
        // Get the date to display
        $date = request()->date ? Carbon::parse(request()->date) : Carbon::today();
        $view = request()->view ?? 'week';
        
        // Get services for this business (only active ones)
        $services = Service::where('user_id', $userId)
            ->where('is_active', true)
            ->get();
            
        // For the public view, we don't show client details
        $appointments = Appointment::where('user_id', $userId)
            ->where('status', '!=', 'cancelled')
            ->when($view === 'day', function($query) use ($date) {
                return $query->whereDate('start_time', $date);
            })
            ->when($view === 'week', function($query) use ($date) {
                $startOfWeek = $date->copy()->startOfWeek(Carbon::SUNDAY);
                $endOfWeek = $date->copy()->endOfWeek(Carbon::SATURDAY);
                return $query->whereBetween('start_time', [$startOfWeek, $endOfWeek]);
            })
            ->when($view === 'month', function($query) use ($date) {
                $startDate = $date->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
                $endDate = $date->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);
                return $query->whereBetween('start_time', [$startDate, $endDate]);
            })
            ->with('service')
            ->get()
            ->map(function($appointment) {
                // Mask client information for privacy
                return [
                    'id' => $appointment->id,
                    'start_time' => $appointment->start_time,
                    'end_time' => $appointment->end_time,
                    'status' => $appointment->status,
                    'service' => $appointment->service,
                    // Don't include client details
                ];
            });
            
        return view('calendar.public', compact(
            'businessName', 
            'date', 
            'view', 
            'services', 
            'appointments'
        ));
    }

    private function generateDaysArray($start, $end)
    {
        $days = [];
        $current = $start->copy();
        
        while ($current <= $end) {
            $days[] = [
                'date' => $current->format('Y-m-d'),
                'day' => $current->format('d'),
                'dayName' => $current->format('l'),
                'isToday' => $current->isToday(),
            ];
            
            $current->addDay();
        }
        
        return $days;
    }
    
    private function generateCalendarGrid($date)
    {
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        
        $startOfCalendar = $startOfMonth->copy()->startOfWeek();
        $endOfCalendar = $endOfMonth->copy()->endOfWeek();
        
        return $this->generateDaysArray($startOfCalendar, $endOfCalendar);
    }
}