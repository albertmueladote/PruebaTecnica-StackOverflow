<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\ApiController::class, 'form']);
Route::post('/', [App\Http\Controllers\ApiController::class, 'handleRequest']);
Route::get('/searches/{id}/questions', [App\Http\Controllers\ApiController::class, 'getQuestionsBySearch']);