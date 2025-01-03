<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FisServiceLogs extends Model{
	public $table = 'abt_fis_test_logs';
	public $timestamps = false;
	protected $guarded = [];
	
	static function relationship(){
		return [
		];
	}
}