<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::query()
            ->with(['client', 'service'])
            ->where('user_id', Auth::id());
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('client', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                })
                ->orWhereHas('service', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }
        
        // Apply date range filter
        if ($request->filled('dateRange')) {
            switch ($request->dateRange) {
                case 'today':
                    $query->whereDate('start_time', now()->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('start_time', [
                        now()->startOfWeek()->toDateTimeString(),
                        now()->endOfWeek()->toDateTimeString()
                    ]);
                    break;
                case 'month':
                    $query->whereBetween('start_time', [
                        now()->startOfMonth()->toDateTimeString(),
                        now()->endOfMonth()->toDateTimeString()
                    ]);
                    break;
                // 'all' or default: no date filtering
            }
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Default sort by start_time descending
        $query->orderBy('start_time', 'desc');
        
        $appointments = $query->paginate(10)->withQueryString();
        
        return Inertia::render('Appointments/Index', [
            'appointments' => $appointments,
            'filters' => $request->only(['search', 'dateRange', 'status'])
        ]);
    }

    public function create(Request $request)
    {
        $clients = Client::where('user_id', Auth::id())->orderBy('name')->get();
        $services = Service::where('user_id', Auth::id())->where('is_active', true)->orderBy('name')->get();
        
        return Inertia::render('Appointments/Create', [
            'clients' => $clients,
            'services' => $services,
            'preselectedClient' => $request->has('client_id') ? Client::find($request->client_id) : null,
            'preselectedService' => $request->has('service_id') ? Service::find($request->service_id) : null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $appointment = new Appointment($validated);
        $appointment->user_id = Auth::id();
        $appointment->save();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment created successfully.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        
        return Inertia::render('Appointments/Show', [
            'appointment' => $appointment->load(['client', 'service']),
        ]);
    }

    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        return Inertia::render('Appointments/Edit', [
            'appointment' => $appointment,
            'clients' => Client::where('user_id', Auth::id())->orderBy('name')->get(),
            'services' => Service::where('user_id', Auth::id())->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    public function updateStatus(Appointment $appointment, Request $request)
    {
        // Validate
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);
        
        // Update
        $appointment->status = $request->status;
        $appointment->save();
        
        // Return proper response
        return response()->json(['success' => true]);
    }
}