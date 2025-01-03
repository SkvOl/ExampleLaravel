<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Achievements;
use App\Models\User;
use App\Models\App;

class DocsIdentity extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'docs';
	public $timestamps = false;
	protected $guarded = [];
	
	const DELETED_AT = 'is_deleted';

	static function relationship(){
		return [
			'Achievements'=>[
				'class'=>Achievements::class,
				'0'=>'id',
				'1'=>'ach_id',
			],
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
			'App'=>[
                'class'=>App::class,
				'0'=>'id_identity_doc',
                '1'=>'id',
            ],
		];
	}

	function Achievements(){
		$model = 'Achievements';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
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

	function App(){
		$model = 'App';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}
}