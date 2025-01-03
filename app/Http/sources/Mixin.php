<?php
namespace App\Http\sources;

/**
 * Эта черта содержит функции не подходящие по логике в другие классы/черты
 * @author SkvOl
 */
trait Mixin{

    /**
     * Функция ищущая хотябы один ключ из массива keys 
     * в многомерном массиве фккфн, в случае нахождения возвращает пару 
     * ключ <-> значение. Если ключи не найдены возвращает false 
     * @param array $keys массив искомых ключей [key1,key2,...]
     * @param array $array любой массив
     * @return array|false [key=>value]|false
     * @static
     */
    protected static function searchKey($keys, $array){
        if(!is_array($keys)){
            $keys = [$keys];
        }

        foreach($array as $key => $curr_value){
            if(is_numeric($key)){ 
                if(is_array($curr_value)){
                    $value = self::searchKey($keys, $curr_value);
                    if($value !== false) return $value;
                }

                continue;
            }
            

            if(in_array($key, $keys)) return [$key=>$curr_value];  
            if(is_array($curr_value)){
                $value = self::searchKey($keys, $curr_value);
                if($value !== false) return $value;
            }
        }

        return false;
    }

    /**
     * Функция заменяющая в массиве $array элемент с ключём key($replacements) 
     * на значение $replacements[key($replacements)]
     * @param array $replacements массив [key=>value] на который будет произведена замена
     * @param array $array массив в котором будет произведена замена
     * @static
     */
    protected static function replacementByKey($replacements, &$array){
        $key = array_keys($replacements)[0];

        foreach($array as $currKey=>&$value){

            if($key == $currKey) {

                $value = $replacements[$key];
            }
            elseif(is_array($value)){
                self::replacementByKey($replacements, $value);
            }
        }
    }
    
    /**
     * Заменяет ключи, содержащиеся в массиве $replacements на ключ $key
     * в массиве $array
     * @param array $replacements массив [key1, key2, key3, ...] названий ключей, которые будут заменены
     * @param string $key значение, на которое будут заменены ключи из массива $replacements
     * @param array $array массив в котором будет произведена замен
     * @static
     */
    protected static function replacementKey($replacements, $key, &$array){
        foreach($array as $currKey=>&$value){

            if(in_array($currKey, $replacements)) {
                $array += [$key=>$array[$currKey]];
                unset($array[$currKey]);
            }
            elseif(is_array($value) && $value != []){
                self::replacementKey($replacements, $key, $value);
            }
        }
    }


    /**
     * Функция возвращающая поля из массива select
     * для условий из массива where
     * для экземпляра модели model, или false, если ответ пустой
     * @param model $model экземпляр модели
     * @param array $select поля для вывода ['id', 'is_deleted', ...]
     * @param array $where условия ['id'=>1, 'is_deleted'=>0, ...]
     * @return array|false [[0]=>['id'=>value, 'is_deleted'=>value, ...],[1]=>['id'=>value, 'is_deleted'=>value, ...], ...]|false
     * @static
     */
    protected static function get($model, $select, $where){
        if($select != [] && $select !== null){

            for($i = 1; $i < count($select); $i++){
                $select[0] .= ', '.$select[$i];
            }
        }   
        else $select[0] = '*';

        $res = $model->selectRaw($select[0])->
        where(function($query) use ($where){
            foreach($where as $field=>$condition){
                $query->where($field, '=', $condition);
            }
        })->orderBy('id', 'desc')->get()->toArray();

        if($res == []){
            return false;
        }
        else{
            return $res;
        }  
    }

    /**
     * Функция очищающая(unset([key=>value])) массив $array от значений из массива $value
     * за исключением ключей из массива $exceptions
     * @param array $values массив значений которые надо очистить
     * @param array $exceptions массив ключей которые не надо очищать
     * @param array $array очищаемый массив
     * @static
     */
    protected static function clear($values, $exceptions, &$array){ 
        if($array === false || $array === null || $array === []){
            $array = [];
            return;
        }

        foreach($array as $key=>&$arr){
            if(!in_array($key, $exceptions)){
                if(is_array($arr) && $arr != []){
                    self::clear($values, $exceptions, $arr);
                }
                else{
                    if(in_array($arr, $values)) unset($array[$key]);
                }
            }
        }
    }


    /**
     * Функция очищающая(unset([key=>value])) массив $array от ключей из массива $keys
     * за исключением ключей из массива $exceptions
     * @param array $keys массив ключей которые надо очистить
     * @param array $exceptions массив ключей которые не надо очищать
     * @param array $array очищаемый массив
     * @static
     */
    protected static function clearKey($keys, $exceptions, &$array){ 
        if($array === false || $array === null || $array === []){
            $array = [];
            return;
        }

        foreach($array as $key=>&$arr){
            if(!in_array($key, $exceptions)){
                if(/*!in_array($key, $keys) &&*/ !self::in_array_like($key, $keys) && is_array($arr) && count($arr) > 1 && $arr[array_key_first($arr)] != []){
                    self::clearKey($keys, $exceptions, $arr);
                }
                else{
                    if(in_array('all', $keys) || in_array($key, $keys)) {
                        unset($array[$key]);
                    }
                }
            }
        }
    }

    protected static function сollecting($keys, $array, &$res = []){
        if(!is_array($keys)) $keys = [$keys];

        foreach($array as $currKey=>$value){
            
            if(in_array($currKey, $keys)) {
                array_push($res, $value);
            }
            elseif(is_array($value)){
                self::сollecting($keys, $value, $res);
            }
        }

        return $res;
    }

    protected static function in_array_like($item, $array){
        // foreach($array as $value) if(stripos($value, $item) !== false) return true;
        foreach($array as $value) if(str_contains($value, $item)) return true;

        return false;
    }

    protected static function array_in_like($item, $array){
        // foreach($array as $value) if(stripos($item, $value) !== false) return true;
        foreach($array as $value) if(str_contains($item, $value)) return true;

        return false;
    }




    /**
     * Функция выводящая отформатированный массив array на экран
     * @param array $array массив значений которые надо очистить
     * @static
     */
    protected static function printf($array){
        echo "<pre>";
        var_dump($array);
        echo "</pre>";
    }

    /**
     * Функция удаляющая дирректорию и всё находящееся в ней
     * @param string $path путь до дирректории + её название
     * @static
     */
    protected static function deldir($path){
        if(is_dir($path) === true){
            $files = array_diff(scandir($path), array('.', '..'));

            foreach ($files as $file){
                self::deldir(realpath($path) . '/' . $file);
            }

            return rmdir($path);
        }
        elseif(is_file($path) === true){
            return unlink($path);
        }

        return false;
    }

    /**
     * Функция создающая файл и устанавливающая права на этот файл
     * @param string $name путь до файла + его название
     * @param string $text содержимое файла
     * @static
     */
    protected static function inFile($name, $text, $flag = FILE_APPEND){
        file_put_contents($name, $text,  $flag);           
        chmod($name, 0664);
    }

    protected static function fromFile($name){
        return file_get_contents($name);
    }

    protected static function var_dump_f($var, $fileName = 'var_dump_f.txt', $flag = FILE_APPEND){
        self::inFile($fileName, var_export($var, true)."\r\n", $flag);
    }
}