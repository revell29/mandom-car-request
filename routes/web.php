<?php

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

Route::get('/', function () {
    return redirect('/backend/login');
});


Route::group(['prefix' => 'backend'], function () {
    Auth::routes();
    Route::get('/delete', 'Auth\LoginController@logout')->name('logout');
    Route::get('/', function () {
        return redirect('/backend/login');
    });
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/dashboard', 'HomeController@index')->name('dashboard');

        /**
         * User Page
         **/
        Route::get('user/data', 'Backend\UserController@data')->name("user.data");
        Route::post('user/restore/{id}', 'Backend\UserController@restore')->name('user.restore');
        Route::delete('user/remove/{id}', 'Backend\UserController@remove')->name('user.delete');
        Route::resource('user', 'Backend\UserController');
    });
});
