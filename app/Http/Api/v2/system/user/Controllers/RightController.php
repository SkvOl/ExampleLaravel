<?php
namespace App\Http\Api\v2\system\user\Controllers;

use App\Http\Api\v2\system\user\Requests\RightGetRequest;
use Illuminate\Support\Facades\Http;
use App\Exceptions\AccessDenied;
use App\Http\sources\Controller;
use OpenApi\Attributes as OAT;
use App\Exceptions\Exceptions;
use App\Http\sources\Wrapper;

class RightController extends Controller{
    use Wrapper;

    #[OAT\Get(
        path: '/v2/user/right',
        summary: 'Получение прав пользователя',
        description: 'Получение прав пользователя',
        tags: ['user', 'right'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'right_id', type: 'int', format: 'int', example: '5'),
                            new OAT\Property(property: 'model_id', type: 'int', format: 'int', example: '61'),
                            new OAT\Property(property: 'level_rights_model', type: 'int', format: 'int', example: '41'),
                            new OAT\Property(property: 'server_id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'department_id', type: 'int', format: 'int', example: '0'),
                        ]
                    ))
                ])
            ),
            new OAT\Response(
                response: 500,
                description: 'Ошибка',
                content: new OAT\JsonContent(ref: '#/components/schemas/Error500')
            ),
        ],
        parameters: [new OAT\RequestBody(ref: "#/components/requestBodies/RightGetRequest")]
    )]
    function right(RightGetRequest $request){
        $response = Http::asForm()->post("http://172.16.170.109/right/get", [
            'a_token'=> session('user')->token,
            'user_id'=> $request->input('user_id'),
            'server_id'=> $request->input('server_id'),
        ]);

        $status = $response->status();
        $response = json_decode($response, true);

        if($status == 200) return self::_response($response['data']);
        else throw new Exceptions($response["Error"], $status);
    }
}