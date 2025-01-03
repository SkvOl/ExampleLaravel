<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\AbtAchievements;
use App\Models\DocsTypes;

class Achievements extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'ach';
	public $timestamps = false;
	protected $guarded = [];

	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'DocsTypes'=>[
				'class'=>DocsTypes::class,
				'0'=>'id_superservice',
				'1'=>'id_superservice_doc_type',
			],
			'AbtAchievements'=>[
				'class'=>AbtAchievements::class,
				'0'=>'id_ach',
				'1'=>'id',
			],
			'Campaigns'=>[
				'class'=>Campaigns::class,
				'0'=>'id_level',
				'1'=>'level',
			],
		];
	}

	function DocsTypes(){
		$model = 'DocsTypes';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function AbtAchievements(){
		$model = 'AbtAchievements';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function Campaigns(){
        $model = 'Campaigns';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}