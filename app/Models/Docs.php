<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\AbtAchievements;
use App\Models\UserExecution;
use App\Models\DocsTypes;
use App\Models\AbtScans;
use App\Models\User;

class Docs extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'docs';
	public $timestamps = false;
	protected $guarded = [];
	
	const DELETED_AT = 'is_deleted';

	static function relationship(){
		return [
			'User'=>[
				'class'=>User::class,
				'0'=>'id',
				'1'=>'id_user',
			],
			'UserExecution'=>[
                'class'=>UserExecution::class,
                '0'=>'id_user',
                '1'=>'id_user',
            ],
			'DocsTypes'=>[
                'class'=>DocsTypes::class,
                '0'=>'id',
                '1'=>'id_type',
            ],
			'Schools'=>[
                'class'=>Schools::class,
                '0'=>'id',
                '1'=>'dop_info3',
            ],
			'AbtAchievements'=>[
				'class'=>AbtAchievements::class,
				'0'=>'id_document',
				'1'=>'id',
			],
			'AbtScans'=>[
                'class'=>AbtScans::class,
				'0'=>'id_document',
				'1'=>'id',
            ],
		];
	}

	function User(){
		$model = 'User';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function UserExecution(){
		$model = 'UserExecution';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function DocsTypes(){
		$model = 'DocsTypes';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function Schools(){
		$model = 'Schools';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function AbtAchievements(){
		$model = 'AbtAchievements';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function AbtScans(){
		$model = 'AbtScans';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}
}