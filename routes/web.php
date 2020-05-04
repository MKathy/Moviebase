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
Route::view('/', 'welcome');

Route::get('movies', 'MoviesController@index');
Route::get('movies/{filterBy}', 'MoviesController@filterBy');
Route::post('movies', 'MoviesController@store');
Route::get('comments', 'CommentsController@index');
Route::post('comments', 'CommentsController@store');
Route::get('top', 'TopMoviesController@index');