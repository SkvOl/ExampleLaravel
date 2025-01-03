<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\ScosStudyPlansStudents;
use App\Models\ScosMarks;

class ScosStudents extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'scos_students';
	public $timestamps = false;
	protected $guarded = [];

	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'ScosStudyPlansStudents'=>[
				'class'=>ScosStudyPlansStudents::class,
				'0'=>'student',
				'1'=>'external_id',
			],
			'ScosMarks'=>[
				'class'=>ScosMarks::class,
				'0'=>'student',
				'1'=>'external_id',
			],
		];
	}

	function ScosStudyPlansStudents(){
		$model = 'ScosStudyPlansStudents';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function ScosMarks(){
		$model = 'ScosMarks';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}
}