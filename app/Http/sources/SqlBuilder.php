<?php
namespace App\Http\sources;

class SqlBuilder{

    private $sqlObject = null;

    private $pathModel;
    private $mainModel;
    private $secondaryModels;

    private $isProtected = true;
    private static $injection = ['select', 'from', 'insert', 'update', 'delete'];
    private static $sqlBlocks = ['select','where', 'group', 'having', 'order', 'limit'];

    private static $operators = ['>', '<', '<>','!=', 'IN', 'IS', 'LIKE'];

    function __construct($mainModel, $pathModel, $secondaryModels, $select, $where, $group, $having, $order, $limit) {
        $this->pathModel = $pathModel;
        $this->secondaryModels = $secondaryModels;

        $this->modelBuild($mainModel, $select, $where, $group, $having, $order);
        
        foreach(self::$sqlBlocks as $sqlBlock){
            $func = $sqlBlock.'Build';
            $this->$func($$sqlBlock);
        }
    }

    function toSql(){
        if(!$this->sqlObject) return false;
        else return $this->sqlObject->toSql();
    }

    function toArray(){
        if(!$this->sqlObject) return false;
        else return $this->sqlObject->get()->toArray();
    }

    function get(){
        if(!$this->sqlObject) return false;
        else return $this->sqlObject->get();
    }

    private function modelBuild($mainModel, &$select, &$where, &$group, &$having, &$order){
        $this->mainModel = $this->pathModel.$mainModel;
        $this->mainModel = new $this->mainModel;
        
        
        if($this->secondaryModels !== []){
            $selectMain = [];
            $selectOther = [];

            $whereMain = [];
            $whereOther = [];

            $groupMain = [];
            $groupOther = [];

            $havingMain = [];
            $havingOther = [];

            $orderMain = [];
            $orderOther = [];
            
            foreach($select as $key){
                if(stripos($key, '.') !== false){
                    array_push($selectOther, $key);
                }
                else{
                    array_push($selectMain, $key);
                }
            }


            foreach($where as $key=>$value){
                if(stripos($key, '.') !== false){
                    $whereOther += [$key=>$value];
                }
                else{
                    $whereMain += [$key=>$value];
                }
            }

            foreach($group as $key){
                if(stripos($key, '.') !== false){
                    array_push($groupOther, $key);
                }
                else{
                    array_push($groupMain, $key);
                }
            }

            foreach($having as $key=>$value){
                if(stripos($key, '.') !== false){
                    $havingOther += [$key=>$value];
                }
                else{
                    $havingMain += [$key=>$value];
                }
            }

            foreach($order as $key=>$value){
                if(stripos($key, '.') !== false){
                    $orderOther += [$key=>$value];
                }
                else{
                    //array_push($orderMain, [$key=>$value]);
                    $orderMain += [$key=>$value];
                }
            }
            $select = $selectMain;
            $where = $whereMain;
            $group = $groupMain;
            $having = $havingMain;
            $order = $orderMain;

            $this->secondaryBuild($selectOther, $whereOther, $groupOther, $havingOther, $orderOther);    
            $this->mainModel = $this->mainModel::with($this->secondaryModels);
        }
    }

    private function selectBuild($select){
        if($select !== []){
            for($i = 1; $i < count($select); $i++){
                $select[0] .= ', '.$select[$i];
            }
        }   
        else $select[0] = '*';

        
        foreach(self::$injection as $inj){
            $this->isProtected &= (stripos($select[0], $inj) === false);
        }

        if($this->isProtected) $this->sqlObject = $this->mainModel->selectRaw($select[0]);
    }

    private function whereBuild($where){
        if(!$this->sqlObject || $where === []) return false;
    
        $_where = self::_where($where);
        $this->sqlObject = $this->sqlObject->whereRaw($_where['sqlWhere'], $_where['valueArray']);
    }

