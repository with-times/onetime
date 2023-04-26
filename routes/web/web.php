<?php

use App\Http\Controllers\Web\WebController;
use Illuminate\Support\Facades\Route;

Route::post('/create', [WebController::class, 'create']);
Route::put('/update/{id}', [WebController::class, 'update']);
Route::delete('/delete/{id}', [WebController::class, 'delete']);
Route::post('/info', [WebController::class, 'getWebInfo']);
Route::get('/deeds', [WebController::class, 'getDeeds']);

