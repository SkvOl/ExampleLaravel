<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Forms;

class FormsAnswers extends Model{
	public $table = 'lk.forms_answers';
	public $timestamps = false;
	protected $guarded = [];
	protected $dateFormat = 'U';
	protected $fillable = [
        'text',
        'is_field'
    ];
	
	static function relationship(){
		return [
			'Forms'=>[
				'class'=>Forms::class,
				'0'=>'id',
				'1'=>'id_form',
			],
		];
	}
	public function form()
    {
        return $this->belongsTo(Forms::class, 'id_form');
    }
    public function answersUsers()
    {
        return $this->hasMany(FormsUsers::class, 'id_answer');
    }
}