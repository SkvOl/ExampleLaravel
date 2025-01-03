<?php
namespace App\Http\Api\v2\system\example\Controllers;

use App\Exceptions\AccessDenied;
use App\Http\sources\Controller;
use OpenApi\Attributes as OAT;
use App\Models\App;


class AppController extends Controller{

    #[OAT\Get(
        path: '/example/app',
        summary: 'Тестовый url получающий заявления',
        description: 'Тестовый url получающий заявления',
        tags: ['example'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'paginator', ref: '#/components/schemas/Paginator'),
                ])
            ),
            new OAT\Response(
                response: 500,
                description: 'Ошибка',
                content: new OAT\JsonContent(ref: '#/components/schemas/Error500')
            ),
        ],
        parameters: new OAT\Parameters(ref: 'paginator'),
    )]
    function getList($request){
        if(session('user')->checkRightsByLevel(App::class, [43])){
            return App::configure($request)->paginate();
        }
        else{
            throw new AccessDenied;
        }
    }
    
    #[OAT\Get(
        path: '/example/app/{id}',
        summary: 'Тестовый url получающий заявление',
        description: 'Тестовый url получающий заявление',
        tags: ['example'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'paginator', ref: '#/components/schemas/Paginator'),
                    // new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(ref: '#/components/schemas/AppTransformer'))
                ])
            ),
            new OAT\Response(
                response: 500,
                description: 'Ошибка',
                content: new OAT\JsonContent(ref: '#/components/schemas/Error500')
            ),
        ],
        parameters: new OAT\Parameters(ref: 'paginator')
    )]
    function getOne(string $id){
        if(session('user')->checkRightsByLevel(App::class, [43])){
            return App::findOrFail($id);
        }
        else{
            throw new AccessDenied;
        }
    }

    #[OAT\Post(
        path: '/example/app',
        summary: 'Тестовый url для создание заявления',
        description: 'Тестовый url для создание заявления',
        tags: ['example'],
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
        parameters: [new OAT\RequestBody(ref: "#/components/requestBodies/AppCreateRequest")]
    )]
    function create($request){
        if(session('user')->checkRightsByLevel(App::class, [43])){
            return [
                'id'=>App::insertGetId($request->all()),
            ];
        }
        else{
            throw new AccessDenied;
        }
    }

    #[OAT\Patch(
        path: '/example/app/{id}',
        summary: 'Тестовый url для изменения заявления',
        description: 'Тестовый url для изменения заявления',
        tags: ['example'],
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
        parameters: [new OAT\RequestBody(ref: "#/components/requestBodies/AppChangeRequest")]
    )]
    function change($request, string $id){ 
        if(session('user')->checkRightsByLevel(App::class, [43])){
            App::where('id', $id)->update($request->all());
        
            return [
                'id'=>$id,
            ];
        }
        else{
            throw new AccessDenied;
        }
    }
    
    #[OAT\Delete(
        path: '/example/app/{id}',
        summary: 'Тестовый url для удаления заявления',
        description: 'Тестовый url для удаления заявления',
        tags: ['example'],
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
    )]
    function delete($request, string $id){ 
        if(session('user')->checkRightsByLevel(App::class, [43])){
            App::where('id', $id)->delete();

            return [
                'id'=>$id,
            ];
        }
        else{
            throw new AccessDenied;
        }
    }

    function test(App $app){
        if($app->trashed()) echo 'Удалён'."\n\n";
        else echo 'Не удалён'."\n\n";

        return $app; 
    }
}