<?php

namespace App\Http\Middleware;

use App\Models\access;
use App\Models\perposal;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class proposalApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if($user && access::where('status',1)->exists()){
            return $next($request);
        }
        return response()->json(['message' => 'Access forbidden. User must have at least one approved proposal.'], 403);
    }
}
