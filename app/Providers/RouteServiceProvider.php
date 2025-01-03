<?php

namespace App\Providers;


use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Exceptions\NotFound;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {

        $this->routes(function (Request $request) {     
            $url = $request->url();
            
            $urlA = explode('pnzgu.ru/', $url);
            if(count($urlA) < 4) $urlA += ['', '', '', ''];

            $urlA = explode('/', $urlA[1]);
            
            if(count($urlA) < 4) $urlA += ['', '', '', ''];

            throw_if(!in_array($urlA[0], ['test', 'prod', 'mdl', 'pulse', 'livewire', 'api', 'docs', 'telescope', '']), new NotFound);

            
            $path = base_path("app/Http/Api/".$urlA[2]."/system/".$urlA[3]."/Routes/api.php");
            
            if(!file_exists($path)) return [];

            $routeMiddleware = Route::middleware('location.exceptions', 'throttle.personal:'.env('REQUEST_LIMIT').',1', 'api');
        
            return [
                $routeMiddleware->prefix($urlA[0].'/api')->group(base_path('routes/api.php')),
                $routeMiddleware->prefix($urlA[0].'/api/'.$urlA[2].'/'.$urlA[3].'/')->group($path)
            ];
        });
    }
}
