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

Route::post('/attendances/import','AttendancesController@import')->name('attendances.import');

Route::get('/attendances/{attendance}/createExtra', 'AttendancesController@createExtra')->name('attendances.createExtra');

Route::get('/attendances/{attendance}/createAbsence', 'AttendancesController@createAbsence')->name('attendances.createAbsence');

Route::patch('/attendances/{attendance}/changeAbnormal', 'AttendancesController@changeAbnormal')->name('attendances.changeAbnormal');

Route::get('/attendances/{attendance}/clock', 'AttendancesController@clock')->name('attendances.clock');

Route::patch('/attendances/{attendance}/updateClock', 'AttendancesController@updateClock')->name('attendances.updateClock');

Route::get('/attendances/{attendance}/addTime', 'AttendancesController@addTime')->name('attendances.addTime');

Route::post('/attendances/{attendance}/createAddTime', 'AttendancesController@createAddTime')->name('attendances.createAddTime');

Route::get('/attendances/{attendance}/addNote', 'AttendancesController@addNote')->name('attendances.addNote');

Route::post('/attendances/{attendance}/createAddNote', 'AttendancesController@createAddNote')->name('attendances.createAddNote');

Route::post('/attendances/export','AttendancesController@export')->name('attendances.export');

Route::get('/attendances/{attendance}/basic', 'AttendancesController@basic')->name('attendances.basic');

Route::patch('/attendances/{attendance}/changeBasic', 'AttendancesController@changeBasic')->name('attendances.changeBasic');

Route::get('results','AttendancesController@index')->name('attendances.results');

Route::resource('/holidays','HolidaysController');

Route::resource('/salarys','SalarysController');

Route::resource('/teachers','TeachersController');
Route::resource('/lessons','LessonsController');
Route::resource('/missings','MissingsController');
Route::resource('/substitutes','SubstitutesController');
Route::resource('/alters','AltersController');
Route::resource('/lesson_attendances','LessonAttendancesController');

Route::get('login','SessionsController@create')->name('login');
Route::post('login','SessionsController@store')->name('login');
Route::delete('logout','SessionsController@destroy')->name('logout');


