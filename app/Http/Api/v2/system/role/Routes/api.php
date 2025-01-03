<?php
use App\Http\Api\v2\system\role\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('', [RoleController::class, 'role']);
