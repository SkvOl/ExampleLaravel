<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserExecution;
use App\Models\Achievements;
use App\Models\Campaigns;
use App\Models\Docs;


class AbtAchievements extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'abt_ach';
	public $timestamps = false;
	protected $guarded = [];
	
	const DELETED_AT = 'is_deleted';

	static function relationship(){
		return [
			'Achievements'=>[
				'class'=>Achievements::class,
				'0'=>'id',
				'1'=>'id_ach',
			],
			'Docs'=>[
				'class'=>Docs::class,
				'0'=>'id',
				'1'=>'id_document',
			],
			'UserExecution'=>[
				'class'=>UserExecution::class,
				'0'=>'id',
				'1'=>'id_execution',
			],
			'Campaigns'=>[
				'class'=>Campaigns::class,
				'0'=>'id_level',
				'1'=>'id_level',
			],
			'App'=>[
				'class'=>App::class,
				'0'=>'id_execution',
				'1'=>'id_execution',
			],
		];
	}

	function UserExecution(){
        $model = 'UserExecution';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function Campaigns(){
        $model = 'Campaigns';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function Docs(){
        $model = 'Docs';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function Achievements(){
        $model = 'Achievements';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function App(){
        $model = 'App';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}