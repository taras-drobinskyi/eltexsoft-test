<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/users','ApiController@getAll');
Route::get('/users/{id}','ApiController@getById');
Route::post('/users','ApiController@store');
Route::patch('/users/{id}','ApiController@update');
Route::delete('/users/{id}','ApiController@delete');

