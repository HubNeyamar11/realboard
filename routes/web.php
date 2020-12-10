<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//연습
Route::post('app/create_tag','App\Http\Controllers\AdminController@addTag');
Route::get('app/get_tags','App\Http\Controllers\AdminController@getTag');

Route::get('app/show_modal','App\Http\Controllers\AdminController@showModal');

Route::post('app/edit_tag','App\Http\Controllers\AdminController@editTag');
Route::post('app/delete_tag','App\Http\Controllers\AdminController@deleteTag');

//게시판 라우트
Route::post('app/create_board','App\Http\Controllers\AdminController@addBoard');
Route::get('app/get_board','App\Http\Controllers\AdminController@getBoard');
Route::post('app/edit_board','App\Http\Controllers\AdminController@editBoard');

Route::post('app/delete_board','App\Http\Controllers\AdminController@deleteBoard');

Route::post('app/upload', 'App\Http\Controllers\AdminController@upload');
Route::post('app/delete_image', 'App\Http\Controllers\AdminController@deleteImage');

//유저 라우트
Route::post('app/create_user','App\Http\Controllers\AdminController@createUser');
Route::get('app/get_users','App\Http\Controllers\AdminController@getUsers');
Route::post('app/edit_user','App\Http\Controllers\AdminController@editUser');

Route::get('/', function () {
    return view('welcome');
});

Route::any('{slug}',function(){
    return view('welcome');
});