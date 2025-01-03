<?php
namespace App\Http\Api\v2\system\session\Requests;

use OpenApi\Attributes as OAT;
use App\Http\sources\Request;

#[OAT\RequestBody(
    request: 'SessionAuthenticationRequest',
    required: true,
    content: new OAT\JsonContent(properties: [
        new OAT\Property(property: 'login', type: 'string', format: 'login', example: 's517671772', schema:'required|string'),
        new OAT\Property(property: 'password', type: 'string', format: 'password', example: 'fdgdhd45', schema:'required|string'),
        new OAT\Property(property: 'platform', type: 'string', format: 'string', example: '1', schema:'required|int|string'),
    ])
)]
class SessionAuthenticationRequest extends Request{

    function rules(): array{

        return [
            'login' => 'required|string',
            'password'=>'required|string',
            'platform'=>'required|int|string'
        ];
    }
}