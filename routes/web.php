<?php

use App\Post;
use Illuminate\Support\Facades\Auth;
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
Route::get('/contacts', 'ContactController@get');
Route::get('/conversation/{id}', 'ContactController@getMessagesFor');
Route::post('/conversation/send', 'ContactController@send');

Route::resource('posts', 'PostController'); #->except('create');
Route::get('post/comments/{postId}','PostController@show')->name('posts.show');
Route::get('posts/create/{type}', 'PostController@create')->name('posts.create');


Route::resource('comments', 'CommentController'); #->except('create');
// Route::get('post/comments/store/{parentId?}/{postId?}', 'CommentController@store')->name('comments.store');
Route::post('post/comments', 'CommentController@store')->name('comments.store');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('posts/{post}/{user}/like', 'PostController@like')->name('posts.like');
Route::get('posts/{post}/{user}/unlike', 'PostController@unlike')->name('posts.unlike');

Route::get('posts/{post}/{user}/favorite', 'PostController@favorite')->name('posts.favorite');
Route::get('posts/{post}/{user}/unfavorite', 'PostController@unfavorite')->name('posts.unfavorite');

Route::get('post/comments/{comment}/{user}/like', 'CommentController@like')->name('comments.like');
Route::get('post/comments/{comment}/{user}/unlike', 'CommentController@unlike')->name('comments.unlike');

Route::get('users/{user}/{sessionUser}/follow', 'UserController@follow')->name('users.follow');
Route::get('users/{user}/{sessionUser}/unfollow', 'UserController@unfollow')->name('users.unfollow');

Route::get('users/{user}/{sessionUser}/{index}/notification_preference', 'UserController@notification_preference')->name('users.notification_preference');

Route::get('feeds', function () {
    return view('feed');
})->middleware('auth');

Route::get('story/{userId}', function () {
    return view('stories');
})->middleware('auth');

Route::get('post/{postId}', function () {
    return view('comments');
})->middleware('auth');

Route::get('chat', function () {
    return view('chats');
})->middleware('auth');

Route::get('profile/{userId}', function () {
    return view('profile');
})->middleware('auth');

Route::get('profile/edit', function () {
    return view('editProfile');
})->middleware('auth');



Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
