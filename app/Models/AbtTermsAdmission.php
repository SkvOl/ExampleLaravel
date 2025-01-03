<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\AbtAdmissionEvents;
use App\Models\CompetitionsTypes;
use App\Models\AdmissionStages;
use App\Models\EducationForm;
use App\Models\Levels;

class AbtTermsAdmission extends Model{
    use SoftDeleteFlagTrait;

	public $table = 'abt_terms_admission_2024_test';
	public $timestamps = false;
    protected $guarded = [];

    const DELETED_AT = 'is_deleted';
    
	static function relationship(){
		return [
            'AdmissionStages'=>[
                'class'=>AdmissionStages::class,
                '0'=>'id',
				'1'=>'id_admission_stage',
            ],
            'Levels'=>[
                'class'=>Levels::class,
                '0'=>'id',
				'1'=>'id_level',
            ],
			'AbtAdmissionEvents'=>[
                'class'=>AbtAdmissionEvents::class,
                '0'=>'id',
				'1'=>'id_event',
            ],
            'CompetitionsTypes'=>[
                'class'=>CompetitionsTypes::class,
                '0'=>'id',
				'1'=>'id_place_type',
            ],
            'EducationForm'=>[
                'class'=>EducationForm::class,
                '0'=>'id',
				'1'=>'id_form',
            ],
		];
	}

    function AdmissionStages(){
        $model = 'AdmissionStages';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
    
    function Levels(){
        $model = 'Levels';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function AbtAdmissionEvents(){
        $model = 'AbtAdmissionEvents';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function CompetitionsTypes(){
        $model = 'CompetitionsTypes';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function EducationForm(){
        $model = 'EducationForm';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}