<?php

namespace App\Http\Controllers;

use App\Models\Usulan;
use App\Http\Requests\StoreUsulanRequest;
use App\Http\Requests\UpdateUsulanRequest;
use App\Services\UsulanService;
use Illuminate\Http\JsonResponse;

class UsulanController extends Controller
{
    protected $usulanService;

    public function __construct(UsulanService $usulanService)
    {
        $this->usulanService = $usulanService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        // Add specific query logic as needed (e.g. filtering by id_user)
        $usulans = Usulan::with('details.berkas')->latest()->paginate(10);
        
        return response()->json([
            'status' => 'success',
            'data' => $usulans
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUsulanRequest $request): JsonResponse
    {
        try {
            // Assuming user is authenticated. 
            // In a real scenario you would get it via auth()->id()
            $userId = auth()->id() ?? 1; // Fallback to 1 for testing if not auth
            
            $usulan = $this->usulanService->createUsulan($request->validated(), $userId);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Usulan created successfully.',
                'data' => $usulan
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create usulan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $usulan = Usulan::with('details.berkas')->findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'data' => $usulan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUsulanRequest $request, string $id): JsonResponse
    {
         try {
             $usulan = $this->usulanService->updateUsulan($id, $request->validated());
             
             return response()->json([
                 'status' => 'success',
                 'message' => 'Usulan updated successfully.',
                 'data' => $usulan
             ]);
             
         } catch (\Exception $e) {
             return response()->json([
                 'status' => 'error',
                 'message' => 'Failed to update usulan.',
                 'error' => $e->getMessage()
             ], 500);
         }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->usulanService->deleteUsulan($id);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Usulan deleted successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete usulan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
