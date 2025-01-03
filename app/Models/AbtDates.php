<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\AbtDatesTypes;
use App\Models\AbtConfig;

class AbtDates extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'abt_dates';
	public $timestamps = false;
	protected $guarded = [];

	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'AbtDatesTypes'=>[
				'class'=>AbtDatesTypes::class,
				'0'=>'id',
				'1'=>'id_type',
			],
			'AbtConfig'=>[
				'class'=>AbtConfig::class,
				'0'=>'id',
				'1'=>'id_abt_config',
			],
		];
	}

	function AbtDatesTypes(){
		$model = 'AbtDatesTypes';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function AbtConfig(){
		$model = 'AbtConfig';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}
}