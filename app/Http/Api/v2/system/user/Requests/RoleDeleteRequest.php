<?php
namespace App\Http\Api\v2\system\user\Requests;

use OpenApi\Attributes as OAT;
use App\Http\sources\Request;

#[OAT\RequestBody(
    request: 'RoleDeleteRequest',
    required: true,
    content: new OAT\JsonContent(properties: [
        new OAT\Property(property: 'user_id', type: 'int', format: 'int', example: '517746628', schema:'required|int|string'),
    ])
)]
class RoleDeleteRequest extends Request{

    function rules(): array{

        return [
            'user_id' => 'required|integer|string',
        ];
    }
}
