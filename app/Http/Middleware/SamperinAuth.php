<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SamperinAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('samperin_user_id')) {
            return redirect()->route('samperin.login');
        }

        return $next($request);
    }
}