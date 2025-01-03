<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Addresses;

class Countries extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'countries';
	public $timestamps = false;
	protected $guarded = [];
	
	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'Addresses'=>[
                'class'=>Addresses::class,
				'0'=>'id_country',
				'1'=>'id', 
            ],
		];
	}

	function Addresses(){
        $model = 'Addresses';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}