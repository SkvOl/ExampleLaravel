<?php
namespace App\Http\Api\v2\system\user\Controllers;

use Illuminate\Support\Facades\Http;
use App\Exceptions\AccessDenied;
use App\Http\sources\Controller;
use OpenApi\Attributes as OAT;
use App\Exceptions\Exceptions;
use App\Http\sources\Wrapper;

class RoleController extends Controller{
    use Wrapper;

    #[OAT\Get(
        path: '/v2/user/role/{id}',
        summary: 'Получение ролей пользователя',
        description: 'Получение ролей пользователя',
        tags: ['user','role'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'id', type: 'string', format: 'string', example: '5'),
                            new OAT\Property(property: 'name', type: 'string', format: 'string', example: 'Администратор модуля телефонии'),
                            new OAT\Property(property: 'role_id', type: 'string', format: 'string', example: '3')
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
    )]
    function getOne(string $id){
        $response = Http::asForm()->post('http://172.16.170.109/right/getrolesuser', [
            'user_id'=>$id,
            'a_token'=>session('user')->token,
        ]);

        $status = $response->status();
        $response = json_decode($response, true);

        if($status == 200) return self::_response($response['data']);
        else throw new Exceptions($response["Error"], $status);
    }


    #[OAT\Post(
        path: '/v2/user/role',
        summary: 'Создание ролей пользователя',
        description: 'Создание ролей пользователя',
        tags: ['user', 'role'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', properties: [
                        new OAT\Property(property: 'id', type: 'int', format: 'int', example: 1),
                    ])
                ])
            ),
            new OAT\Response(
                response: 500,
                description: 'Ошибка',
                content: new OAT\JsonContent(ref: '#/components/schemas/Error500')
            ),
        ],
        parameters: [new OAT\RequestBody(ref: "#/components/requestBodies/RoleCreateRequest")]
    )]
    function create($request){
        $response = Http::asForm()->post('http://172.16.170.109/right/save', $request->all() + ['a_token'=> session('user')->token]);

        $status = $response->status();
        $response = json_decode($response, true);

        if($status == 200) return self::_response($response['data']);
        else throw new Exceptions($response["Error"], $status);
    }

    #[OAT\Delete(
        path: '/v2/user/role/{id}',
        summary: 'Удаление ролей пользователя',
        description: 'Удаление ролей пользователя',
        tags: ['user', 'role'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', properties: [
                        new OAT\Property(property: 'id', type: 'int', format: 'int', example: 1),
                    ])
                ])
            ),
            new OAT\Response(
                response: 500,
                description: 'Ошибка',
                content: new OAT\JsonContent(ref: '#/components/schemas/Error500')
            ),
        ],
        parameters: [new OAT\RequestBody(ref: "#/components/requestBodies/RoleDeleteRequest")]
    )]
    function delete($request, string $id){ 
        $response = Http::asForm()->post('http://172.16.170.109/right/delete', [
            'id'=>$id,
            'user_id'=>$request->input('user_id'),
            'a_token'=> session('user')->token
        ]);

        $status = $response->status();
        $response = json_decode($response, true);

        if($status == 200) return self::_response($response['data']);
        else throw new Exceptions($response["Error"], $status);
    }
}