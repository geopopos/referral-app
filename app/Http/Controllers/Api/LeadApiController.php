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
     * @OA\Get(
     *     path="/api/admin/leads",
     *     tags={"Leads"},
     *     summary="List leads",
     *     description="Get a paginated list of leads with filtering and search capabilities",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by lead status",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"new", "contacted", "qualified", "appointment_scheduled", "proposal_sent", "closed", "lost"}
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="referrer_id",
     *         in="query",
     *         description="Filter by referrer user ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search in name, email, or company",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Field to sort by",
     *         required=false,
     *         @OA\Schema(type="string", default="created_at")
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         description="Sort direction",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"}, default="desc")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of results per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of leads",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Lead")
     *             ),
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=5),
     *             @OA\Property(property="per_page", type="integer", example=15),
     *             @OA\Property(property="total", type="integer", example=67)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Access denied. Admin privileges required.")
     *         )
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/admin/leads",
     *     tags={"Leads"},
     *     summary="Create lead",
     *     description="Create a new lead",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="company", type="string", example="Acme Corp"),
     *             @OA\Property(property="status", type="string", enum={"new", "contacted", "qualified", "appointment_scheduled", "proposal_sent", "closed", "lost"}, example="new"),
     *             @OA\Property(property="notes", type="string", example="Interested in roofing services"),
     *             @OA\Property(property="referrer_id", type="integer", example=2),
     *             @OA\Property(property="source", type="string", example="website"),
     *             @OA\Property(property="utm_source", type="string", example="google"),
     *             @OA\Property(property="utm_medium", type="string", example="cpc"),
     *             @OA\Property(property="utm_campaign", type="string", example="summer2024"),
     *             @OA\Property(property="utm_term", type="string", example="roofing services"),
     *             @OA\Property(property="utm_content", type="string", example="ad1"),
     *             @OA\Property(property="pipeline_stage", type="string", enum={"lead", "qualified", "appointment", "proposal", "negotiation", "closed_won", "closed_lost"}, example="lead"),
     *             @OA\Property(property="appointment_date", type="string", format="date", example="2024-07-15"),
     *             @OA\Property(property="proposal_sent_date", type="string", format="date", example="2024-07-20"),
     *             @OA\Property(property="close_date", type="string", format="date", example="2024-07-25"),
     *             @OA\Property(property="monthly_retainer", type="number", format="float", example=2500.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Lead created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Lead")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Access denied. Admin privileges required.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="The email field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function store(StoreLeadRequest $request)
    {
        $lead = Lead::create($request->validated());
        $lead->load(['referrer', 'commissions']);

        return new LeadResource($lead);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/leads/{id}",
     *     tags={"Leads"},
     *     summary="Get lead",
     *     description="Get a specific lead by ID",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Lead ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lead details",
     *         @OA\JsonContent(ref="#/components/schemas/Lead")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Access denied. Admin privileges required.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lead not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Lead].")
     *         )
     *     )
     * )
     */
    public function show(Lead $lead)
    {
        $lead->load(['referrer', 'commissions']);
        return new LeadResource($lead);
    }

    /**
     * @OA\Put(
     *     path="/api/admin/leads/{id}",
     *     tags={"Leads"},
     *     summary="Update lead",
     *     description="Update a specific lead",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Lead ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="company", type="string", example="Acme Corp"),
     *             @OA\Property(property="status", type="string", enum={"new", "contacted", "qualified", "appointment_scheduled", "proposal_sent", "closed", "lost"}, example="contacted"),
     *             @OA\Property(property="notes", type="string", example="Updated notes"),
     *             @OA\Property(property="referrer_id", type="integer", example=2),
     *             @OA\Property(property="source", type="string", example="website"),
     *             @OA\Property(property="utm_source", type="string", example="google"),
     *             @OA\Property(property="utm_medium", type="string", example="cpc"),
     *             @OA\Property(property="utm_campaign", type="string", example="summer2024"),
     *             @OA\Property(property="utm_term", type="string", example="roofing services"),
     *             @OA\Property(property="utm_content", type="string", example="ad1"),
     *             @OA\Property(property="pipeline_stage", type="string", enum={"lead", "qualified", "appointment", "proposal", "negotiation", "closed_won", "closed_lost"}, example="qualified"),
     *             @OA\Property(property="appointment_date", type="string", format="date", example="2024-07-15"),
     *             @OA\Property(property="proposal_sent_date", type="string", format="date", example="2024-07-20"),
     *             @OA\Property(property="close_date", type="string", format="date", example="2024-07-25"),
     *             @OA\Property(property="monthly_retainer", type="number", format="float", example=2500.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lead updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Lead")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Access denied. Admin privileges required.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lead not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Lead].")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="The email field must be a valid email address.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        $lead->update($request->validated());
        $lead->load(['referrer', 'commissions']);

        return new LeadResource($lead);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/leads/{id}",
     *     tags={"Leads"},
     *     summary="Delete lead",
     *     description="Delete a specific lead",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Lead ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Lead deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Access denied. Admin privileges required.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lead not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Lead].")
     *         )
     *     )
     * )
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return response()->json([
            'message' => 'Lead deleted successfully'
        ], Response::HTTP_NO_CONTENT);
    }
}
