<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Direction;
use App\Models\AbtDates;


class Levels extends Model{
	public $table = 'levels';
	public $timestamps = false;
	protected $guarded = [];
	
	static function relationship(){
		return [
			'AbtDates'=>[
				'class'=>AbtDates::class,
				'0'=>'id_level',
				'1'=>'id',
			],
			'Direction'=>[
				'class'=>Direction::class,
				'0'=>'id_level',
				'1'=>'id',
			],
		];
	}

	function AbtDates(){
		$model = 'AbtDates';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function Direction(){
		$model = 'Direction';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}
}