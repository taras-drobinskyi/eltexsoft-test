<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/register', function (){
    redirect('/login');
})->name('redirect')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/user-management', 'UserController@index')->name('user-management')->middleware('auth');
    Route::get('/users', 'UserController@data')->name('users')->middleware('auth');
    Route::post('/users', 'UserController@store')->name('store')->middleware('auth');
    Route::put('/users/{id}', 'UserController@update')->name('update-user')->middleware('auth');
    Route::get('/users/{id}', 'UserController@edit')->name('edit-form')->middleware('auth');
    Route::delete('/users/{id}', 'UserController@destroy')->name('delete-user')->middleware('auth');
    Route::get('/user/{id}', 'UserController@show')->name('view-form')->middleware('auth');
    Route::get('/new/user', 'UserController@create')->name('new-form')->middleware('auth');

});
