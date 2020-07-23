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

Route::get('/', function () {
    return view('build_excel');
});


//Download Excel
Route::get('/excel','ExcelController@getExcel') ;


//Import Csv file 
Route::get('/importcsv','ExcelController@importcsv') ;


//Send email with Qr Code.
Route::get('/qrcode_email','ExcelController@sendEmail') ;

