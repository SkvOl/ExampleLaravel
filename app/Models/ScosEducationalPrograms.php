<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\ScosStudyPlans;

class ScosEducationalPrograms extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'scos_educational_programs';
	public $timestamps = false;
	protected $guarded = [];

	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'ScosStudyPlans'=>[
				'class'=>ScosStudyPlans::class,
				'0'=>'educational_program',
				'1'=>'external_id',
			],
		];
	}

	function ScosStudyPlans(){
		$model = 'ScosStudyPlans';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}
}