<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\App;

class AbtFisEgeStatus extends Model{
	public $table = 'abt_fis_ege_status';
	public $timestamps = false;
	protected $guarded = [];
	
	static function relationship(){
		return [
			'App'=>[
                'class'=>App::class,
                '0'=>'id_execution',
                '1'=>'id_execution',
            ],
		];
	}

    function App(){
        $model = 'App';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }

}