<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserExecutionCategory;
use App\Models\UserExecutionKind;
use App\Models\SuperServiceLogs;
use App\Models\UserStudyGroup;
use App\Models\UserPosition;
use App\Models\Department;
use App\Models\ApiRights;
use App\Models\Addresses;
use App\Models\UserFio;
use App\Models\UserAdd;
use App\Models\UserExp;
use App\Models\Marks;
use App\Models\User;
use App\Models\Docs;
use App\Models\App;


class UserExecution extends Model{
    use SoftDeleteFlagTrait;

    public $table = 'user_execution';
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
            'UserAdd'=>[
                'class'=>UserAdd::class,
                '0'=>'id_user',
                '1'=>'id_user',
            ],
            'UserExp'=>[
                'class'=>UserExp::class,
                '0'=>'id_user',
                '1'=>'id_user',
            ],
            'Department'=>[
                'class'=>Department::class,
                '0'=>'id',
                '1'=>'id_department',
            ],
            'UserExecutionKind'=>[
                'class'=>UserExecutionKind::class,
                '0'=>'id',
                '1'=>'id_kind',
            ],
            'UserExecutionCategory'=>[
                'class'=>UserExecutionCategory::class,
                '0'=>'id',
                '1'=>'id_category',
            ],
            'UserStudyGroup'=>[
                'class'=>UserStudyGroup::class,
                '0'=>'id',
                '1'=>'id_position',
            ],
            'UserPosition'=>[
                'class'=>UserPosition::class,
                '0'=>'id',
                '1'=>'id_position',
            ],
            'ApiRights'=>[
                'class'=>ApiRights::class,
                '0'=>'id_user',
                '1'=>'id_user',
            ],
            'Addresses'=>[
                'class'=>Addresses::class,
                '0'=>'id_user',
                '1'=>'id_user',
            ],
            'UserFio'=>[
                'class'=>UserFio::class,
                '0'=>'id_user',
                '1'=>'id_user',
            ],
            'Docs'=>[
                'class'=>Docs::class,
                '0'=>'id_user',
                '1'=>'id_user',
            ],
            'App'=>[
                'class'=>App::class,
                '0'=>'id_execution',
                '1'=>'id',
            ],
            'SuperServiceLogs'=>[
                'class'=>SuperServiceLogs::class,
                '0'=>'id_user',
                '1'=>'id_user',
            ],
            'Marks'=>[
                'class'=>Marks::class,
                '0'=>'id_execution',
                '1'=>'id',
            ],

            'DocsIdentityOther'=>[
                'class'=>DocsIdentityOther::class,
                '0'=>'id_user',
                '1'=>'id_user',
            ],

            'AbtFisEgeStatus'=>[
                'class'=>AbtFisEgeStatus::class,
                '0'=>'id_execution',
                '1'=>'id',
            ],
        ];
    }

    function User(){
        $model = 'User';
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

    function Department(){
        $model = 'Department';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function UserExecutionKind(){
        $model = 'UserExecutionKind';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function UserExecutionCategory(){
        $model = 'UserExecutionCategory';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function UserStudyGroup(){
        $model = 'UserStudyGroup';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function UserPosition(){
        $model = 'UserPosition';
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

    function App(){
        $model = 'App';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function SuperServiceLogs(){
        $model = 'SuperServiceLogs';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function Marks(){
        $model = 'Marks';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
    
    function DocsIdentityOther(){
        $model = 'DocsIdentityOther';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function AbtFisEgeStatus(){
        $model = 'AbtFisEgeStatus';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
    public function socetExecution()
    {
        return $this->hasMany(PhoneSocketExecution::class, 'id','id_execution');
    }
}