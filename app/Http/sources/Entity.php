<?php
namespace App\Http\sources;

ini_set('memory_limit', '1000000000M');
set_time_limit(36000000);

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Http\sources\SqlBuilderRaw;
use App\Http\sources\SqlBuilder1;
use App\Http\sources\SqlBuilder;
use App\Events\Logs\LogsEvent;
use App\Http\sources\Rights;
use App\Http\sources\Mixin;
use App\Http\sources\Logs;
//use function Ramsey\Uuid\v1;

class Entity{
    use Mixin;
    use Rights;

    static protected $models;   
    static protected $isLogs = true;
    static protected $isClear = true;
    static protected $pathModel = "App\\Models\\";
    static protected $pathResource = 'App\\Http\\Api\\v1\\system\\';
    static protected $nameResource = null;
    static protected $minFields = 2;
    static protected $readOnly = false;
    static protected $isExist = false;
    static protected $exist = [];
    static protected $existExceptions = [];
    static private $headers = [
        'Access-Control-Allow-Origin'=>'*',
        'Access-Control-Allow-Headers'=>'*',
    ];


    static function get($request, $list = [], $idDep = 0, $func = 'whiteList', $type = 'SqlBuilder'){
        

        $itemsSql = ['_select', '_join','_where', '_group', '_having', '_order', '_limit'];
        $customFlags = false;
        foreach($itemsSql as $itemSql) $customFlags = $customFlags || isset($request[$itemSql]);
        
        if(stripos($type,'raw') !== false && !$customFlags) {


            $stable = self::stableFields($list, $request, $idDep);
            if($stable) return response([])->withHeaders(self::$headers);

            $resArr = self::select(select: $request['_sql'], type: 'raw');
            if(self::$isClear) self::clear(['', ' '], [], $resArr);
        }
        elseif($customFlags){        
            foreach($itemsSql as $itemSql){
                if(isset($request[$itemSql])){
                    if(!is_array($request[$itemSql])) $request[$itemSql] = json_decode($request[$itemSql], true);
                }
                else $request[$itemSql] = [];
            }

            $stable = self::stableFields($list, $request, $idDep);
            if($stable) return response([])->withHeaders(self::$headers);

            $resArr = self::select($request['_select'], $request['_join'], $request['_where'], $request['_group'], $request['_having'], $request['_order'], $request['_limit'], $type);
            if(self::$isClear) self::clear(['', ' '], [], $resArr);
        }
        else{
            if(!isset(self::$nameResource)) self::$nameResource = self::$models[0].'Resource';
            else self::$nameResource .= 'Resource';

            $model = self::$pathModel.self::$models[0];
            $resources = self::$pathResource.explode('/', $_SERVER['REQUEST_URI'])[4].'\\Resources\\'.self::$nameResource;
            
            $modelList = array_slice(self::$models, 1);

            if($modelList !== []){
                $model = $model::with($modelList);
            }
            
            if(!is_array($request)){
                if(self::resourcesExists($resources)){
                    if($modelList !== []) $resObj = new $resources($model->find($request));
                    else $resObj = new $resources($model::find($request));
                }
                else{
                    if($modelList !== []) $resObj = $model->find($request);
                    else $resObj = $model::find($request);
                }                    

                $resArr = json_decode(json_encode($resObj, JSON_UNESCAPED_UNICODE), true);
                
                //Очистка и отправка:
                if(self::$isClear) self::clear(['', ' '], [], $resArr);

                $resArr = ['0' => $resArr];
            }
            else{
                if(self::resourcesExists($resources)){
                    if($modelList !== []) $resObj = $resources::collection($model->get());
                    else $resObj = $resources::collection($model::get());
                }
                else{
                    if($modelList !== []) $resObj = $model->get();
                    else $resObj = $model::get();
                }
                $resArr = json_decode(json_encode($resObj, JSON_UNESCAPED_UNICODE), true); 
                
                //Очистка и отправка:
                if(self::$isClear) self::clear(['', ' '], [], $resArr);
            }
        }
        
        if($list!= []) $resArr = self::$func($list, $resArr, $idDep);

        return response($resArr)->withHeaders(self::$headers);
    } 

    static function set($request){
        if(self::$readOnly) return 'Read only';

        $searchId = self::$models;

        array_walk($searchId, function (&$value, $key) {
            $explode = explode('.', $value);
            $value = end($explode);

            $value='id_'.$value;
        });
        
        $request = self::toArray($request);
        $isfound = self::searchKey($searchId, $request);


        if($isfound !== false){
            $resArr = self::update($request);
        }
        else{
            $resArr = self::insert($request);
        }

        LogsEvent::dispatchIf(self::$isLogs, new Logs($resArr['status'], $request, $resArr['body']));

        return response($resArr)->withHeaders(self::$headers);
    }

