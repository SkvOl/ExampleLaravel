<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\CgExams;

class AbtSubjects extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'abt_subjects';
	public $timestamps = false;
	protected $guarded = [];

	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'CgExams'=>[
                'class'=>CgExams::class,
				'0'=>'subject_id',
				'1'=>'id',
            ],
			'Marks'=>[
                'class'=>Marks::class,
				'0'=>'id_subject',
				'1'=>'id',
            ],
		];
	}

	function CgExams(){
        $model = 'CgExams';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function Marks(){
        $model = 'Marks';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}