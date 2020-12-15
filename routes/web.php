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

        /**
         * Car Request
         */
        Route::post('car_request/search', 'Backend\CarRequestController@search')->name('car_request.search');
        Route::get('car_request/detail', 'Backend\CarRequestController@detail')->name('car_request.detail');
        Route::get('car_request/print', 'Backend\CarRequestController@printPdf')->name('car_request.print');
        Route::post('car_request/report', 'Backend\CarRequestController@exportReport')->name('car_request.export');
        Route::resource('car_request', 'Backend\CarRequestController');
        /**
         * HR Management
         */
        Route::group(['prefix' => 'hr'], function () {
            Route::post('departement/restore/{id}', 'Backend\DepartementController@restore')->name('departement.restore');
            Route::delete('departement/remove/{id}', 'Backend\DepartementController@remove')->name('departement.delete');
            Route::resource('departement', 'Backend\DepartementController');

            // Employee
            Route::post('employee/restore/{id}', 'Backend\EmployeeController@restore')->name('employee.restore');
            Route::delete('employee/remove/{id}', 'Backend\EmployeeController@remove')->name('employee.delete');
            Route::resource('employee', 'Backend\EmployeeController');
        });

        Route::group(['prefix' => 'maintenance'], function () {
            Route::resource('mobil', 'Backend\MobilController');
            Route::resource('supir', 'Backend\SupirController');
        });
    });
});
