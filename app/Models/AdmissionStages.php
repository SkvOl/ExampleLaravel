<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Competitions;
use App\Models\App;

class AdmissionStages extends Model{
	public $table = 'abt_admission_stages';
	public $timestamps = false;
    protected $guarded = [];
    
	static function relationship(){
		return [
			'App'=>[
                'class'=>App::class,
                '0'=>'id_admission_stage',
				'1'=>'id',
            ],
			'Competitions'=>[
                'class'=>Competitions::class,
                '0'=>'id_admission_stage',
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
}