    private function groupBuild($group) {
        if(!$this->sqlObject) return false;

        foreach($group as $field){
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

        
        // foreach($order as $value){
        //     var_dump($value);
        //     $this->sqlObject = $this->sqlObject->orderBy(array_keys($value)[0], array_values($value)[0]); 
        // }
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

    private static function _where($where){
        $sqlWhere = '';
        $valueArray = [];

        $ind = 0;
        foreach($where as $key=>$value){
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

        //echo $sqlWhere."\n";
        return [
            'sqlWhere'=>$sqlWhere, 
            'valueArray'=>$valueArray
        ];
    }

    private function secondaryBuild($select, $where, $group, $having, $order){
        $secondaryModels = [];

        foreach($this->secondaryModels as $secondaryModel){
            $models = explode('.', $secondaryModel);
            $model = $models[array_key_last($models)];
            $secondarySelect = [];
            $secondaryWhere = [];
            $secondaryGroup = [];
            $secondaryHaving = [];
            $secondaryOrder = [];

            foreach($select as $value){       
                $modelsSelect = explode('.', $value);
                $modelSelect = $modelsSelect[array_key_first($modelsSelect)];

                if($modelSelect == $model){
                    array_push($secondarySelect, str_replace($model.'.', '', $value));
                }
            }

            foreach($where as $key=>$value){
                $modelsWhere = explode('.', $key);
                $modelWhere = $modelsWhere[array_key_first($modelsWhere)];

                if(stripos(' '.$modelWhere, ' '.$model) !== false){
                    //$secondaryWhere += [explode($model.'.', $key)[1]=>$value];
                    $secondaryWhere += [str_replace(' '.$model.'.', '', ' '.$key)=>$value];
                }
            }

            foreach($group as $value){       
                $modelsGroup = explode('.', $value);
                $modelGroup = $modelsGroup[array_key_first($modelsGroup)];

                if($modelGroup == $model){
                    array_push($secondaryGroup, explode($model.'.', $value)[1]);
                }
            }

            foreach($having as $key=>$value){
                $modelsHaving = explode('.', $key);
                $modelHaving = $modelsHaving[array_key_first($modelsHaving)];

                if(stripos($modelHaving, $model) !== false){
                    $secondaryHaving += [explode($model.'.', $key)[1]=>$value];
                }
            }

            foreach($order as $key=>$value){
                $modelsOrder = explode('.', $key);
                $modelOrder = $modelsOrder[array_key_first($modelsOrder)];

                if($modelOrder == $model){
                    array_push($secondaryOrder, [explode($model.'.', $key)[1]=>$value]);
                }
            }

            if($secondarySelect !== [] || $secondaryWhere !== [] || $secondaryGroup !== [] || $secondaryHaving !== [] || $secondaryOrder !== []){
                if($secondaryWhere !== []) $_where = self::_where($secondaryWhere);
                else $_where = [];

                

                if($secondaryHaving !== []) $_having = self::_where($secondaryHaving);
                else $_having = [];

                $secondaryModels += [
                    $secondaryModel=>function($query) use ($secondarySelect, $_where, $secondaryGroup, $_having, $secondaryOrder){    
                        if($secondarySelect !== []) { 
                            foreach($secondarySelect as $value){
                                $query->selectRaw($value);
                            }
                        }

                        if($_where !== []) $query->whereRaw($_where['sqlWhere'], $_where['valueArray']);
                        if($secondaryGroup !== []) {
                            $query->groupBy($secondaryGroup);

                            if($_having !== []) $query->havingRaw($_having['sqlWhere'], $_having['valueArray']);
                        }
                        if($secondaryOrder !== []) { 
                            foreach($secondaryOrder as $value){
                                $query->orderBy(array_keys($value)[0],array_values($value)[0]);
                            }
                        }
                }];
            }
            else array_push($secondaryModels, $secondaryModel);
        }

        $this->secondaryModels = $secondaryModels;
    }

    private function modelToTable(string $modelName):string{
        $model = $this->pathModel.$modelName;
        $model = new $model;

        return $model->table;
    }
}