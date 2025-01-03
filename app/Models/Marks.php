<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ExamSchedule;
use App\Models\App;

class Marks extends Model{
	public $table = 'abt_marks';
	public $timestamps = false;
    protected $guarded = [];
    
	static function relationship(){
		return [
			'UserExecution'=>[
                'class'=>UserExecution::class,
                '0'=>'id',
                '1'=>'id_execution',
            ],
			'AbtSubjects'=>[
                'class'=>AbtSubjects::class,
                '0'=>'id',
                '1'=>'id_subject',
            ],
            'ExamSchedule'=>[
                'class'=>ExamSchedule::class,
                '0'=>'subject_id',
                '1'=>'id_subject',
            ],
            'App'=>[
                'class'=>App::class,
                '0'=>'id_execution',
                '1'=>'id_execution',
            ],
		];
	}

	function UserExecution(){
        $model = 'UserExecution';
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

    function App(){
        $model = 'App';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}