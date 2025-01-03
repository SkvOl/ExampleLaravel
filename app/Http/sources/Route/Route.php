<?php

namespace App\Http\sources\Route;

use Illuminate\Support\Facades\Route as Router;
use App\Http\sources\Controller;

class Route{

    public static function register()
    {
        if (!Router::hasMacro('api')) {
            Router::macro('api', function ($uri, $action) {
                if(is_array($action)) {
                    return Router::any($uri, $action);
                }
                elseif((new $action) instanceof Controller){
                    return Router::apiResource($uri, $action);
                }
            });
        }
    }
}