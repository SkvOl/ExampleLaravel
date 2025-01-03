<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SuperServiceLogs;
use App\Models\UserExecution;
use App\Models\CompGroups;
use App\Models\User;

class AsuContracts extends Model{
    public $table = 'asu_contracts';
    public $timestamps = false;
    protected $guarded = [];
    
    static function relationship(){
        return [
            'SuperServiceLogs'=>[
                'class'=>SuperServiceLogs::class,
                '0'=>'id_user',
                '1'=>'eios_id',
            ],
            'CompGroups'=>[
                'class'=>CompGroups::class,
                '0'=>'id',
                '1'=>'id_comp_group',
            ],
            'UserExecution'=>[
                'class'=>UserExecution::class,
                '0'=>'id_user',
                '1'=>'eios_id',
            ],
            'User'=>[
                'class'=>User::class,
                '0'=>'id',
                '1'=>'eios_id',
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

    function CompGroups(){
        $model = 'CompGroups';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function User(){
        $model = 'User';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

}
