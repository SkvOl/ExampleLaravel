<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;


class DocsIdentityOther extends Model{
	use SoftDeleteFlagTrait;
	public $table = 'docs';
	public $timestamps = false;
	protected $guarded = [];
	
	const DELETED_AT = 'is_deleted';

	static function relationship(){
		return [
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

		];
	}

	function UserExecution(){
		$model = 'UserExecution';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function DocsTypes(){
		$model = 'DocsTypes';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

}