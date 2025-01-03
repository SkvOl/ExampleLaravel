<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\ApiEntities;

class ApiSystems extends Model{
    use SoftDeleteFlagTrait;

    public $table = 'api.systems';
    public $timestamps = false;
    protected $guarded = [];

    const DELETED_AT = 'is_deleted';
    
    static function relationship(){
        return [
            'ApiEntities'=>[
                'class'=>ApiEntities::class,
                '0'=>'id_system',
                '1'=>'id',
            ],
        ];
    }

    function ApiEntities(){
        $model = 'ApiEntities';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}