<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\CompGroups;

class Orders extends Model{
    use SoftDeleteFlagTrait;
	public $table = 'orders';
	public $timestamps = false;
	protected $guarded = [];

    const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'Levels'=>[
                'class'=>Levels::class,
				'0'=>'id',
                '1'=>'level',
            ],
			'Campaigns'=>[
                'class'=>Campaigns::class,
				'0'=>'id_level',
                '1'=>'level',
            ],
			'OrderTypes'=>[
                'class'=>OrderTypes::class,
				'0'=>'id',
                '1'=>'type',
            ],
			'OrderSources'=>[
                'class'=>OrderSources::class,
				'0'=>'id',
                '1'=>'source',
            ],
            'CompGroups'=>[
                'class'=>CompGroups::class,
				'0'=>'order_id',
                '1'=>'id',
            ],
		];
	}

	function Levels(){
        $model = 'Levels';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function Campaigns(){
        $model = 'Campaigns';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function OrderTypes(){
        $model = 'OrderTypes';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function OrderSources(){
        $model = 'OrderSources';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function CompGroups(){
        $model = 'CompGroups';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}