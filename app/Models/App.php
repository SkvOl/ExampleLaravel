<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use App\Http\sources\ConfigurableTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\SuperServiceLogs;
use App\Models\AdmissionStages;
use App\Models\AbtFisEgeStatus;
use App\Models\UserExecution;
use App\Models\AddInfoSource;
use App\Models\CompGroups;
use App\Models\Marks;
use App\Models\Docs;


class App extends Model{
    use ConfigurableTrait;
    use SoftDeleteFlagTrait;

    public $table = 'abt_app';
    public $timestamps = false;
    public static $model_id = 6;
    protected $guarded = [];

    const DELETED_AT = 'is_deleted';
    
    static function relationship(){
        return [
            'SuperServiceLogs'=>[
                'class'=>SuperServiceLogs::class,
                '0'=>'id_user',
                '1'=>'id_user',
            ],
            'CompGroups'=>[
                'class'=>CompGroups::class,
                '0'=>'id_app',
                '1'=>'id',
            ],
            'UserExecution'=>[
                'class'=>UserExecution::class,
                '0'=>'id',
                '1'=>'id_execution',
            ],
            'DocsIdentity'=>[
                'class'=>Docs::class,
                '0'=>'id',
                '1'=>'id_identity_doc',
            ],
            'DocsStudy'=>[
                'class'=>Docs::class,
                '0'=>'id',
                '1'=>'id_prev_study_doc',
            ],
            'AddInfoSource'=>[
                'class'=>AddInfoSource::class,
                '0'=>'id',
                '1'=>'id_source',
            ],
            'AdmissionStages'=>[
                'class'=>AdmissionStages::class,
                '0'=>'id',
                '1'=>'id_admission_stage',
            ],
            'Direction'=>[
                'class'=>Direction::class,
                '0'=>'id',
                '1'=>'id',
            ],
            'AbtFisEgeStatus'=>[
                'class'=>AbtFisEgeStatus::class,
                '0'=>'id_execution',
                '1'=>'id_execution',
            ],
            'Marks'=>[
                'class'=>Marks::class,
                '0'=>'id_execution',
                '1'=>'id_execution',
            ],

            'AbtAchievements'=>[
                'class'=>AbtAchievements::class,
                '0'=>'id_execution',
                '1'=>'id_execution',
            ],
        ];
    }

    function CompGroups(){
        $model = 'CompGroups';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function SuperServiceLogs(){
        $model = 'SuperServiceLogs';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function UserExecution(){
        $model = 'UserExecution';
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

    function AddInfoSource(){
        $model = 'AddInfoSource';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function AdmissionStages(){
        $model = 'AdmissionStages';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function Direction(){
        $model = 'Direction';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function AbtFisEgeStatus(){
        $model = 'AbtFisEgeStatus';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function Marks(){
        $model = 'Marks';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
    
    function AbtAchievements(){
        $model = 'AbtAchievements';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}
