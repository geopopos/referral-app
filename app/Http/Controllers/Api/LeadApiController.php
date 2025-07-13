<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreLeadRequest;
use App\Http\Requests\Api\UpdateLeadRequest;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LeadApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Lead::with(['referrer', 'commissions']);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('referrer_id')) {
            $query->where('referrer_id', $request->referrer_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        // Sort by created_at desc by default
        $query->orderBy($request->get('sort_by', 'created_at'), $request->get('sort_direction', 'desc'));

        $leads = $query->paginate($request->get('per_page', 15));

        return LeadResource::collection($leads);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeadRequest $request)
    {
        $lead = Lead::create($request->validated());
        $lead->load(['referrer', 'commissions']);

        return new LeadResource($lead);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        $lead->load(['referrer', 'commissions']);
        return new LeadResource($lead);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        $lead->update($request->validated());
        $lead->load(['referrer', 'commissions']);

        return new LeadResource($lead);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return response()->json([
            'message' => 'Lead deleted successfully'
        ], Response::HTTP_NO_CONTENT);
    }
}
