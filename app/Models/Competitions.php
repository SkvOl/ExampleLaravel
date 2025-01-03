<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\TargetOrgsDetailed;
use App\Models\CompetitionsTypes;
use App\Models\TargetOrgsGroups;
use App\Models\AdmissionStages;
use App\Models\EducationForm;
use App\Models\AbtProfiles;
use App\Models\CompGroups;
use App\Models\Direction;
use App\Models\CgExams;

class Competitions extends Model{
    use SoftDeleteFlagTrait;

	public $table = 'abt_competitions';
	public $timestamps = false;
    protected $guarded = [];

    const DELETED_AT = 'is_deleted';
    
	static function relationship(){
		return [
			'CompGroups'=>[
                'class'=>CompGroups::class,
				'0'=>'id_competition',
				'1'=>'id',
            ],
			'Direction'=>[
                'class'=>Direction::class,
				'0'=>'id',
				'1'=>'id_direction',
            ],
			'EducationForm'=>[
                'class'=>EducationForm::class,
				'0'=>'id',
				'1'=>'id_form',
            ],
			'CompetitionsTypes'=>[
                'class'=>CompetitionsTypes::class,
				'0'=>'id',
				'1'=>'id_type',
            ],
			'TargetOrgsGroups'=>[
                'class'=>TargetOrgsGroups::class,
				'0'=>'id',
				'1'=>'id_detailed',
            ],
			'CgExams'=>[
                'class'=>CgExams::class,
				'0'=>'spec_id',
				'1'=>'id_direction',
            ],
            'AdmissionStages'=>[
                'class'=>AdmissionStages::class,
                '0'=>'id',
                '1'=>'id_admission_stage',
            ],
            'AbtProfiles'=>[
                'class'=>AbtProfiles::class,
                '0'=>'id',
                '1'=>'id_profile',
            ],
            'TargetOrgsDetailed'=>[
                'class'=>TargetOrgsDetailed::class,
				'0'=>'id_group',
                '1'=>'id_detailed',
            ],
		];
	}

	function CompGroups(){
        $model = 'CompGroups';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function Direction(){
        $model = 'Direction';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function EducationForm(){
        $model = 'EducationForm';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function CompetitionsTypes(){
        $model = 'CompetitionsTypes';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function TargetOrgsGroups(){
        $model = 'TargetOrgsGroups';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function CgExams(){
        $model = 'CgExams';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function AdmissionStages(){
        $model = 'AdmissionStages';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function AbtProfiles(){
        $model = 'AbtProfiles';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function TargetOrgsDetailed(){
        $model = 'TargetOrgsDetailed';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}