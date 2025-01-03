<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\InformationSchema;
use App\Models\ApiEntities;

class ApiModels extends Model{
    use SoftDeleteFlagTrait;

    public $table = 'api.models';
    public $timestamps = false;
    protected $guarded = [];
    
    const DELETED_AT = 'is_deleted';
    
    static function relationship(){
        return [
            'InformationSchema'=>[
                'class'=>InformationSchema::class,
                '0'=>'TABLE_NAME',
                '1'=>'name_table',     
            ],
            'ApiEntities'=>[
                'class'=>ApiEntities::class,
                '0'=>'id_main_model',
                '1'=>'id',     
            ],
        ];
    }

    function InformationSchema(){
        $model = 'InformationSchema';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function ApiEntities(){
        $model = 'ApiEntities';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}