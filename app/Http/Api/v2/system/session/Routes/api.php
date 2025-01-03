<?php

use App\Http\Api\v2\system\session\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::post('authentication', [SessionController::class, 'authentication']);

Route::patch('change', [SessionController::class, 'edit']);

Route::post('logout', [SessionController::class, 'logout']);