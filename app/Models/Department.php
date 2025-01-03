<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserStudyGroup;
use App\Models\UserExecution;

class Department extends Model{
    use SoftDeleteFlagTrait;

    public $table = 'department';
    public $timestamps = false;
    protected $guarded = [];

    const DELETED_AT = 'is_deleted';
    
    static function relationship(){
        return [
            'UserExecution'=>[
                'class'=>UserExecution::class,
                '0'=>'id_department',
                '1'=>'id',
            ],
            'UserStudyGroup'=>[
                'class'=>UserStudyGroup::class,
                '0'=>'id_department',
                '1'=>'id',
            ],
            'Department'=>[
                'class'=>Department::class,
                '0'=>'id',
                '1'=>'id_parent',
            ],
        ];
    }

    function Department(){
        $model = 'Department';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function UserStudyGroup(){
        $model = 'UserStudyGroup';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function UserExecution(){
        $model = 'UserExecution';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
    public function room()
    {
        return $this->hasMany(Room::class, 'id_dep');
    }
}