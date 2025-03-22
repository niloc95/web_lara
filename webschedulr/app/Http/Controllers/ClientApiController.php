<?php
// app/Http/Controllers/ClientApiController.php
namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ClientApiController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate client data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:255',
            ]);
            
            // Create new client
            $client = new Client($validated);
            $client->user_id = Auth::id();
            $client->save();
            
            // For JSON requests (axios)
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Client created successfully',
                    'client' => $client
                ], 201);
            }
            
            // For Inertia requests, redirect with flash data
            return redirect()->back()->with([
                'success' => 'Client created successfully',
                'client' => $client
            ]);
            
        } catch (ValidationException $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->back()->withErrors($e->errors());
            
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while creating the client',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'An error occurred while creating the client');
        }
    }
}