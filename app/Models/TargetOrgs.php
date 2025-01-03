<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\TargetOrgsGroups;
use App\Models\Competitions;


class TargetOrgs extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'target_orgs'; // _detailed
	public $timestamps = false;
	protected $guarded = [];
	
	const DELETED_AT = 'is_deleted';

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