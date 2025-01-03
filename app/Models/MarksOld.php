<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ExamSchedule;
use App\Models\AbtSubjects;
use App\Models\CompGroups;

class MarksOld extends Model{
	public $table = 'marks';
	public $timestamps = false;
    protected $guarded = [];
    
	static function relationship(){
		return [
			'CompGroups'=>[
                'class'=>CompGroups::class,
                '0'=>'id',
                '1'=>'id_comp_group',  
            ],
			'AbtSubjects'=>[
                'class'=>AbtSubjects::class,
                '0'=>'id',
                '1'=>'subject_id',
            ],
            'ExamSchedule'=>[
                'class'=>ExamSchedule::class,
                '0'=>'subject_id',
                '1'=>'subject_id',
            ],
		];
	}

	function CompGroups(){
        $model = 'CompGroups';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

	function AbtSubjects(){
        $model = 'AbtSubjects';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

    function ExamSchedule(){
        $model = 'ExamSchedule';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}