<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireProfileCompletion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Skip if user is not authenticated
        if (!$user) {
            return $next($request);
        }

        // Skip if user is not a partner
        if (!$user->isPartner()) {
            return $next($request);
        }

        // Skip if user has completed onboarding
        if ($user->hasCompletedOnboarding()) {
            return $next($request);
        }

        // Skip if already on onboarding routes
        if ($request->routeIs('onboarding.*')) {
            return $next($request);
        }

        // Skip for logout and profile routes
        if ($request->routeIs('logout') || $request->routeIs('profile.*')) {
            return $next($request);
        }

        // Redirect to onboarding
        return redirect()->route('onboarding.step', ['step' => $user->onboarding_step])
            ->with('message', 'Please complete your profile to access your dashboard.');
    }
}
