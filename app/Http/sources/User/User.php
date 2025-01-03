<?php
namespace App\Http\sources\User;

use App\Http\sources\User\UserRights;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Exceptions\Token;

class User{
    use UserRights;

    private $checkExceptions;
    private $request;
    
    /** @var int $token Токен пользователя, который делает запрос*/
    public $token;
    /** @var int $id Идентификатор пользователя, который делает запрос*/
    public $id = null;
    // /** @var int $adm Метка, админ ли человек, который делает запрос */
    public $adm;
    /** @var int $pssch unix дата смены пароля */
    public $pssch;
    /** @var int $iat Дата выдачи токена */
    public $iat;
    /** @var int $exta Дата окончания действия access token */
    public $exta;
    /** @var int $extr Дата окончания действия refresh token */
    public $extr;
    /** @var int $ip Пользователя, который сделал запрос */
    public $ip;
    /** @var int $userAgent Пользователя, который сделал запрос */
    public $userAgent;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
        $this->ip = $this->request->ip();
        $this->userAgent = $this->request->header('User-Agent');
        if(isset($this->request)) $this->token = $this->request->bearerToken();
    }

    /**
     * Задание массива исключений при проверке токенов. 
     * @param array $exceptions [bool, bool, ...]
     * @return void
     */
    public function setExceptions(array $exceptions): void
    {
        $this->checkExceptions = $exceptions;
    }


    /**
     * Проверка токенов. 
     * @return bool
     */
    public function checkToken(): bool
    {
        if(is_null($this->token)){
            foreach($this->checkExceptions as $exception) if($exception) return true;
        
            throw new Token('Token is missing', 401);
        } 

        $response = Http::asForm()->post(env('AUTHENTICATION_SERVER').'/token/check', [
            'a_token' => $this->token,
            'remote_ip'=>$this->ip,
        ]);

        $status = $response->status();
        $response = json_decode($response, true);

        if($status == 200){
            ['sub'=>$this->id, 'adm'=>$this->adm, 'pssch'=>$this->pssch, 'iat'=>$this->iat, 'exta'=>$this->exta, 'extr'=>$this->extr] = $response;
            return true;
        }
        else{
            throw new Token($response["Error"], $status);
        }
    }


    /**
     * Получаем права с сервера авторизации. 
     * @return void
     */
    public function getRight(): void
    {
        $response = [];

        if(isset($this->token) AND isset($this->id)) {
            $response = Http::asForm()->post("http://172.16.170.109/right/get", [
                'a_token'=> $this->token,
                'user_id'=> $this->id,
                'server_id'=> 1,
            ]);

            $response = json_decode($response, true)['data'];
        }
        
        self::initialization($response, is_null($this->adm) ? 1 : $this->adm);
    }


    /**
     * Возвращает массив данных о пользователе. 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'token'=> $this->token,
            'id'=> $this->id,
            'adm'=> $this->adm,
            'pssch'=> $this->pssch,
            'iat'=> $this->iat,
            'exta'=> $this->exta,
            'extr'=> $this->extr,
            'ip'=> $this->ip,
            'userAgent'=> $this->userAgent,
        ];
    }
}