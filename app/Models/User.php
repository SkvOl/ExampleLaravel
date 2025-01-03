<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\SuperServiceLogs;
use App\Models\UserExecution;
use App\Models\DocsSnils;
use App\Models\ApiRights;
use App\Models\Addresses;
use App\Models\UserAdd;
use App\Models\UserExp;
use App\Models\UserFio;
use App\Models\Docs;

class User extends Model{
    use SoftDeleteFlagTrait;

    public $table = 'user';
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
            'SuperServiceLogs'=>[
                'class'=>SuperServiceLogs::class,
                '0'=>'id_user',
                '1'=>'id',
            ],
            'UserExecution'=>[
                'class'=>UserExecution::class,
                '0'=>'id_user',
                '1'=>'id',
            ],
            'UserAdd'=>[
                'class'=>UserAdd::class,
                '0'=>'id_user',
                '1'=>'id',
            ],
            'UserExp'=>[
                'class'=>UserExp::class,
                '0'=>'id_user',
                '1'=>'id',
            ],
            'ApiRights'=>[
                'class'=>ApiRights::class,
                '0'=>'id_user',
                '1'=>'id',
            ],
            'Addresses'=>[
                'class'=>Addresses::class,
                '0'=>'id_user',
                '1'=>'id',
            ],
            'UserFio'=>[
                'class'=>UserFio::class,
                '0'=>'id_user',
                '1'=>'id',
            ],
            'Docs'=>[
                'class'=>Docs::class,
                '0'=>'id_user',
                '1'=>'id',
            ],
            'DocsIdentity'=>[
                'class'=>Docs::class,
                '0'=>'id_user',
                '1'=>'id',
            ],
            'DocsStudy'=>[
                'class'=>Docs::class,
                '0'=>'id_user',
                '1'=>'id',
            ],
            'DocsSnils'=>[
                'class'=>DocsSnils::class,
                '0'=>'id_user',
                '1'=>'id',
            ],
        ];
    }

    function SuperServiceLogs(){
        $model = 'SuperServiceLogs';
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

    function UserExp(){
        $model = 'UserExp';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function User(){
        $model = 'User';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function ApiRights(){
        $model = 'ApiRights';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function Addresses(){
        $model = 'Addresses';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function UserFio(){
        $model = 'UserFio';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function Docs(){
        $model = 'Docs';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function DocsIdentity(){
        $model = 'DocsIdentity';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function DocsStudy(){
        $model = 'DocsStudy';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function DocsSnils(){
        $model = 'DocsSnils';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
    public function rooms()
    {
        return $this->hasMany(Room::class, 'owner_id');
    }
}
