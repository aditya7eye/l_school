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
//////////////////////////////Admin master//////////////////////////////////////////

Route::get('/dashboard','Master_Controller@dashboard');
Route::get('/manage-admin','Master_Controller@addadmin');
Route::get('/insertadmin','Master_Controller@insertadmin');
Route::get('/del_admin','Master_Controller@del_admin');
Route::get('/update_admin_form','Master_Controller@update_admin_form');
Route::get('/updateadmindetails','Master_Controller@updateadmindetails');



/////////////////////////////planmaster/////////////////////////////////////////////

Route::get('/manage-plan','Plan_Controller@addplan');
