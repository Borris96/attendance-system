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
    return view('welcome');
});

Route::get('/home', 'StaticPagesController@index')->name('home');

Route::resource('users', 'UsersController');

Route::resource('/staffs','StaffsController');

Route::patch('/staffs/{staff}/leave', 'StaffsController@leave')->name('staffs.leave');

Route::resource('/leave_staffs','LeaveStaffsController');

Route::resource('/absences','AbsencesController');

Route::resource('/extra_works','ExtraWorksController');

Route::resource('/attendances','AttendancesController');

Route::get('showf', 'AttendancesController@showf')->name('fakeshow');

Route::resource('/holidays','HolidaysController');

Route::resource('/salarys','SalarysController');

Route::get('login','SessionsController@create')->name('login');
Route::post('login','SessionsController@store')->name('login');
Route::delete('logout','SessionsController@destroy')->name('logout');

