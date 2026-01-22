<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class MobileSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('client')) {
            // this is mine
            Session::put('client', $request->get('client'));
            // this is passport's - we might want to do a flash or something to ensure we're not holding stale auth data in the session
            Session::put('client_id', $request->query('client_id'));
            Session::put('code_challenge', $request->query('code_challenge'));
            Session::put('code_challenge_method', $request->query('code_challenge_method'));
            Session::put('prompt', $request->query('prompt'));
            Session::put('redirect_uri', $request->query('redirect_uri'));
            Session::put('response_type', $request->query('response_type'));
            Session::put('state', $request->query('state'));
        }
        return $next($request);
    }
}
