<?php
namespace App\Http\Api\v2\system\session\Requests;

use OpenApi\Attributes as OAT;
use App\Http\sources\Request;

#[OAT\RequestBody(
    request: 'SessionRefreshRequest',
    required: true,
    content: new OAT\JsonContent(properties: [
        new OAT\Property(property: 'a_token', type: 'string', format: 'jwt', example: 'eyJhbGciOiJzaGEyNTYiLCJ0eXfAiOiJKV1QifQ.eyJzdWIiOjUxNzY3r3MTc3MiwiYWRtIjhoxLCJwc3NjaCI6MCwiaWF0IjoxNzMyNjE3MDE5LCJleHRhIjoxNzMyNzAzNDE5LCJleHRyIjoxNzMyNzg5ODE5fQ.0ff71fc0d98dd36f1df07c0f77062160f26d9a4be1872d407e157fdc85f9f3dd', schema:'required|string'),
        new OAT\Property(property: 'r_token', type: 'string', format: 'login', example: 'eyJhbGciOiJzaGEyN', schema:'required|string'),
        new OAT\Property(property: 'platform', type: 'int', format: 'int', example: '1', schema:'required|int|string'),
    ])
)]
class SessionRefreshRequest extends Request{

    function rules(): array{

        return [
            'a_token' => 'required|string',
            'r_token' => 'required|string',
            'platform' => 'required|int|string',
        ];
    }
}