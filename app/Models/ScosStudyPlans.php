<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use App\Models\ScosStudyPlansDisciplines;
use Illuminate\Database\Eloquent\Model;
use App\Models\ScosEducationalPrograms;
use App\Models\ScosStudyPlansStudents;
use App\Models\ScosMarks;


class ScosStudyPlans extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'scos_study_plans';
	public $timestamps = false;
	protected $guarded = [];

	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'ScosEducationalPrograms'=>[
				'class'=>ScosEducationalPrograms::class,
				'0'=>'external_id',
				'1'=>'educational_program',
			],
			'ScosStudyPlansStudents'=>[
				'class'=>ScosStudyPlansStudents::class,
				'0'=>'study_plan',
				'1'=>'external_id',
			],
			'ScosStudyPlansDisciplines'=>[
				'class'=>ScosStudyPlansDisciplines::class,
				'0'=>'study_plan',
				'1'=>'external_id',
			],
			'ScosMarks'=>[
				'class'=>ScosMarks::class,
				'0'=>'study_plan',
				'1'=>'external_id',
			],
		];
	}

	function ScosEducationalPrograms(){
		$model = 'ScosEducationalPrograms';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function ScosStudyPlansStudents(){
		$model = 'ScosStudyPlansStudents';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function ScosStudyPlansDisciplines(){
		$model = 'ScosStudyPlansDisciplines';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function ScosMarks(){
		$model = 'ScosMarks';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}
}