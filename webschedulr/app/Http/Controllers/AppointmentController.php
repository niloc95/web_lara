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
            ->where('user_id', Auth::id())
            ->with(['client', 'service']);
        
        $appointments = $query->orderBy('start_time', 'desc')->paginate(10);
        
        return Inertia::render('Appointments/Index', [
            'appointments' => $appointments
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
        $this->authorize('update', $appointment);
        
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,confirmed,completed,cancelled'],
        ]);
        
        $appointment->update([
            'status' => $validated['status']
        ]);
        
        return back()->with('success', 'Appointment status updated successfully');
    }
}