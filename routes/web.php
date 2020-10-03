<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', 'PagesController@index');

Route::get('/setup', 'SetupController@setupPage');
Route::post('/setup', 'SetupController@saveSetup')->name('setup');

Auth::routes([
    'reset' => false,
    'verify' => false,
]);

Route::get('/home', 'HomeController@index')->name('home');
Route::prefix('apps')->name('apps.')->group(function () {
    Route::prefix('mailmerge')->name('mailmerge.')->group(function () {
        Route::resource('doclogo', 'DocLogoController');
    });
    Route::resource('mailmerge', 'MailMergeController');
});
