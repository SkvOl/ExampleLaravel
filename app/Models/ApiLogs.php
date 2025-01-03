<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLogs extends Model{
    public $table = 'api.logs';
    public $timestamps = false;
    protected $guarded = [];
}