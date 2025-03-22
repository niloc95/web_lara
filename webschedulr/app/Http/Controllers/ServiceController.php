<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Service::query()
            ->where('user_id', Auth::id())
            ->withCount('appointments');
            
        // Apply search if provided
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by active status
        if ($request->has('is_active') && $request->is_active !== 'all') {
            $query->where('is_active', $request->is_active === 'true');
        }
        
        $services = $query->orderBy('name', 'asc')->get();
        
        return Inertia::render('Services/Index', [
            'services' => $services,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Services/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:5',
            'price' => 'nullable|numeric|min:0',
            'color' => 'required|string|max:7',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        // Add user_id to validated data
        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $validated['is_active'] ?? true;
        
        $service = Service::create($validated);
        
        return redirect()->route('services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        // Authorize that the user can edit this service
        $this->authorize('update', $service);
        
        return Inertia::render('Services/Edit', [
            'service' => $service
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        // Authorize that the user can update this service
        $this->authorize('update', $service);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:5',
            'price' => 'nullable|numeric|min:0',
            'color' => 'required|string|max:7',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $service->update($validated);
        
        return redirect()->route('services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        // Authorize that the user can delete this service
        $this->authorize('delete', $service);
        
        // Check if service has appointments
        $appointmentCount = Appointment::where('service_id', $service->id)->count();
        
        if ($appointmentCount > 0 && !$request->has('force_delete')) {
            return back()
                ->with('error', 
                    'This service is used in appointments. Update the appointments first or use force delete.');
        }
        
        $service->delete();
        
        return redirect()->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }
    
    /**
     * Toggle active status of the service.
     */
    public function toggleActive(Service $service)
    {
        // Authorize that the user can update this service
        $this->authorize('update', $service);
        
        $service->update([
            'is_active' => !$service->is_active
        ]);
        
        return back()->with('success', 
            $service->is_active 
                ? 'Service has been activated.' 
                : 'Service has been deactivated.'
        );
    }
}