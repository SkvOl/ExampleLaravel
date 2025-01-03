<?php
namespace App\Http\Api\v2\system\session\Controllers;

use Illuminate\Support\Facades\Http;
use App\Exceptions\AccessDenied;
use App\Http\sources\Controller;
use OpenApi\Attributes as OAT;
use App\Exceptions\Exceptions;
use App\Exceptions\Token;
use App\Http\sources\Wrapper;
use App\Http\Api\v2\system\session\Requests\SessionAuthenticationRequest;
use App\Http\Api\v2\system\session\Requests\SessionRefreshRequest;//
use App\Http\Api\v2\system\session\Requests\SessionCheckRequest;//
use App\Http\Api\v2\system\session\Requests\SessionChangeRequest;
use App\Http\Api\v2\system\session\Requests\SessionLogoutRequest;


class SessionController extends Controller{
    use Wrapper;


    #[OAT\Post(
        path: '/v2/session/authentication',
        summary: 'Аутентификация пользователей ПГУ',
        description: 'Аутентификация пользователей ПГУ',
        tags: ['session'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'sub', type: 'int', format: 'int', example: '517671772'),
                            new OAT\Property(property: 'adm', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'pssch', type: 'int', format: 'int', example: '0'),
                            new OAT\Property(property: 'iat', type: 'int', format: 'int', example: '1733806994'),
                            new OAT\Property(property: 'exta', type: 'int', format: 'int', example: '1733893394'),
                            new OAT\Property(property: 'extr', type: 'int', format: 'int', example: '1733979794'),
                            new OAT\Property(property: 'a_token', type: 'string', format: 'string', example: 'eyJhbGciOiJzaGEyNTYiLCJ0eXAiOiJKV1QifQ==.eyJzdWIiOjUxNzY3MTc3MiwiYWRtIjoxLCJwc3NjaCI6MCwiaWF0IjoxNzMzODA2OTk0LCJleHRhIjoxNzFkljhpSlfgJleHRyIjoxNzMzOTc5Nzk0fQ==.8898d5f990bd718a7e3b3afad1be593c26f99b35803797fa15f825b69346a975'),
                            new OAT\Property(property: 'r_token', type: 'string', format: 'string', example: '91a04f11291724e9238c2b49549c68d4'),
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
        parameters: [new OAT\RequestBody(ref: "#/components/requestBodies/SessionAuthenticationRequest")]
    )]
    function authentication(SessionAuthenticationRequest $request){
        $response = Http::asForm()->post(env('AUTHENTICATION_SERVER').'/token/auth', [
            'login' => $request->input('login'),
            'password' => $request->input('password'),
            'platform'=> $request->input('platform'),
            'remote_ip'=>$request->ip(),
        ]);

        $status = $response->status();
        $response = json_decode($response, true);

        if($status == 200) return self::_response($response);
        else throw new Token($response["Error"], $status);
    }

    #[OAT\Post(
        path: '/v2/session/refresh',
        summary: 'Обновление a_token пользователей ПГУ',
        description: 'Обновление a_token пользователей ПГУ',
        tags: ['session'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'a_token', type: 'string', format: 'string', example: 'eyJhbGciOiJzaGEyNTYiLCJ0eXAiOiJKV1QifQ==.eyJzdWIiOjUxNzY3MTc3MiwiYWRtIjoxLCJwc3NjaCI6MCwiaWF0IjoxNzMzODA2OTk0LCJleHRhIjoxNzFkljhpSlfgJleHRyIjoxNzMzOTc5Nzk0fQ==.8898d5f990bd718a7e3b3afad1be593c26f99b35803797fa15f825b69346a975'),
                            new OAT\Property(property: 'r_token', type: 'string', format: 'string', example: '91a04f11291724e9238c2b49549c68d4'),
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
        parameters: [new OAT\RequestBody(ref: "#/components/requestBodies/SessionRefreshRequest")]
    )]
    function refresh(SessionRefreshRequest $request){

        $response = Http::asForm()->post(env('AUTHENTICATION_SERVER').'/token/refresh', [
            'a_token' => $request->input('a_token'),
            'r_token' => $request->input('r_token'),
            'platform'=> $request->input('platform'),
            'remote_ip'=>$request->ip(),
        ]);

        $status = $response->status();
        $response = json_decode($response, true);

        if($status == 200) return response($response)->withHeaders(['Access-Control-Allow-Credentials'=>'true']);
        else  throw new Token($response["Error"], $status);
    }

    #[OAT\Post(
        path: '/v2/session/check',
        summary: 'Проверка a_token пользователей ПГУ',
        description: 'Проверка a_token пользователей ПГУ',
        tags: ['session'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'sub', type: 'int', format: 'int', example: '517671772'),
                            new OAT\Property(property: 'adm', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'pssch', type: 'int', format: 'int', example: '0'),
                            new OAT\Property(property: 'iat', type: 'int', format: 'int', example: '1733806994'),
                            new OAT\Property(property: 'exta', type: 'int', format: 'int', example: '1733893394'),
                            new OAT\Property(property: 'extr', type: 'int', format: 'int', example: '1733979794'),
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
        parameters: [new OAT\RequestBody(ref: "#/components/requestBodies/SessionCheckRequest")]
    )]
    function check(SessionCheckRequest $request){

        $response = Http::asForm()->post(env('AUTHENTICATION_SERVER').'/token/check', [
            'a_token' => $request->input('a_token'),
            'remote_ip'=>$request->ip(),
        ]);

        $status = $response->status();
        $response = json_decode($response, true);

        if($status == 200) return response($response)->withHeaders(['Access-Control-Allow-Credentials'=>'true']);
        else  throw new Token($response["Error"], $status);
    }
