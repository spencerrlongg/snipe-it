<?php

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::post('/mobile/login', function(Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        // Authentication passed...
         $user = Auth::user();
         $token = $user->createToken('Token Name')->accessToken;

        return response()->json([
            'token' => $token,
            'user' => $user->only('id', 'name', 'email')
        ]);
    } else {
        return response()->json(['error' => 'Unauthorised'], 401);
    } 
})->name('mobile.login');
