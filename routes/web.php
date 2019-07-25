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
Route::post('/teachers/role', 'TeachersController@role')->name('teachers.role');
Route::patch('/teachers/{teacher}/remove', 'TeachersController@remove')->name('teachers.remove');
Route::resource('/leave_teachers','LeaveTeachersController');
Route::get('create_term', 'TeachersController@createTerm')->name('create_term');
Route::post('store_term', 'TeachersController@storeTerm')->name('store_term');
Route::get('edit_term', 'TeachersController@editTerm')->name('edit_term');
Route::patch('update_term', 'TeachersController@updateTerm')->name('update_term');

Route::resource('/lessons','LessonsController');
Route::get('/lessons/{lesson}/editTeacher', 'LessonsController@editTeacher')->name('lessons.edit_teacher');
Route::patch('/lessons/{lesson}/updateTeacher', 'LessonsController@updateTeacher')->name('lessons.update_teacher');

Route::resource('/missings','MissingsController');

Route::resource('/substitutes','SubstitutesController');

Route::resource('/alters','AltersController');
Route::get('/one_time','AltersController@oneTime')->name('alters.one_time');
Route::post('/store_all','AltersController@storeAll')->name('alters.store_all');

Route::resource('/lesson_attendances','LessonAttendancesController');
Route::get('teacher_results','LessonAttendancesController@index')->name('lesson_attendances.teacher_results');
Route::get('teacher_multiple_results','LessonAttendancesController@index')->name('lesson_attendances.teacher_multiple_results');
Route::post('/lesson_attendances/export_one','LessonAttendancesController@exportOne')->name('lesson_attendances.export_one');
Route::post('/lesson_attendances/export_multiple','LessonAttendancesController@exportMultiple')->name('lesson_attendances.export_multiple');

Route::get('login','SessionsController@create')->name('login');
Route::post('login','SessionsController@store')->name('login');
Route::delete('logout','SessionsController@destroy')->name('logout');


