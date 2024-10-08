<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if (auth()->user()) {
        
                    switch (auth()->user()->type) {
                        case 'bdo':
                                return redirect()->route('authenticate.activity');
                            break;
                        case 'supervisor':
                                return redirect()->route('authenticate.supervisor');
                            break;
                        case 'admin':
                                return redirect()->route('authenticate.admin');
                            break;
                        
                        default:
                        // return redirect()->route('authenticate.activity');
                            break;
                    }

        
                } else {

                    if (Auth::guard('web')->check()) {

                        Auth::guard('web')->logout();
                    
                        return redirect()->route('auth.signin')->with('msg','You are not allowed to access this system');
                    }
                    
       
                }
            }
        }

        return $next($request);
    }
}
