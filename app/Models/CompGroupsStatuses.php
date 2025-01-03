<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CompGroups;

class CompGroupsStatuses extends Model{
	public $table = 'abt_comp_groups_statuses';
	public $timestamps = false;
	protected $guarded = [];
	
	static function relationship(){
		return [
			'CompGroups'=>[
                'class'=>CompGroups::class,
				'0'=>'id',
                '1'=>'id_comp_group',
            ],
		];
	}

	function CompGroups(){
        $model = 'CompGroups';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}