//
    #[OAT\Patch(
        path: '/v2/session/change',
        summary: 'Изменение сессии пользователя',
        description: 'Изменение сессии пользователя',
        tags: ['session'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'sub', type: 'int', format: 'int', example: '517671772'),
                            new OAT\Property(property: 'adm', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'pssch', type: 'int', format: 'int', example: '0'),
                            new OAT\Property(property: 'iat', type: 'int', format: 'int', example: '1733806994'),
                            new OAT\Property(property: 'exta', type: 'int', format: 'int', example: '1733893394'),
                            new OAT\Property(property: 'extr', type: 'int', format: 'int', example: '1733979794'),
                            new OAT\Property(property: 'a_token', type: 'string', format: 'string', example: 'eyJhbGciOiJzaGEyNTYiLCJ0eXAiOiJKV1QifQ==.eyJzdWIiOjUxNzY3MTc3MiwiYWRtIjoxLCJwc3NjaCI6MCwiaWF0IjoxNzMzODA2OTk0LCJleHRhIjoxNzFkljhpSlfgJleHRyIjoxNzMzOTc5Nzk0fQ==.8898d5f990bd718a7e3b3afad1be593c26f99b35803797fa15f825b69346a975'),
                            new OAT\Property(property: 'r_token', type: 'string', format: 'string', example: '91a04f11291724e9238c2b49549c68d4'),
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
        parameters: [new OAT\RequestBody(ref: "#/components/requestBodies/SessionChangeRequest")]
    )]
    function edit(SessionChangeRequest $request){

        $response = Http::asForm()->post(env('AUTHENTICATION_SERVER').'/user/changeuser', [
            'a_token'=> session('user')->token,
            'id'=> $request->input('id'),
            'platform'=> $request->input('platform'),
            'remote_ip'=> session('user')->ip,
        ]);

        
        $status = $response->status();
        $response = json_decode($response, true);
        
        if($status == 200) return self::_response($response);
        else throw new Exceptions($response["Error"], $status);
    }

    #[OAT\Post(
        path: '/v2/session/logout',
        summary: 'Выход из аккаунта для пользователей ПГУ',
        description: 'Выход из аккаунта для пользователей ПГУ',
        tags: ['session'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'a_token', type: 'string', format: 'jwt', example: 'eyJhbGciOiJzaGEyNTYiLCJ0eXfAiOiJKV1QifQ.eyJzdWIiOjUxNzY3r3MTc3MiwiYWRtIjhoxLCJwc3NjaCI6MCwiaWF0IjoxNzMyNjE3MDE5LCJleHRhIjoxNzMyNzAzNDE5LCJleHRyIjoxNzMyNzg5ODE5fQ.0ff71fc0d98dd36f1df07c0f77062160f26d9a4be1872d407e157fdc85f9f3dd'),
                            new OAT\Property(property: 'r_token', type: 'string', format: 'string', example: 'eyJhbGciOiJzaGEyN'),
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
        parameters: [new OAT\RequestBody(ref: "#/components/requestBodies/SessionLogoutRequest")]
    )]
    function logout(SessionLogoutRequest $request){

        $response = Http::asForm()->post(env('AUTHENTICATION_SERVER').'/token/logout', [
            'a_token' => $request->input('a_token'),
            'r_token' => $request->input('r_token'),
            'remote_ip'=>$request->ip(),
        ]);

        $status = $response->status();
        $response = json_decode($response, true);

        if($status == 200) return self::_response($response);
        else  throw new Token($response["Error"], $status);
    }
}