<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Pages extends Model{
	public $table = 'lk.pages';
	public $timestamps = false;
	protected $guarded = [];
	
	static function relationship(){
		return [	
		];
	}


}