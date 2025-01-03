<?php
namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests;
use App\Http\sources\Mixin;

class ThrottleRequestsPersonal extends ThrottleRequests{
    use Mixin;

    public function handle($request, $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        session('locationExceptions')->setIpExceptions([
            'list of IP addresses for personalizing trolling'
        ]);

        if(session('locationExceptions')->isIpExceptions()) $maxAttempts = env('REQUEST_LIMIT_OUR');
        elseif(session('locationExceptions')->isUrlExceptions()) $maxAttempts = env('REQUEST_LIMIT_EXCEPTION');
        
        

        if (is_string($maxAttempts)
            && func_num_args() === 3
            && ! is_null($limiter = $this->limiter->limiter($maxAttempts))) {
            return $this->handleRequestUsingNamedLimiter($request, $next, $maxAttempts, $limiter);
        }

        return $this->handleRequest(
            $request,
            $next,
            [
                (object) [
                    'key' => $prefix.$this->resolveRequestSignature($request),
                    'maxAttempts' => $this->resolveMaxAttempts($request, $maxAttempts),
                    'decayMinutes' => $decayMinutes,
                    'responseCallback' => null,
                ],
            ]
        );
    }
}