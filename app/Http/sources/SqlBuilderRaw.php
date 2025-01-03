<?php
namespace App\Http\sources;

use App\Http\sources\Mixin;
use Illuminate\Support\Facades\DB;

class SqlBuilderRaw{
    private $isProtected = true;
    private $sqlObject = null;    
    private $sql;

    
    private $sqlError = ['insert', 'update', 'delete', 'drop', 'truncate'];

    function __construct($sql) {
        $this->sql = $sql;

        $this->isProt();
        $this->main();

        if(!$this->sqlObject) return false;
        else return $this->sqlObject;
    }

    private function isProt(){
        $this->isProtected = true;
        
        foreach($this->sqlError as $error){
            if(stripos($this->sql, ' '.$error.' ') !== false) {
                $this->isProtected  = false;
                return;
            }
        }
    }

    private function main(){
        if($this->isProtected) $this->sqlObject = DB::select($this->sql);
        else $this->sqlObject = false;
    }

    function toSql(){
        if(!$this->sqlObject) return false;
        else return $this->sql;  
    }

    function toArray(){
        if(!$this->sqlObject) return false;
        else return $this->sqlObject->get()->toArray();
    }

    function get(){
        if(!$this->sqlObject) return false;
        else return $this->sqlObject;
    }
}