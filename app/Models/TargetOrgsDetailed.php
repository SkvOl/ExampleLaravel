<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TargetOrgsGroups;
use App\Models\Competitions;


class TargetOrgsDetailed extends Model{
	public $table = 'target_orgs_detailed';
	public $timestamps = false;
	protected $guarded = [];
	
	static function relationship(){
		return [
			'Competitions'=>[
                'class'=>Competitions::class,
				'0'=>'id_detailed',
				'1'=>'id_group',
            ],
			'TargetOrgsGroups'=>[
                'class'=>TargetOrgsGroups::class,
				'0'=>'id',
				'1'=>'id_group',
            ],
		];
	}

	function Competitions(){
        $model = 'Competitions';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function TargetOrgsGroups(){
        $model = 'TargetOrgsGroups';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}