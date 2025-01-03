<?php
namespace App\Http\Api\v2\system\role\Controllers;

use Illuminate\Support\Facades\Http;
use App\Exceptions\AccessDenied;
use App\Http\sources\Controller;
use OpenApi\Attributes as OAT;
use App\Exceptions\Exceptions;
use App\Http\sources\Wrapper;

class RoleController extends Controller{
    use Wrapper;
    
    #[OAT\Get(
        path: '/v2/role',
        summary: 'Получение всех ролей',
        description: 'Получение всех ролей',
        tags: ['role'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'id', type: 'string', format: 'string', example: '3'),
                            new OAT\Property(property: 'name', type: 'string', format: 'string', example: 'Администратор модуля телефонии')
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
    function role(){
        $response = Http::asForm()->post('http://172.16.170.109/right/getallroles', [
            'a_token'=>session('user')->token,
        ]);

        $status = $response->status();
        $response = json_decode($response, true);

        if($status == 200) return self::_response($response['data']);
        else throw new Exceptions($response["Error"], $status);
    }
}