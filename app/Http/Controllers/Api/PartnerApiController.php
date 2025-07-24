<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Partners",
 *     description="Partner management endpoints"
 * )
 */
class PartnerApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/partners/search",
     *     summary="Search for a partner by referral code",
     *     description="Find a referral partner using their unique referral code",
     *     operationId="searchPartner",
     *     tags={"Partners"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="referral_code",
     *         in="query",
     *         required=true,
     *         description="The referral code to search for",
     *         @OA\Schema(
     *             type="string",
     *             example="ref_abc12345"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Partner found successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="partner", type="object",
     *                 @OA\Property(property="id", type="integer", example=4),
     *                 @OA\Property(property="name", type="string", example="John Smith"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="referral_code", type="string", example="ref_abc12345"),
     *                 @OA\Property(property="role", type="string", example="partner"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="stats", type="object",
     *                     @OA\Property(property="total_leads", type="integer", example=5),
     *                     @OA\Property(property="total_commissions", type="number", format="float", example=1250.00)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Partner not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Partner not found with the provided referral code")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="referral_code", type="array",
     *                     @OA\Items(type="string", example="The referral code field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Admin access required",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Access denied. Admin privileges required.")
     *         )
     *     )
     * )
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'referral_code' => 'required|string|max:255',
            ]);

            $partner = User::where('referral_code', $validated['referral_code'])
                ->where('role', 'partner')
                ->first();

            if (!$partner) {
                return response()->json([
                    'message' => 'Partner not found with the provided referral code'
                ], 404);
            }

            // Calculate basic stats
            $totalLeads = $partner->leads()->count();
            $totalCommissions = $partner->getTotalCommissions();

            return response()->json([
                'partner' => [
                    'id' => $partner->id,
                    'name' => $partner->name,
                    'email' => $partner->email,
                    'referral_code' => $partner->referral_code,
                    'role' => $partner->role,
                    'created_at' => $partner->created_at->toISOString(),
                    'stats' => [
                        'total_leads' => $totalLeads,
                        'total_commissions' => $totalCommissions,
                    ]
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
