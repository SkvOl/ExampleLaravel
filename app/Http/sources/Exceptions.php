<?php

namespace App\Http\sources;

use App\Http\sources\Wrapper;
use Exception;

class Exceptions extends Exception{
    use Wrapper;

    private $status;

    function __construct($message, $status = 500) {
        $this->message = $message;
        $this->status = $status;
    }


    function render($request){
        return self::_response([
            'Message'=>$this->getMessage(),
            'Info'=>[
                // 'trace'=>$throwable->getTrace(),
                'line'=>$this->getLine(),
                'file'=>$this->getFile(),
            ]
        ], $this->status);
    }
}