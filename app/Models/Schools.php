<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;

class Schools extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'schools';
	public $timestamps = false;
	protected $guarded = [];

	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'Docs'=>[
                'class'=>Docs::class,
				'0'=>'dop_info3',
                '1'=>'id',
            ],
			'DocsStudy'=>[
                'class'=>DocsStudy::class,
				'0'=>'dop_info3',
                '1'=>'id',
            ],
		];
	}

	function Docs(){
		$model = 'Docs';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function DocsStudy(){
		$model = 'DocsStudy';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}
}