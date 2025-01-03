<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Competitions;
use App\Models\Direction;



class AbtProfiles extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'abt_profiles';
	public $timestamps = false;
	protected $guarded = [];

	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'Direction'=>[
                'class'=>Direction::class,
				'0'=>'id',
				'1'=>'id_direction',
            ],

			'Competitions'=>[
                'class'=>Competitions::class,
				'0'=>'id_profile',
				'1'=>'id',
            ],
		];
	}

	function Direction(){
        $model = 'Direction';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
	
	function Competitions(){
        $model = 'Competitions';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}