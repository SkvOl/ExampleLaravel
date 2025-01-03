<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\ApiSystems;
use App\Models\ApiModels;

class ApiEntities extends Model{
    use SoftDeleteFlagTrait;

    public $table = 'api.entities';
    public $timestamps = false;
    protected $guarded = [];

    const DELETED_AT = 'is_deleted';
    
    static function relationship(){
        return [
            'ApiSystems'=>[
                'class'=>ApiSystems::class,
                '0'=>'id',
                '1'=>'id_system',
            ],
            'ApiModels'=>[
                'class'=>ApiModels::class,
                '0'=>'id',
                '1'=>'id_main_model', 
            ],
        ];
    }

    function ApiSystems(){
        $model = 'ApiSystems';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function ApiModels(){
        $model = 'ApiModels';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}