    private static function select($select = [], $join = [], $where = [], $group = [], $having = [], $order = [], $limit = [], $type = 'SqlBuilder'){
        if(!isset(self::$nameResource)) self::$nameResource .= self::$models[0].'Resource';
        else self::$nameResource .= 'Resource';

        $resources = self::$pathResource.explode('/', $_SERVER['REQUEST_URI'])[4].'\\Resources\\'.self::$nameResource;
        

        if($type == 'raw') $sqlBuilder = new SqlBuilderRaw($select);
        elseif($type == 'SqlBuilder') $sqlBuilder = new SqlBuilder(self::$models[0], self::$pathModel, array_slice(self::$models, 1), $select, $where, $group, $having, $order, $limit);
        else $sqlBuilder = new SqlBuilder1(self::$models[0], self::$pathModel, array_slice(self::$models, 1), $select, $join, $where, $group, $having, $order, $limit);

        
        $resObj = $sqlBuilder->get();
        // echo $sqlBuilder->toSql()."\n\n";
        // self::var_dump_f($sqlBuilder->toSql());
        
        if(self::resourcesExists($resources) AND $resObj !== false){    
            $resObj = $resources::collection($resObj);
        }
        
        if($resObj === false) $resObj = [];
        
        if(!is_array($resObj)) {
            $resArr = json_decode($resObj->toJson(), true);
        }
        else{
            $resArr = $resObj;
        }

        if($resArr == []){
            return false;
        }
        else{
            return $resArr;
        }  
        
    }

    private static function resourcesExists($resources){
        return file_exists(str_replace(['\\', 'api', 'App/'],['/', 'html/api', 'app/'],'\\var\\www\\api\\'.$resources).'.php');
    }

    

    private static function insert($array){
        $saveId = ['status'=>'inserted', 'body'=>[]];
        if(isset($array['_custom'])){
            self::$models = $array['_custom'];
            unset($array['_custom']);
        }
        
        DB::transaction(function() use (&$array, &$saveId) {
            foreach(self::$models as $keys=>$item){
                $explode = explode('.', $item);
                $item = end($explode);
                if(count($explode) > 1) $previous = $explode[count($explode) - 2];
                else $previous = self::$models[0];
                
                $model = self::$pathModel.$item;
                $model = new $model;
                
                $fields = Schema::getColumnListing($model->table);
                foreach($array as $key=>$value){
                    if(in_array($key, $fields)){
                        $model->$key = $value;
                    }
                    else{  
                        if(is_array($value) && $item == $key){
                            foreach($value as &$tmp){
                                if($previous != $item && isset($array[$model::relationship()[$previous][1]])){
                                    $tmp += [$model::relationship()[$previous][1]=>$array[$model::relationship()[$previous][1]]];   
                                } 

                                foreach($array as $dopKey=>$dopValue){
                                    if(!is_array($dopValue)){
                                        $tmp += [$dopKey=>$dopValue];
                                    }
                                }
                            }

                            $saveId['body'] += ['id_'.$item.'_dop'=>self::_insert(self::$pathModel.$item, $fields, $value)];
                        }
                    }
                }

                if(self::countFields($model) >= self::$minFields && !isset($array[$item])){
                    if(self::$isExist) $model = self::exist($model, $item);
                    else $model->save();
                    $saveId['body'] += ['id_'.$item=>$model->id];
                }
                //self::var_dump_f($saveId);


                $flkag = 0;
                for($i = $keys + 1; $i < count(self::$models); $i++){
                    $explode = explode('.', self::$models[$i]);
                    $currentModel = end($explode);
                    
                    if(isset($model::relationship()[$currentModel])){
                        $relationshipValue = $model::relationship()[$currentModel][1];
                        
                        if(isset($array[$currentModel]) && count($array[$item]) > 1){
                            if(isset($model->$relationshipValue)){
                                $flkag = 1;

                                foreach($array[$currentModel] as $keyForId=>&$valueForId){
                                    $valueForId = [$model::relationship()[$currentModel][0]=>$model->$relationshipValue] + $valueForId;
                                }
                            }
                            else{
                                $flkag = 2;
                                
                                foreach($array[$currentModel] as $keyForId=>&$valueForId){
                                    $id = (isset($saveId['body']['id_'.$item.'_dop'][$keyForId]) ? $saveId['body']['id_'.$item.'_dop'][$keyForId] : 0);
                                    $valueForId = [$model::relationship()[$currentModel][0]=>$id] + $valueForId;
                                }
                            }
                        }
                        else{
                            if(isset($model->$relationshipValue)){
                                $flkag = 3;
                                $array = [$model::relationship()[$currentModel][0]=>$model->$relationshipValue] + $array;
                            }
                            else{
                                $flkag = 4;
                                $first = current($saveId['body']['id_'.$item.'_dop']);
                                $id = ($first !== false ? $first : 0);
                                $array = [$model::relationship()[$currentModel][0]=>$id] + $array;
                            }
                        }

                        
                    }
                }
                // self::var_dump_f($item.' '.$flkag);
                // if($flkag == 2) self::var_dump_f($saveId);
                // self::var_dump_f( $array );
            }     
        }, 2); 

        if($saveId['body'] == []) $saveId['status'] = 'inserted error';

        return $saveId;
    }

