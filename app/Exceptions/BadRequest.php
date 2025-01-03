<?php

namespace App\Exceptions;

use App\Http\sources\Wrapper;
use Exception;
use OpenApi\Attributes as OAT;

class BadRequest extends Exception
{
    use Wrapper;
    private $status;

    function __construct(string $message)
    {
        $this->message = $message;
        $this->status = 400;
        $this->code = $this->status;
    }
    #[OAT\Schema(
        schema: 'ErrorBadRequest',
        description: 'Неверный запрос',
        properties: [
            new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Error'),
            new OAT\Property(property: 'Message', type: 'string', format: 'string', example: 'incorrect data entered"}"'),
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