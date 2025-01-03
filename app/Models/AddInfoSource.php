<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\App;

class AddInfoSource extends Model{
	public $table = 'abit_add_info_source';
	public $timestamps = false;
	protected $guarded = [];
	
	static function relationship(){
		return [
			'App'=>[
                'class'=>App::class,
				'0'=>'id_source',
                '1'=>'id',
            ],
		];
	}
}