<?php
namespace App\Http\Api\v2\system\user\Requests;

use OpenApi\Attributes as OAT;
use App\Http\sources\Request;

#[OAT\RequestBody(
    request: 'RightGetRequest',
    required: true,
    content: new OAT\JsonContent(properties: [
        new OAT\Property(property: 'user_id', type: 'int', format: 'int', example: '517746628', schema:'required|int|string'),
        new OAT\Property(property: 'server_id', type: 'int', format: 'int', example: '4', schema:'required|int|string'),
    ])
)]
class RightGetRequest extends Request{

    function rules(): array{

        return [
            'user_id' => 'required|integer|string',
            'server_id'=>'required|integer|string',
        ];
    }
}
