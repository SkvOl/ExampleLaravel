<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AbtNumberGenerator extends Model{
    public $table = 'abt_number_generator';
    public $timestamps = false;
    protected $guarded = []; 
    
    static function relationship(){
        return [
            
        ];
    }
}