    private static function _insert($model, $fields, $array){
        $saveId = [];
        $nameModel = $model;

        foreach($array as $key1=>$item){     
            if(is_array($item)){
                
                $model = new $model;   
                foreach($item as $key=>$value){
                    if(in_array($key, $fields)){
                        $model->$key = $value;
                    }
                }  

                if(self::countFields($model) >= self::$minFields){
                    if(self::$isExist) $model = self::exist($model, $nameModel);
                    else $model->save();
                }

                $saveId += [$key1=>$model->id];
            }   
        }

        return $saveId;
    }   

    private static function countFields($model){
        $fields = Schema::getColumnListing($model->table);
        $count = 0;

        foreach($fields as $field){
            if(isset($model->$field)){
                $count++;
            }
        }

        return $count;
    }



    private static function update($array){
        $saveId = ['status'=>'updated', 'body'=>[]];
        
        DB::transaction(function() use (&$array, &$saveId) {
            foreach(self::$models as $keys=>$item){
                $explode = explode('.', $item);
                $item = end($explode);

                $model = self::$pathModel.$item;
                $id = 'id_'.$item;
                //self::var_dump_f($item);
                if(self::searchKey($id, $array) !== false){
                    if(isset($array[$id])){
                        
                        $model = $model::findOrFail($array[$id]);
                        $modelOld = json_encode($model, JSON_UNESCAPED_UNICODE);
                    }
                    else{
                        $model = new $model;
                    }
                    $fields = Schema::getColumnListing($model->table);
                    

                    foreach($array as $key=>$value){
                        if(in_array($key, $fields)){
                            if(in_array($value, ['null', 'NULL'])) $model->$key = NULL;
                            else $model->$key = $value;
                        }
                        else{                            
                            if(is_array($value) && $item == $key){
                                $saveId['body'] += [$item.'_dop'=>self::_update(self::$pathModel.$item, $id, $fields, $value)];
                            }
                        }
                    }

                    if(isset($array[$id])){
                        $model->save();
                        $saveId['body'] += [
                            $item=>[
                                'old'=>$modelOld,
                            ],
                        ];
                    }
                }
            }     
        }, 2); 

        return $saveId;
    }

    private static function _update($model, $id, $fields, $array){
        $saveId = [];

        foreach($array as $item){       
            if(is_array($item) && self::searchKey($id, $item) !== false){
                
                $model = $model::findOrFail($item[$id]);
                $modelOld = json_encode($model, JSON_UNESCAPED_UNICODE);
                foreach($item as $key=>$value){
                    if(in_array($key, $fields)){
                        //$model->$key = $value;
                        if(in_array($value, ['null', 'NULL'])) $model->$key = NULL;
                        else $model->$key = $value;
                    }
                }  
                
                $model->save();

                array_push($saveId, [
                    'old'=>$modelOld,
                ]);
            }   
        }

        return $saveId;
    }

    private static function toArray($data){
        foreach($data as $key=>&$value){
            if(!is_array($value) && self::in_array_like($key, self::$models)) {
                $value = json_decode($value, true);
            }
        }
        return $data;
    }

    private static function exist($model, $nameModel){
        $nameModel = explode('\\', $nameModel);
        $nameModel = end($nameModel);
        if(self::$existExceptions == []){
            $exist = [];
            $except = $model->toArray();
            unset($except['id']);
        }
        else{
            $exist = $model->toArray();
            $except = [];
            unset($exist['id']);
        }     
        

        foreach(self::$existExceptions as $exception){
            if($exception == $nameModel){
                $model->save();
                return $model;
            }
            if(!str_contains($exception, $nameModel) AND str_contains($exception, '.')){ // В случае, если указан столбец без модели
                continue;
            }
            
            $field = str_replace($nameModel.'.', '', $exception);
            if(isset($exist[$field])){            
                $except += [$field=>$exist[$field]];
                unset($exist[$field]);
            }
        }

        foreach(self::$exist as $existItem){
            if($existItem == $nameModel){
                return $model->firstOrCreate($model->toArray());
            }
            if(!str_contains($exception, $nameModel) AND str_contains($exception, '.')){ // В случае, если указан столбец без модели
                continue;
            }
            
            $field = str_replace($nameModel.'.', '', $existItem);
            if(isset($except[$field])){            
                $exist += [$field=>$except[$field]];
                unset($except[$field]);
            }
            else if($except == []){
                $except = $exist;
                $exist = [$field=>$except[$field]];
                unset($except[$field]);
            }
        }
        
        return $model->firstOrCreate($exist, $except);
    }
}