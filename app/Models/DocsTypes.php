<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Docs;

class DocsTypes extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'docs_types';
	public $timestamps = false;
	protected $guarded = [];
	
	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'Docs'=>[
                'class'=>Docs::class,
				'0'=>'id_type',
                '1'=>'id',
            ],
			'FisDocsTypes'=>[
                'class'=>FisDocsTypes::class,
				'0'=>'id_fisservice',
                '1'=>'id_fisservice',
            ],
		];
	}

	function Docs(){
		$model = 'Docs';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function FisDocsTypes(){
		$model = 'FisDocsTypes';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}
}