<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserExecution;
use App\Models\Department;

class UserStudyGroup extends Model{
    use SoftDeleteFlagTrait;

    public $table = 'user_study_group';
    public $timestamps = false;
    protected $guarded = [];
    
    const DELETED_AT = 'is_deleted';

    static function relationship(){
        return [
            'UserExecution'=>[
                'class'=>UserExecution::class,
                '0'=>'id_position',
                '1'=>'id',
            ],
            'Department'=>[
                'class'=>Department::class,
                '0'=>'id',
                '1'=>'id_department',
            ],
        ];
    }

    function UserExecution(){
        $model = 'UserExecution';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function Department(){
        $model = 'Department';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}