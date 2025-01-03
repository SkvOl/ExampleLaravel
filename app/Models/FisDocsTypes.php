<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FisDocsTypes extends Model{
	public $table = 'abt_fis_docs_types';
	public $timestamps = false;
	protected $guarded = [];
	
	static function relationship(){
		return [
			'DocsTypes'=>[
                'class'=>DocsTypes::class,
				'0'=>'id_fisservice',
                '1'=>'id_fisservice',
            ],
		];
	}

	function DocsTypes(){
		$model = 'DocsTypes';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}
}