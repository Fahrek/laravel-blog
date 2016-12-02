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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

/**
 * Users routes
 */
Route::get('/users', 'UserController@index');
Route::get('/users/{user}', 'UserController@show');
Route::post('/users', 'UserController@store')->middleware(['auth:api']);
Route::put('/users/{user}', 'UserController@update')->middleware(['auth:api']);
Route::delete('/users/{user}', 'UserController@destroy')->middleware(['auth:api']);

/**
 * Types routes
 */
Route::get('/types', 'TypeController@index');
Route::get('/types/{type}', 'TypeController@show');
Route::post('/types', 'TypeController@store')->middleware(['auth:api']);
Route::put('/types/{type}', 'TypeController@update')->middleware(['auth:api']);
Route::delete('/types/{type}', 'TypeController@destroy')->middleware(['auth:api']);

/**
 * Posts routes
 */
Route::get('/posts', 'PostController@index');
Route::get('/posts/{post}', 'PostController@show');
Route::post('/posts', 'PostController@store')->middleware(['auth:api']);
Route::put('/posts/{post}', 'PostController@update')->middleware(['auth:api']);
Route::delete('/posts/{post}', 'PostController@destroy')->middleware(['auth:api']);
