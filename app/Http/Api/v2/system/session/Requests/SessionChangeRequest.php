<?php
namespace App\Http\Api\v2\system\session\Requests;

use OpenApi\Attributes as OAT;
use App\Http\sources\Request;

#[OAT\RequestBody(
    request: 'SessionChangeRequest',
    required: true,
    content: new OAT\JsonContent(properties: [
        new OAT\Property(property: 'platform', type: 'int', format: 'int', example: '4', schema:'required|int|string'),
        new OAT\Property(property: 'id', type: 'int', format: 'int', example: '517671772', schema:'required|int|string'),
    ])
)]
class SessionChangeRequest extends Request{

    function rules(): array{
        return [
            'platform' => 'required|integer',
            'id' =>'required|integer',
        ];
    }
}
