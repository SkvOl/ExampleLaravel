<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Competitions;

class CompetitionsTypes extends Model{
	public $table = 'abt_competitions_types';
	public $timestamps = false;
	protected $guarded = [];
	
	static function relationship(){
		return [
			'Competitions'=>[
                'class'=>Competitions::class,
				'0'=>'id_type',
				'1'=>'id',
            ],
		];
	}

	function Competitions(){
        $model = 'Competitions';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}