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
     *     summary="Search for a user by referral code",
     *     description="Find a referral partner or admin user using their unique referral code",
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
     *                 @OA\Property(property="role", type="string", example="partner", description="User role (partner or admin)"),
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
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found with the provided referral code")
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

            $user = User::where('referral_code', $validated['referral_code'])
                ->whereIn('role', ['partner', 'admin'])
                ->first();

            if (!$user) {
                return response()->json([
                    'message' => 'User not found with the provided referral code'
                ], 404);
            }

            // Calculate basic stats
            $totalLeads = $user->leads()->count();
            $totalCommissions = $user->getTotalCommissions();

            return response()->json([
                'partner' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'referral_code' => $user->referral_code,
                    'role' => $user->role,
                    'created_at' => $user->created_at->toISOString(),
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
