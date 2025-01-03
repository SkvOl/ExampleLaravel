<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;

class CompGroupsStatusesList extends Model{
	use SoftDeleteFlagTrait;

	public $table = 'abt_comp_groups_statuses_list';
	public $timestamps = false;
	protected $guarded = [];
	
	const DELETED_AT = 'is_deleted';
	
	static function relationship(){
		return [
		];
	}
}