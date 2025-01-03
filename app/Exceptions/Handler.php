<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Http\sources\Wrapper;
use Illuminate\Http\Request;
use Throwable;


class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void{
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $throwable, Request $request) {
            if(method_exists($throwable, 'getStatusCode')) $statusCode = $throwable->getStatusCode();
            elseif($throwable->getCode() != 0) $statusCode = $throwable->getCode();
            else $statusCode = 500;
            
            session('locationExceptions')->setIpExceptions([
                'list of IP addresses for error personalization'
            ]);

            if(session('locationExceptions')->isLocal() AND $statusCode != 429){
                return Wrapper::_response([
                    'Message'=>$throwable->getMessage(),
                    'Info'=>[
                        // 'trace'=>$throwable->getTrace(),
                        'line'=>$throwable->getLine(),
                        'file'=>$throwable->getFile(),
                    ]
                ], $statusCode);
            }
            elseif(session('locationExceptions')->isIpExceptions()){
                return Wrapper::_response(['status'=>'work'], 200);
            }

            return Wrapper::_response(statusCode: $statusCode);
        });
    }
}
