<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

class LanguageSelection {
    const LOCALES = ['ru', 'en',];

    public function handle(Request $request, Closure $next) {
        App::setLocale($request->getPreferredLanguage(self::LOCALES));
        
        return $next($request);
    }
}