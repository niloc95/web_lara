<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
// Add these imports if you have these resource classes
// use App\Http\Resources\ClientResource;
// use App\Http\Resources\AppointmentResource;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query()
            ->where('user_id', Auth::id())
            ->withCount('appointments');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $clients = $query->orderBy('name')->paginate(10)->withQueryString();
        
        return Inertia::render('Clients/Index', [
            'clients' => $clients,
            'filters' => $request->only(['search', 'status'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Clients/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'birth_date' => 'nullable|date',
        ]);

        $client = new Client($validated);
        $client->user_id = Auth::id();
        $client->save();

        return redirect()->route('clients.index')
            ->with('success', 'Client created successfully.');
    }

    public function edit(Client $client)
    {
        $this->authorize('update', $client);
        
        // Fetch the client's appointments
        $appointments = $client->appointments()
            ->with(['service:id,name,color']) // Only select needed fields
            ->latest()
            ->get();
        
        return Inertia::render('Clients/Edit', [
            'client' => $client,
            'appointments' => $appointments,
            // Any other data you're passing
        ]);
    }

    public function update(Request $request, Client $client)
    {
        $this->authorize('update', $client);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'birth_date' => 'nullable|date',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $this->authorize('delete', $client);
        
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }

    public function show(Client $client, $name = null)
    {
        $this->authorize('view', $client);
        
        return Inertia::render('Clients/Show', [
            'client' => $client,
            'appointments' => $client->appointments()
                ->with('service')
                ->orderBy('start_time', 'desc')
                ->get()
        ]);
    }
}