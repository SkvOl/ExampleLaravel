<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\AbtScansTypes;
use App\Models\Docs;


class AbtScans extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'lka_scans';
	public $timestamps = false;
	protected $guarded = [];

	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'Docs'=>[
                'class'=>Docs::class,
				'0'=>'id',
                '1'=>'id_document',
            ],
            'AbtScansTypes'=>[
                'class'=>AbtScansTypes::class,
				'0'=>'id',
                '1'=>'id_type',
            ],
		];
	}

	function Docs(){
		$model = 'Docs';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

	function AbtScansTypes(){
		$model = 'AbtScansTypes';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}
}