<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;

class ExamBuildings extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'exam_buildings';
	public $timestamps = false;
	protected $guarded = [];
	
	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
		
		];
	}
}