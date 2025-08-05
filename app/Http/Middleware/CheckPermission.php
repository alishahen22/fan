<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (! $user) {
            return abort(401, 'Unauthorized');
        }

        if (! $user->hasPermission($permission)) {
            return abort(403, 'Access denied. You do not have the required permission: ' . $permission);
        }

        return $next($request);
    }
}
