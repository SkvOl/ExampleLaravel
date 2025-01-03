<?php
namespace App\Http\sources;

use Illuminate\Support\Facades\Schema;
use App\Http\sources\Mixin;

class SqlBuilder1{
    use Mixin;

    private $sqlObject = null;

    private $pathModel;
    private $mainModel;
    private $mainModelName;
    private $secondaryModels;

    private $isProtected = true;
    private static $injection = ['select', 'from', 'insert', 'update', 'delete'];
    private $sqlBlocks = ['select', 'join', 'where', 'group', 'having', 'order', 'limit'];
    private $differentVariantsJoin = ['left'=>'leftJoin', 'right'=>'rightJoin', 'cross'=>'crossJoin'];

    private static $operators = ['>', '<', '<>','!=', 'IN', 'IS', 'LIKE'];

    function __construct($mainModel, $pathModel, $secondaryModels, $select, $join, $where, $group, $having, $order, $limit) {
        $this->pathModel = $pathModel;
        $this->secondaryModels = $secondaryModels;

        $this->mainModelName = $mainModel;
        $this->mainModel = $this->pathModel.$this->mainModelName;
        $this->mainModel = new $this->mainModel;
        $join += ['mainModel'=> $mainModel];
        
        foreach($this->sqlBlocks as $sqlBlock){
            $func = $sqlBlock.'Build';
            $this->$func($$sqlBlock);
        }

        if(!$this->sqlObject) return false;
        else return $this->sqlObject;
    }

    function toSql($separator = "\n"){
        if(!$this->sqlObject) return false;
        else{
            $sql = $this->sqlObject->toSql();
            $this->sqlBlocks[1] = 'inner join';
            $sqlBlocksStr = $this->sqlBlocks;
            array_push($sqlBlocksStr, 'left join', 'right join');

            foreach($sqlBlocksStr as $sqlBlock){
                if(stripos($sql, ' '.$sqlBlock.' ') !== false && $sqlBlock != 'select') $sql = str_replace(' '.$sqlBlock.' ', $separator.$sqlBlock.' ', $sql);
            }

            return $sql;
        }
    }

    function toArray(){
        if(!$this->sqlObject) return false;
        else return $this->sqlObject->get()->toArray();
    }

    function get(){
        if(!$this->sqlObject) return false;
        else return $this->sqlObject->get();
    }

    private function selectBuild($select){
        if($select !== []){
            $explode = explode('.', $select[0]);
            $potentialModel = $this->findModel($select[0]);

            if(count($explode) > 1) $select[0] = $this->modelToTable($potentialModel).'.'.$explode[1];

            for($i = 1; $i < count($select); $i++){
                $potentialModel = $this->findModel($select[$i]);
                if($potentialModel !== false) $select[$i] = str_replace($potentialModel, $this->modelToTable($potentialModel), $select[$i]);

                $select[0] .= ', '.$select[$i];
            }
        }   
        else $select[0] = '*';

        
        foreach(self::$injection as $inj){
            $this->isProtected &= (stripos($select[0], $inj) === false);
        }

        if($this->isProtected) $this->sqlObject = $this->mainModel->selectRaw($select[0]);
    }

    private function joinBuild($join){
        if(!$this->sqlObject) return false;
        $mainModel = $join['mainModel'];

        foreach($this->secondaryModels as $key => $secondaryName){
            $explode = explode('.', $secondaryName);
            
            if(count($explode) > 1){
                array_unshift($explode, $mainModel);

                for($i = 0; $i < count($explode) - 1; $i++){
                    $flag = false;
                    for($j = 0; $j < $key; $j++){
                        if(stripos($this->secondaryModels[$j].' ', $explode[$i + 1].' ') !== false){
                            $flag = true;
                            break;
                        }
                    }
                    if($flag) continue;

                    $main = $this->pathModel.$explode[$i];
                    $main = new $main; 

                    $secondary = $this->pathModel.$explode[$i + 1];
                    $secondary = new $secondary;    
                    
                    $func = 'join';

                    
                    if(isset($join[$explode[$i + 1]]) && isset($this->differentVariantsJoin[$join[$explode[$i + 1]]])){
                        $func = $this->differentVariantsJoin[$join[$explode[$i + 1]]];
                    }

                    
                    $this->sqlObject = $this->sqlObject->$func($secondary->table, $main->table.'.'.$main::relationship()[$explode[$i + 1]][1], $secondary->table.'.'.$main::relationship()[$explode[$i + 1]][0]);    
                }
            }
            else{
                $flag = false;
                for($j = 0; $j < $key; $j++){
                    if(stripos($this->secondaryModels[$j], $secondaryName) !== false){
                        $flag = true;
                        break;
                    }
                }
                if($flag) continue;

                $secondaryModel = $this->pathModel.$secondaryName;
                $secondaryModel = new $secondaryModel;    
                
                $func = 'join';
                
                if(isset($join[$secondaryName]) && isset($this->differentVariantsJoin[$join[$secondaryName]])) $func = $this->differentVariantsJoin[$join[$secondaryName]];
                $this->sqlObject = $this->sqlObject->$func($secondaryModel->table, $this->mainModel->table.'.'.$this->mainModel::relationship()[$secondaryName][1], $secondaryModel->table.'.'.$this->mainModel::relationship()[$secondaryName][0]);
            }
        }
    }

