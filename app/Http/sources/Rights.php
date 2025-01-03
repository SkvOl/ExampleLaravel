<?php
namespace App\Http\sources;
use App\Http\sources\Mixin;

trait Rights{
    use Mixin;

    static function whiteList($list, $data, $idDep = NULL){


        if(session('right') != 'adm' && $list != []){      
            foreach ($data as &$value){
                $right = session('right');

                $idUser = self::searchKey('id_user', $value);
                if(isset($idUser['id_user']) && session('id_user') == $idUser['id_user']) $right = 'superuser';

                if(isset($idDep)){
                    $idDepartment = array_unique(self::сollecting('id_department', $value));
                    if(isset($idDepartment) && !in_array($idDep, $idDepartment)) $value = [];
                } 

                self::clearKey(['all'], $list[$right], $value);
            }
        }

        self::clear([[]], [], $data);
        return $data;
    }

    static function blackList($list, $data, $idDep = NULL){
        if(session('right') != 'adm'){
            foreach ($data as &$value){
                $right = session('right');

                $idUser = self::searchKey('id_user', $value);
                if(isset($idUser['id_user']) && session('id_user') == $idUser['id_user']) $right = 'superuser';

                if(isset($idDep)){
                    $idDepartment = array_unique(self::сollecting('id_department', $value));
                    if(isset($idDepartment) && !in_array($idDep, $idDepartment)) $value = [];
                } 

                self::clearKey($list[$right], [], $value);
            }
        }
 
        self::clear([[]], [], $data);
        return $data;
    }

   static function stableFields($list, $data, $idDep = NULL){
        if(session('right') != 'adm' && $list != []){
            $right = session('right');

            foreach ($data as $key=>$value){
                if(in_array($key, ['_where', '_having', '_order'])){
                    foreach ($list[$right][$key] as $itemKey=>$item){
                        $data = self::searchKey($itemKey, $value);

                        if($data == false || $data[$itemKey] != $item) return true;
                    }
                }
                elseif(in_array($key, ['_select', '_group'])){
                    if($list[$right][$key] != $value) return true;
                } 
            }
        }
 
        return false;
    }
}