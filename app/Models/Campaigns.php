<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AbtAchievements;
use App\Models\Direction;

class Campaigns extends Model{
	public $table = 'abt_campaigns';
	public $timestamps = false;
	protected $guarded = [];
	
	static function relationship(){
		return [
			'Direction'=>[
				'class'=>Direction::class,
				'0'=>'id_level',
				'1'=>'id_level',
			],
			'AbtAchievements'=>[
				'class'=>AbtAchievements::class,
				'0'=>'id_level',
				'1'=>'id_level',
			],
		];
	}

	function Direction(){
        $model = 'Direction';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function AbtAchievements(){
        $model = 'AbtAchievements';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}