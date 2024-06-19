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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth', 'prefix' => 'clients'], function () {
    Route::resource('/', 'ClientsController', ['names' => 'clients'])
        ->only([
            'index',
            'create',
            'store',
        ]);

    Route::get('/{client}', 'ClientsController@show')->name('clients.show');
    Route::delete('/{client}', 'ClientsController@destroy')->name('clients.destroy');

    Route::get('/{client}/bookings', 'BookingsController')->name('client.bookings');

    Route::resource('/{client}/journals', 'JournalsController', ['names' => 'journals'])
        ->only([
            'index',
            'create',
            'store',
            'destroy',
        ]);
});
