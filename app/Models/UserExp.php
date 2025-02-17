<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserExecution;
use App\Models\UserAdd;
use App\Models\User;


class UserExp extends Model{
    use SoftDeleteFlagTrait;

    public $table = 'user_exp';
    public $timestamps = false;
    protected $guarded = [];
    
    const DELETED_AT = 'is_deleted';

    static function relationship(){
        return [
            'User'=>[
                'class'=>User::class,
                '0'=>'id',
                '1'=>'id_user',    
            ],
            'UserExecution'=>[
                'class'=>UserExecution::class,
                '0'=>'id_user',
                '1'=>'id_user',
            ],
            'UserAdd'=>[
                'class'=>UserAdd::class,
                '0'=>'id_user',
                '1'=>'id_user',
            ],
        ];
    }

    function User(){
        $model = 'User';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function UserExecution(){
        $model = 'UserExecution';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function UserAdd(){
        $model = 'UserAdd';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}