<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;

class LogPageView
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->isMethod('get')
            && ! $request->is('admin*')
            && ! $request->is('storage/*')
            && ! $request->expectsJson()
        ) {
            ActivityLog::recordFromRequest('View', 'Someone viewed page');
        }

        return $response;
    }
}

