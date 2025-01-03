<?php

use App\Http\Api\v2\system\user\Controllers\RightController;
use App\Http\Api\v2\system\user\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::api('role', RoleController::class);

Route::get('right', [RightController::class, 'right']);
