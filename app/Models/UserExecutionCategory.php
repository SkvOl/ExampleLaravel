<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserExecution;

class UserExecutionCategory extends Model{
    use SoftDeleteFlagTrait;

    public $table = 'user_execution_category';
    public $timestamps = false;
    protected $guarded = [];

    const DELETED_AT = 'is_deleted';
    
    static function relationship(){
        return [
            'UserExecution'=>[
                'class'=>UserExecution::class,
                '0'=>'id_category',
                '1'=>'id',
            ],
        ];
    }

    function UserExecution(){
        $model = 'UserExecution';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}