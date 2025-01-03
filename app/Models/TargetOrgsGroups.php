<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\TargetOrgsDetailed;
use App\Models\Competitions;


class TargetOrgsGroups extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'target_orgs_groups';
	public $timestamps = false;
	protected $guarded = [];
	
	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'Competitions'=>[
                'class'=>Competitions::class,
				'0'=>'id_detailed',
				'1'=>'id',
            ],
			'TargetOrgsDetailed'=>[
                'class'=>TargetOrgsDetailed::class,
				'0'=>'id_group',
				'1'=>'id',
            ],
		];
	}

	function Competitions(){
        $model = 'Competitions';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function TargetOrgsDetailed(){
        $model = 'TargetOrgsDetailed';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}