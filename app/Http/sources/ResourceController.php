<?php

namespace App\Http\sources;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Events\Logs\LogsEvent;
use App\Exceptions\Exceptions;
use App\Http\sources\Wrapper;
use Illuminate\Http\Request;
use App\Http\sources\Logs;
 
class ResourceController extends BaseController{
    use Wrapper;

    private $validation;
    private $system;
    private $targetController;

    public $create_log = true;
    public $change_log = true;
    public $delete_log = true;

    function getList($request){ throw new Exceptions('Method Not Allowed', 405); }
    function getOne(string $id){ throw new Exceptions('Method Not Allowed', 405); }
    function create($request){ throw new Exceptions('Method Not Allowed', 405); }
    function change($request, string $id){ throw new Exceptions('Method Not Allowed', 405); }
    function delete($request, string $id){ throw new Exceptions('Method Not Allowed', 405); }
    

    function __construct(){
        $this->validation = get_class($this);
        
        $this->targetController = explode('\\', $this->validation);
        $this->targetController = end($this->targetController);
        $this->system = str_replace('Controller', '', $this->targetController);
    }

    function index(Request $request){
        $this->validation($request, 'GetRequest');

        return self::_response($this->getList($request), 200);
    }
    
    function show(string $id){
        return self::_response($this->getOne($id), 200);
    }

    function store(Request $request){
        $this->validation($request,'CreateRequest');
        
        return self::_response($this->wrapp('create', $request), 200);
    }

    function update(Request $request, string $id){
        $this->validation($request, 'ChangeRequest');

        return self::_response($this->wrapp('change', $request, $id), 200);
    }

    function destroy(string $id){
        $request = request();
        $this->validation($request, 'DeleteRequest');

        return self::_response($this->wrapp('delete', $request, $id), 200);
    }


    private function wrapp($name, $request, $id = null){
        $response = null;

        try {
            $name_log = "{$name}_log";
           
            DB::transaction(function() use ($name, $request, $id, &$response) {
                if(isset($id)) $response = $this->$name($request, $id);
                else $response = $this->$name($request);
            });

            LogsEvent::dispatchIf($this->$name_log, new Logs("{$name}d", $request->all(), $response));

        } catch (\Throwable $th) {
            LogsEvent::dispatchIf($this->$name_log, new Logs("no {$name}d", $request->all(), $th->getMessage()));
            
            throw new Exceptions(
                message: $th->getMessage(), 
                status: $th->getCode(), 
                file: $th->getFile(), 
                line: $th->getLine()
            );
        }

        return $response;
    }


    
    private function exist($className){
        try{
            new $className;
            return $className;
        }
        catch(\Throwable $th){
            return false;
        }
    }

    private function cacheDelete($key){
        $response = DB::table('cache')->where('key', 'like', $key);
        $response->delete();
    }

    private function validation(Request $request, string $nameRequest){
        $this->validation = $this->exist(str_replace('Controllers\\'.$this->targetController, 'Requests\\'.$this->system.$nameRequest, $this->validation));
        if($this->validation !== false) app($this->validation);
        $request->request->clear(array_keys((new $this->validation)->rules()));
    }
}
