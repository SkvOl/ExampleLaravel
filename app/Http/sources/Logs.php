<?php

namespace App\Http\sources;

use App\Models\ApiLogs;
use App\Models\ApiEntities;
use App\Models\ApiSystems;

class Logs{
    private $version;
    private $id_system;
    private $id_entity;
    private $id_user;
    private $ip;
    private $status;
    private $request;
    private $response;


    /**
     * Конструктор логов
     *
     * @param string $status
     * @param array $in
     * @param array $out
     */
    function __construct($status, $in, $out)
    {
        $this->init();

        $this->status  = $status;
        $this->request = json_encode($in, JSON_UNESCAPED_UNICODE);
        $this->response  = json_encode($out, JSON_UNESCAPED_UNICODE);
    }

    function save(): int
    {
        return ApiLogs::insertGetId(get_object_vars($this));
    }

    private function init(): void
    {
        $url = explode('/', $_SERVER['REQUEST_URI']);
        $this->version = $url[3];
        $this->id_system  = $url[4];
        $this->id_entity  = $url[5];


        $id = ApiEntities::selectRaw('systems.id id_system, entities.id id_entity')->join('api.systems','api.systems.id','api.entities.id_system')
        ->where('systems.link', $this->id_system)->where('entities.name', $this->id_entity)->get()->toArray();

        if(isset($id[0])){
            $this->id_system  = $id[0]['id_system'];
            $this->id_entity  = $id[0]['id_entity'];
        }
        else{
            $this->id_system  = -1;
            $this->id_entity  = -1;
        }

        $this->id_user = session('id_user');
        $this->ip      = $_SERVER['REMOTE_ADDR'];
    }
}