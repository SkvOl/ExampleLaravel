<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserExecution;
use App\Models\UserExp;
use App\Models\User;


class UserAdd extends Model{
    use SoftDeleteFlagTrait;

    public $table = 'user_add';
    public $timestamps = false;
    protected $guarded = [];

    const DELETED_AT = 'is_deleted';
    
    static function relationship(){
        return [
            'User'=>[
                'class'=>User::class,
                '1'=>'id_user',
                '0'=>'id',
            ],
            'UserExecution'=>[
                'class'=>UserExecution::class,
                '0'=>'id_user',
                '1'=>'id_user',
            ],
            'UserExp'=>[
                'class'=>UserExp::class,
                '0'=>'id_user',
                '1'=>'id_user',
            ],
            'Countries'=>[
                'class'=>Countries::class,
                '0'=>'id',
                '1'=>'id_country',
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

    function UserExp(){
        $model = 'UserExp';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function Countries(){
        $model = 'Countries';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}