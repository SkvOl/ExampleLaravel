<?php

namespace App\Exceptions;

use App\Http\sources\Wrapper;
use Exception;
use OpenApi\Attributes as OAT;

class NotFound extends Exception
{
    use Wrapper;
    private $status;

    function __construct()
    {
        $this->message = "Not found";
        $this->status = 404;
        $this->code = $this->status;
    }
    
    #[OAT\Schema(
        schema: 'ErrorNotFound',
        description: 'Запись не найдена',
        properties: [
            new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Error'),
            new OAT\Property(property: 'Message', type: 'string', format: 'string', example: 'file access error, еntry not found"}"'),
            new OAT\Property(property: 'Info', ref: '#/components/schemas/Error'),
        ]
    )]
    function render($request)
    {
        return self::_response([
            'Message' => $this->getMessage(),
        ], $this->status);
    }
}