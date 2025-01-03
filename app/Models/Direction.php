<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\HeadOrganization;
use App\Models\Competitions;
use App\Models\AbtProfiles;
use App\Models\Campaigns;
use App\Models\CgExams;
use App\Models\Levels;


class Direction extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'abt_directions';
	public $timestamps = false;
	protected $guarded = [];
	
	const DELETED_AT = 'is_deleted';

	static function relationship(){
		return [
			'Competitions'=>[
                'class'=>Competitions::class,
				'0'=>'id_direction',
				'1'=>'id',
            ],
			'Levels'=>[
				'class'=>Levels::class,
				'0'=>'id',
				'1'=>'id_level',
			],
			'Campaigns'=>[
				'class'=>Campaigns::class,
				'0'=>'id_level',
				'1'=>'id_level',
			],
			'AbtProfiles'=>[
                'class'=>AbtProfiles::class,
				'0'=>'id_direction',
				'1'=>'id',
            ],
			'CgExams'=>[
                'class'=>CgExams::class,
				'0'=>'spec_id',
				'1'=>'id',
            ],
			'HeadOrganization'=>[
                'class'=>HeadOrganization::class,
				'0'=>'id',
				'1'=>'head_id',
            ],
		];
	}

	function Competitions(){
        $model = 'Competitions';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function Levels(){
        $model = 'Levels';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function Campaigns(){
        $model = 'Campaigns';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function AbtProfiles(){
        $model = 'AbtProfiles';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function CgExams(){
        $model = 'CgExams';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function HeadOrganization(){
        $model = 'HeadOrganization';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}