<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\ScosStudyPlans;
use App\Models\ScosStudents;

class ScosStudyPlansStudents extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'scos_study_plans_students';
	public $timestamps = false;
	protected $guarded = [];

	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'ScosStudyPlans'=>[
				'class'=>ScosStudyPlans::class,
				'0'=>'external_id',
				'1'=>'study_plan',
			],
			'ScosStudents'=>[
				'class'=>ScosStudents::class,
				'0'=>'external_id',
				'1'=>'student',
			],
		];
	}

	function ScosStudyPlans(){
		$model = 'ScosStudyPlans';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function ScosStudents(){
		$model = 'ScosStudents';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}
}