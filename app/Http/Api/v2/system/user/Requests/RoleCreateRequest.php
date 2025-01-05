<?php
namespace App\Http\Api\v2\system\user\Requests;

use OpenApi\Attributes as OAT;
use App\Http\sources\Request;

#[OAT\RequestBody(
    request: 'RoleCreateRequest',
    required: true,
    content: new OAT\JsonContent(properties: [
        new OAT\Property(property: 'user_id', type: 'int', format: 'int', example: '517746628', schema:'required|int|string'),
        new OAT\Property(property: 'role_id', type: 'int', format: 'int', example: '4', schema:'required|int|string'),
        new OAT\Property(property: 'department_id', type: 'int', format: 'int', example: '1', schema:'required|int|string'),
    ])
)]
class RoleCreateRequest extends Request{

    function rules(): array{

        return [
            'user_id' => 'required|integer|string',
            'role_id'=>'required|integer|string',
            'department_id'=>'required|integer|string'
        ];
    }
}
