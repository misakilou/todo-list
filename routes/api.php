<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});




Route::middleware(['auth:api'])->group(function () {
    Route::get('tasks', 'TaskController@index');
    Route::post('tasks', 'TaskController@store');
    Route::patch('tasks/markasdone/{id}', 'TaskController@markAsDone');
    Route::patch('tasks/{id}', 'TaskController@update');
    Route::delete('tasks/{id}', 'TaskController@destroy');
});
