<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\AdminPermission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
class VerifyCsrfToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('bhavya jain');
        Log::info('CSRF Token from form input (_token):', [$request->input('_token')]);
        Log::info('CSRF Token from XSRF-TOKEN cookie:', [$request->cookie('XSRF-TOKEN')]);
        Log::info('Session CSRF token:', [session()->token()]);
        return $next($request);
        
    }
}
