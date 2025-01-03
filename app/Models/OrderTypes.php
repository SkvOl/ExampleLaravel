<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTypes extends Model{
	public $table = 'order_types';
	public $timestamps = false;
	protected $guarded = [];
	
	static function relationship(){
		return [
			'Orders'=>[
                'class'=>Orders::class,
				'0'=>'type',
                '1'=>'id',
            ],
			
		];
	}

	function Orders(){
		$model = 'Orders';
		return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
	}

}