    private function whereBuild($where){
        if(!$this->sqlObject || $where === []) return false;

        $where = self::_where($where);
        $this->sqlObject = $this->sqlObject->whereRaw($where['sqlWhere'], $where['valueArray']);
    }

    private function groupBuild($group) {
        if(!$this->sqlObject || $group === []) return false;

        foreach($group as $field){
            $explode = explode('.', $field);
            if(count($explode) > 1) $field = $this->modelToTable($explode[0]).'.'.$explode[1];

            $this->sqlObject = $this->sqlObject->groupBy($field); 
        }
    }

    private function havingBuild($having) {
        if(!$this->sqlObject || $having === []) return false;
        
        $_having = self::_where($having);
        $this->sqlObject = $this->sqlObject->whereRaw($_having['sqlWhere'], $_having['valueArray']);
    }

    private function orderBuild($order){
        if(!$this->sqlObject) return false;

        foreach($order as $field=>$value){
            $explode = explode('.', $field);
            if(count($explode) > 1) $field = $this->modelToTable($explode[0]).'.'.$explode[1];

            $this->sqlObject = $this->sqlObject->orderBy($field, $value); 
        }
    }

    private function limitBuild($limit){
        if(!$this->sqlObject || $limit == []) return false;
        

        if(is_array($limit)) $this->sqlObject = $this->sqlObject->skip(array_keys($limit)[0])->take(array_values($limit)[0]); 
        else $this->sqlObject = $this->sqlObject->take($limit);
    }

    private function _where($where){
        $sqlWhere = '';
        $valueArray = [];

        $ind = 0;
        foreach($where as $key=>$value){
            $explode = explode('.', $key);
            if(count($explode) > 1) $key = $this->modelToTable($explode[0]).'.'.$explode[1];
            else {
                $fields = Schema::getColumnListing($this->mainModel->table);
                $theoreticalReplacement = [];

                foreach($fields as $field){
                    if(stripos($key, $field) !== false){
                        array_push($theoreticalReplacement, $field);
                    }
                }
                $field = array_reduce($theoreticalReplacement, function($carry, $item) {
                    return mb_strlen($carry, 'utf-8') < mb_strlen($item, 'utf-8') ? $item : $carry;
                }, '');
                
                $key = str_replace($field, $this->mainModel->table.'.'.$field, $key);
            }

            if(stripos($key, ' OR ') === false && stripos($key, ' AND ') === false && $ind != 0) $key = 'AND '.$key;
            
            $isOperator = false;
            foreach(self::$operators as $operator){
                if(stripos($key, ' '.$operator.' ') !== false){
                    $isOperator = true; 
                }
            }
            
            if(is_array($value)){
                $param = '';
                foreach($value as $item) $param .= '?,';
                $param = substr($param, 0, strlen($param) - 1);
                
                $sqlWhere .= ' '.$key.($isOperator ? '' : ' IN ').' ('.$param.')';
            }
            else{
                if(stripos(' '.$value.' ', 'null') !== false || !isset($value)){
                    $sqlWhere .= ' '.$key.' NULL';
                    continue;
                }

                $sqlWhere .= ' '.$key.($isOperator ? '' : ' = ').' ?';
            }

            
            array_push($valueArray, $value);
            $ind += 1;
        }

        return [
            'sqlWhere'=>$sqlWhere, 
            'valueArray'=>$valueArray
        ];
    }

    private function modelToTable(string $modelName):string{
        $model = $this->pathModel.$modelName;
        $model = new $model;

        return $model->table;
    }

    private function findModel($str){
        $models = [];

        array_push($models, $this->mainModelName);
        foreach($this->secondaryModels as $currModel){
            $explode = explode('.', $currModel);
            array_push($models, end($explode));
        }
        foreach($models as $currModel) if(str_contains(' '.$str, ' '.$currModel.'.')) return $currModel;

        return false;
    }
}