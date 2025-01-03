<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\CompGroupsStatuses;
use App\Models\Competitions;
use App\Models\MarksOld;
use App\Models\App;



class CompGroups extends Model{
    use SoftDeleteFlagTrait;

	public $table = 'abt_comp_groups';
	public $timestamps = false;
	protected $guarded = [];

    const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'App'=>[
                'class'=>App::class,
				'0'=>'id',
                '1'=>'id_app',
            ],
			'Competitions'=>[
                'class'=>Competitions::class,
				'0'=>'id',
				'1'=>'id_competition',
            ],
			'CompGroupsStatuses'=>[
                'class'=>CompGroupsStatuses::class,
				'0'=>'id_comp_group',
				'1'=>'id',
            ],
			'MarksOld'=>[
                'class'=>MarksOld::class,
				'0'=>'id_comp_group',
                '1'=>'id',
            ],
		];
	}

	function App(){
        $model = 'App';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function Competitions(){
        $model = 'Competitions';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function CompGroupsStatuses(){
        $model = 'CompGroupsStatuses';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function MarksOld(){
        $model = 'MarksOld';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}