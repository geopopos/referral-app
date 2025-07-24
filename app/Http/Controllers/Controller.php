<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="VolumeUp Referral System API",
 *     version="1.0.0",
 *     description="API for managing leads, commissions, and referrals in the VolumeUp partner system.",
 *     @OA\Contact(
 *         email="support@volumeup.agency",
 *         name="VolumeUp Support"
 *     ),
 *     @OA\License(
 *         name="Proprietary",
 *         url="https://volumeup.agency/terms"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local Development Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 *     description="Enter token in format (Bearer <token>)"
 * )
 * 
 * @OA\Tag(
 *     name="Authentication",
 *     description="API authentication endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Leads",
 *     description="Lead management operations (Admin only)"
 * )
 * 
 * @OA\Tag(
 *     name="Commissions",
 *     description="Commission management operations (Admin only)"
 * )
 * 
 * @OA\Tag(
 *     name="Webhooks",
 *     description="Webhook configuration and management (Admin only)"
 * )
 * 
 * @OA\Schema(
 *     schema="Lead",
 *     type="object",
 *     title="Lead",
 *     description="Lead model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="phone", type="string", nullable=true, example="+1234567890"),
 *     @OA\Property(property="company", type="string", example="Acme Corp"),
 *     @OA\Property(property="status", type="string", enum={"new", "contacted", "qualified", "appointment_scheduled", "proposal_sent", "closed", "lost"}, example="new"),
 *     @OA\Property(property="notes", type="string", nullable=true, example="Interested in roofing services"),
 *     @OA\Property(property="referrer_id", type="integer", nullable=true, example=2),
 *     @OA\Property(property="source", type="string", nullable=true, example="website"),
 *     @OA\Property(property="utm_source", type="string", nullable=true, example="google"),
 *     @OA\Property(property="utm_medium", type="string", nullable=true, example="cpc"),
 *     @OA\Property(property="utm_campaign", type="string", nullable=true, example="summer2024"),
 *     @OA\Property(property="utm_term", type="string", nullable=true, example="roofing services"),
 *     @OA\Property(property="utm_content", type="string", nullable=true, example="ad1"),
 *     @OA\Property(property="pipeline_stage", type="string", enum={"lead", "qualified", "appointment", "proposal", "negotiation", "closed_won", "closed_lost"}, nullable=true, example="lead"),
 *     @OA\Property(property="appointment_date", type="string", format="date", nullable=true, example="2024-07-15"),
 *     @OA\Property(property="proposal_sent_date", type="string", format="date", nullable=true, example="2024-07-20"),
 *     @OA\Property(property="close_date", type="string", format="date", nullable=true, example="2024-07-25"),
 *     @OA\Property(property="monthly_retainer", type="number", format="float", nullable=true, example=2500.00),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-12T22:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-12T22:00:00.000000Z"),
 *     @OA\Property(property="referrer", ref="#/components/schemas/User", nullable=true),
 *     @OA\Property(property="commissions", type="array", @OA\Items(ref="#/components/schemas/Commission")),
 *     @OA\Property(property="total_commissions", type="number", format="float", example=250.00)
 * )
 * 
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="User model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Partner User"),
 *     @OA\Property(property="email", type="string", format="email", example="partner@example.com"),
 *     @OA\Property(property="role", type="string", example="partner")
 * )
 * 
 * @OA\Schema(
 *     schema="Commission",
 *     type="object",
 *     title="Commission",
 *     description="Commission model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=2),
 *     @OA\Property(property="lead_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="amount", type="number", format="float", example=250.00),
 *     @OA\Property(property="type", type="string", enum={"referral", "fast_close_bonus", "monthly_recurring"}, example="referral"),
 *     @OA\Property(property="status", type="string", enum={"pending", "approved", "paid", "cancelled"}, example="pending"),
 *     @OA\Property(property="notes", type="string", nullable=true, example="Initial referral commission"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-12T22:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-12T22:00:00.000000Z")
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
