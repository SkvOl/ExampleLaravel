<?php

namespace App\Exceptions;

use App\Http\sources\Wrapper;
use Exception;
use OpenApi\Attributes as OAT;

class AccessDenied extends Exception{
    use Wrapper;
    private $status;

    function __construct() {
        $this->message = "Access denied";
        $this->status = 403;
        $this->code = $this->status;
    }
    #[OAT\Schema(
        schema: 'Error403',
        description: 'Ошибка',
        properties: [
            new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Error'),
            new OAT\Property(property: 'Message', type: 'string', format: 'string', example: 'file access error, access is denied"}"'),
            new OAT\Property(property: 'Info', ref: '#/components/schemas/Error'),
        ]
    )]
    function render($request){
        return self::_response([
            'Message'=>$this->getMessage(),
        ], $this->status);
    }
}