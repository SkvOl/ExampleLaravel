<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use App\Models\ScosStudyPlansDisciplines;
use Illuminate\Database\Eloquent\Model;
use App\Models\ScosMarks;

class ScosDisciplines extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'scos_disciplines';
	public $timestamps = false;
	protected $guarded = [];

	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'ScosStudyPlansDisciplines'=>[
				'class'=>ScosStudyPlansDisciplines::class,
				'0'=>'discipline',
				'1'=>'external_id',
			],
			'ScosMarks'=>[
				'class'=>ScosMarks::class,
				'0'=>'discipline',
				'1'=>'external_id',
			],
		];
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