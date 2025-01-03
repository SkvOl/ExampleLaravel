<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AbtScans;

class AbtScansTypes extends Model{
	public $table = 'lka_scans_types';
	public $timestamps = false;
	protected $guarded = [];
	
	static function relationship(){
		return [
			'AbtScans'=>[
                'class'=>AbtScans::class,
				'0'=>'id_type',
                '1'=>'id',
            ],
		];
	}

	function AbtScans(){
		$model = 'AbtScans';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}
}