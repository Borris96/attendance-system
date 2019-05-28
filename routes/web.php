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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/home', 'StaticPagesController@index');

Route::resource('/staffs','StaffsController');

Route::resource('/absences','AbsencesController');

Route::resource('/extra_works','ExtraWorksController');

Route::resource('/attendances','AttendancesController');
