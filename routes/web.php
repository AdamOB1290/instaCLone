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

Route::get('/view', function () {
    return view('welcome');
});

Route::resource('users', 'UserController');

Route::resource('chats', 'ChatController');

Route::resource('posts', 'PostController');

Route::resource('comments', 'CommentController'); #->except('create');

Route::get('comments/create/{parentId?}/{postId?}', 'CommentController@create')->name('comments.create');
Route::get('comments/{comment}/{userId?}/edit', 'CommentController@edit')->name('comments.edit');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test', 'PostController@imgTest');
