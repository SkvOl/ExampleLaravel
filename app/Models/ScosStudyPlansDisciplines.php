<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\ScosDisciplines;
use App\Models\ScosStudyPlans;


class ScosStudyPlansDisciplines extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'scos_study_plans_disciplines';
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
			'ScosDisciplines'=>[
				'class'=>ScosDisciplines::class,
				'0'=>'external_id',
				'1'=>'discipline',
			],
		];
	}

	function ScosStudyPlans(){
		$model = 'ScosStudyPlans';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function ScosDisciplines(){
		$model = 'ScosDisciplines';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}
}