<?php

namespace App\Http\Middleware\Api\V1;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogUnauthorizedAccess
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->status() === 403) {
            Log::warning('Unauthorized access attempt', [
                'user_id' => auth()->id(),
                'path' => $request->path(),
                'ip' => $request->ip(),
            ]);
        }

        return $response;
    }
}