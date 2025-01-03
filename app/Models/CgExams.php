<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Competitions;
use App\Models\ExamSchedule;
use App\Models\AbtSubjects;
use App\Models\Direction;

class CgExams extends Model{
	public $table = 'abt_cg_exams';
	public $timestamps = false;
	protected $guarded = [];
	
	static function relationship(){
		return [
			'Competitions'=>[
                'class'=>Competitions::class,
				'0'=>'id_direction',
				'1'=>'spec_id',
            ],
			'Direction'=>[
                'class'=>Direction::class,
				'0'=>'id',
				'1'=>'spec_id',
            ],
			'AbtSubjects'=>[
                'class'=>AbtSubjects::class,
				'0'=>'id',
				'1'=>'subject_id',
            ],
			'CgExams'=>[
                'class'=>CgExams::class,
				'0'=>'spec_id',
				'1'=>'spec_id',
            ],
			'ExamSchedule'=>[
                'class'=>ExamSchedule::class,
				'0'=>'subject_id',
				'1'=>'subject_id',
            ],
		];
	}

	function Competitions(){
        $model = 'Competitions';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function AbtSubjects(){
        $model = 'AbtSubjects';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function Direction(){
        $model = 'Direction';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function CgExams(){
        $model = 'CgExams';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function ExamSchedule(){
        $model = 'ExamSchedule';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}