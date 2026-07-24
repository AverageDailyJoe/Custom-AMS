<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptimizeLocalResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent Windows PHP Built-in Server Keep-Alive socket hangs
        if (app()->isLocal()) {
            $response->headers->set('Connection', 'close');
            $response->headers->set('Keep-Alive', 'timeout=1, max=5');
        }

        return $response;
    }
}
