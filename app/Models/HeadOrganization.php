<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeadOrganization extends Model{
	public $table = 'head_organization';
	public $timestamps = false;
	protected $guarded = [];
	
	static function relationship(){
		return [
			'Direction'=>[
                'class'=>Direction::class,
                '1'=>'head_id',
                '0'=>'id',
            ],
		];
	}

	function Direction(){
        $model = 'Direction';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}