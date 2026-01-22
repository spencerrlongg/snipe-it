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
            // this is for us - just to tell us that requests are coming from the mobile app.
            Session::put('client', $request->get('client'));
            // this is for passport - too many redirects in our auth flow means the query string gets lost, so we're storing the values in
            // the session so that we can get through redirects for saml and 2fa logins.
            // forgetting these keys after they're used is probably smart to make sure we don't have stale auth stuff laying around in the session.
            // i attempted flashing, doesn't seem to work for this, too many redirects happening.
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
