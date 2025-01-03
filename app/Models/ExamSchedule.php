<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\ExamBuildings;
use App\Models\Competitions;
use App\Models\MarksOld;
use App\Models\CgExams;
use App\Models\Marks;

class ExamSchedule extends Model{
    use SoftDeleteFlagTrait;

	public $table = 'exam_schedule';
	public $timestamps = false;
    protected $guarded = [];

    const DELETED_AT = 'is_deleted';
    
	static function relationship(){
		return [
			'Competitions'=>[
                'class'=>Competitions::class,
				'0'=>'id_form',
				'1'=>'id',
            ],
            'ExamBuildings'=>[
                'class'=>ExamBuildings::class,
				'0'=>'id',
				'1'=>'building',
            ],
            'MarksOld'=>[
                'class'=>MarksOld::class,
                '0'=>'subject_id',
                '1'=>'subject_id',
            ],
            'CgExams'=>[
                'class'=>CgExams::class,
				'0'=>'subject_id',
				'1'=>'subject_id',
            ],
            'Marks'=>[
                'class'=>Marks::class,
                '0'=>'id_subject',
                '1'=>'subject_id',
            ],
		];
	}

	function Competitions(){
        $model = 'Competitions';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function ExamBuildings(){
        $model = 'ExamBuildings';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function MarksOld(){
        $model = 'MarksOld';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
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