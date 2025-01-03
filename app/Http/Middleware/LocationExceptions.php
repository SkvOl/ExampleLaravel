<?php

namespace App\Http\Middleware;

use App\Http\sources\LocationExceptions as LocationException;
use Illuminate\Http\Request;
use Closure;

class LocationExceptions{
    public function handle(Request $request, Closure $next) {
        $locationExceptions = new LocationException($request->ip(), $request->url());
        
        $locationExceptions->setUrlExceptions([
            'list of urls to exclude from token verification'
        ]);

        session(['locationExceptions'=>$locationExceptions]);

        return $next($request);
    }
}