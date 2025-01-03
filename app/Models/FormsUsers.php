<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormsUsers extends Model{
	public $table = 'lk.forms_users';
	public $timestamps = false;
	protected $guarded = [];
    const CREATED_AT = 'date';
    protected $dateFormat = 'U';
	protected $fillable = [
        'id',
        'id_user',
        'answer',
        'other',
        'id_exec',
        'id_dep',
        'id_position',
        'old',
        'id_subject',
        'id_teacher',
        'course',
        'is_anon'
    ];
	
	static function relationship(){
		return [
		];
	}
	public function form()
    {
        return $this->belongsTo(Forms::class, 'id');
    }
    public function answer()
    {
        return $this->belongsTo(FormsAnswers::class, 'id');
    }
}