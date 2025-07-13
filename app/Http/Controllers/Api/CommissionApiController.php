<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCommissionRequest;
use App\Http\Requests\Api\UpdateCommissionRequest;
use App\Http\Resources\CommissionResource;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommissionApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Commission::with(['user', 'lead']);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('lead_id')) {
            $query->where('lead_id', $request->lead_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('lead', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        // Sort by created_at desc by default
        $query->orderBy($request->get('sort_by', 'created_at'), $request->get('sort_direction', 'desc'));

        $commissions = $query->paginate($request->get('per_page', 15));

        return CommissionResource::collection($commissions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommissionRequest $request)
    {
        $commission = Commission::create($request->validated());
        $commission->load(['user', 'lead']);

        return new CommissionResource($commission);
    }

    /**
     * Display the specified resource.
     */
    public function show(Commission $commission)
    {
        $commission->load(['user', 'lead']);
        return new CommissionResource($commission);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommissionRequest $request, Commission $commission)
    {
        $commission->update($request->validated());
        $commission->load(['user', 'lead']);

        return new CommissionResource($commission);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commission $commission)
    {
        $commission->delete();

        return response()->json([
            'message' => 'Commission deleted successfully'
        ], Response::HTTP_NO_CONTENT);
    }
}
