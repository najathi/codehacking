<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    //return view('auth.login');
    return view('welcome');
});

//Route::auth();
Auth::routes(); // upgrade laravel V5.3

Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/home', 'HomeController@index');

/* Route::get('welcome', function () {
    return view('welcome');
}); */


Route::group(['middleware' => 'admin'], function () {

    Route::get('/admin', function () {
        //return view('layouts.admin');
        return view('admin.index');
    });

    Route::resource('/admin/users', 'AdminUsersController', ['names' => [
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
    ]]);
});
