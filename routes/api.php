<?php

use Illuminate\Http\Request;

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

Route::group([
    'middleware' => ['api', 'cors'],
    'prefix' => 'auth'
], function ($router) {
    Route::get('/', function (Request $request) {
        return response()->json(['status' => 'running']);
    });
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me')->middleware('jwt.verify');
});

Route::middleware(['jwt.verify', 'cors'])->group(function () {
    // Projects Endpoint
    Route::get('warungs/filter', 'WarungController@filter');
    Route::get('warungs/count', 'WarungController@count');
    Route::resource('warungs', 'WarungController');
    // Posts Endpoint
    Route::get('foods/filter', 'FoodController@filter');
    Route::get('foods/count', 'FoodController@count');
    Route::resource('foods', 'FoodController');
    // Comments Endpoint
    Route::resource('comments', 'CommentController');
    Route::resource('photos', 'PhotoController');
});