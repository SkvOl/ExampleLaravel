<?php

namespace App\Exceptions;

use OpenApi\Attributes as OAT;
use App\Http\sources\Wrapper;
use Exception;

class Exceptions extends Exception{
    use Wrapper;

    private $status;

    function __construct(string $message, int $status = 500, ?string $file = null, ?int $line = null) {
        $status = $status == 0 ? 500 : $status ;

        $this->message = $message;
        $this->status = $status;
        $this->code = $status;

        if(isset($file)) $this->file = $file;
        if(isset($line)) $this->line = $line;
    }

    #[OAT\Schema(
        schema: 'Error',
        properties: [
            new OAT\Property(property: 'line', type: 'int', example: 37),
            new OAT\Property(property: 'file', type: 'string', format: 'string', example: '/path_to_file/...'),
        ],
    )]

    #[OAT\Schema(
        schema: 'Error500',
        description: 'Стандартный формат ошибки',
        properties: [
            new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Error'),
            new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                properties: [
                    new OAT\Property(property: 'Message', type: 'string', format: 'string', example: 'syntax error, unexpected token "}"'),
                    new OAT\Property(property: 'Info', ref: '#/components/schemas/Error'),
                ]
            ))
        ]
    )]
    function render($request){ 
        return self::_response([
            'Message'=>$this->getMessage(),
            'Info'=>[
                // 'trace'=>$this->getTrace(),
                'line'=>$this->getLine(),
                'file'=>$this->getFile(),
            ]
        ], $this->code);
    }
}