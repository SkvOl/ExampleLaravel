<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\AbtTermsAdmission;

class AbtAdmissionEvents extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'abt_admission_events';
	public $timestamps = false;
	protected $guarded = [];
	
	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
			'AbtTermsAdmission'=>[
                'class'=>AbtTermsAdmission::class,
                '0'=>'id',
				'1'=>'id_event',
            ],
		];
	}

	function AbtTermsAdmission(){
        $model = 'AbtTermsAdmission';
        return $this->hasMany(self::relationship()[$model]['class'], self::relationship()[$model]['0'], self::relationship()[$model]['1']);
    }
}