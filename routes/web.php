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

Auth::routes(
    [
        'reset' => false,
        'verify' => false,
    ]
);

Route::get('/home', 'HomeController@index')->name('home');
Route::prefix('apps')
    ->name('apps.')
    ->middleware('auth')
    ->group(
        function () {
            Route::prefix('mailmerge')->name('mailmerge.')->group(
                function () {
                    Route::resource('doclogo', 'MailMerge\DocLogoController');
                    Route::resource('editor', 'MailMerge\EditorController');
                    Route::resource('exactcopy', 'MailMerge\ExactCopyController');
                    Route::resource('signature', 'MailMerge\SignatureController');
                    Route::get('/print/{id}', 'MailMerge\MailMergeController@print')
                        ->name('print');
                    Route::get('/show2/{id}', 'MailMerge\MailMergeController@show2')
                        ->name('show2');
                    Route::get('/save/{id}', 'MailMerge\MailMergeController@save')
                        ->name('save');

                    Route::prefix('recipient')->name('recipient.')->group(
                        function () {
                            Route::get('list', 'MailMerge\RecipientController@list')
                                ->name('list');
                            Route::post('storeMany', 'MailMerge\RecipientController@storeMany')
                                ->name('storeMany');
                        }
                    );
                    Route::resource('recipient', 'MailMerge\RecipientController');
                }
            );
            Route::resource('mailmerge', 'MailMerge\MailMergeController');
        }
    );
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('auth')
        ->group(
            function () {
                Route::resource('user', 'UserController');
                Route::get('/', 'AdminController@index')->name('index');
            }
        );
