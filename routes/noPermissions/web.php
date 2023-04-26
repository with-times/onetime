<?php

use App\Http\Controllers\Web\WebController;
use Illuminate\Support\Facades\Route;

Route::get('/web', [WebController::class, 'getAll']);
Route::get('/web/{num}', [WebController::class, 'getWebByNum']);
Route::get('/feeds', [WebController::class, 'getAllFeed']);
Route::post('/search', [WebController::class, 'search']);
