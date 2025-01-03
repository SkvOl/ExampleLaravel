<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ApiModels;

class InformationSchema extends Model{
    public $table = 'INFORMATION_SCHEMA.KEY_COLUMN_USAGE';
    public $timestamps = false;
    protected $guarded = [];
    
    static function relationship(){
        return [
            'ApiModels'=>[
                'class'=>ApiModels::class,
                '0'=>'name_table', 
                '1'=>'TABLE_NAME', 
            ],

            'ApiModelsRef'=>[
                'class'=>ApiModels::class,
                '0'=>'name_table', 
                '1'=>'REFERENCED_TABLE_NAME', 
            ],
        ];
    }

    function ApiModels(){
        $model = 'ApiModels';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function ApiModelsRef(){
        $model = 'ApiModelsRef';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}