<?php

namespace App\Modules\Personnels\Providers;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (Auth::guest()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        if (!$user->hasAccess($permission)) {
            abort(403, 'Accès non autorisé.');
        }
        
        return $next($request);
    }
} 