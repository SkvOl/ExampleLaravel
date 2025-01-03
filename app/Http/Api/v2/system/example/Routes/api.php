<?php
use App\Http\Api\v2\system\example\Controllers\AppController;
use Illuminate\Support\Facades\Route;
use App\Models\App;

Route::api('app', AppController::class);

Route::api('apps/{app}', [AppController::class, 'test'])->withTrashed();