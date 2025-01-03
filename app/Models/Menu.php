<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'lk.menu';
	public $timestamps = false;
	protected $guarded = [];

	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'Menu'=>[
                'class'=>Menu::class,
                '1'=>'id',
                '0'=>'id_parent',
            ],
		];
	}

	function Menu(){
        $model = 'Menu';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}