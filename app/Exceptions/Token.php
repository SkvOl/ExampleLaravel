<?php

namespace App\Exceptions;

use App\Http\sources\Wrapper;
use Exception;

class Token extends Exception{
    use Wrapper;

    private $status;

    function __construct($message, $status = 400) {
        $this->message = $message;
        $this->status = $status;
        $this->code = $status;
    }

    function render($request){
        return self::_response([
            'Error' => $this->getMessage()
        ], $this->code);
    }
}