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

//  RUTAS INDEX
Route::get('', ['uses' => 'Index\IndexController@login', 'as' => 'index.login', 'middleware' => 'guest']);
Route::post('login', ['uses' => 'Index\IndexController@loginUser', 'as' => 'index.auth', 'middleware' => 'guest']);
Route::get('logout', ['uses' => 'Index\IndexController@logout', 'as' => 'index.logout', 'middleware' => 'user']);

//  RUTAS USER
Route::group(['prefix' => 'user', 'middleware' => 'user'], function() {
    Route::get('', ['uses' => 'User\IndexController@index', 'as' => 'user.index']);
    Route::post('/create', ['uses' => 'User\IndexController@store', 'as' => 'user.create']);
    Route::get('/list', ['uses' => 'User\IndexController@listTicket', 'as' => 'user.list']);
    Route::get('/ticket/{ticket}', ['uses' => 'User\IndexController@show', 'as' => 'user.show']);
    Route::put('/ticket/{ticket}/payTicket', ['uses' => 'User\IndexController@payTicket', 'as' => 'user.payTicket']);
    Route::put('/ticket/{ticket}/nullTicket', ['uses' => 'User\IndexController@nullTicket', 'as' => 'user.nullTicket']);
    Route::get('/results', ['uses' => 'User\ResultController@index', 'as' => 'results.index']);
    Route::resource('/ticketOffice', 'Admin\TicketOfficeController');
    Route::get('report', ['uses' => 'User\ReportController@index', 'as' => 'user.report']);
    Route::get('report/generate', ['uses' => 'User\ReportController@generateDailyReport', 'as' => 'user.report.generate']);
});

//  RUTAS ADMIN
Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {
    Route::put('sorts/upAll', ['uses' => 'Admin\SortController@upAll', 'as' => 'sorts.upAll']);
    Route::get('sorts/downAll', ['uses' => 'Admin\SortController@downAll', 'as' => 'sorts.downAll']);
    Route::get('sorts/downSort/{sort}', ['uses' => 'Admin\SortController@downSort', 'as' => 'sorts.downSort']);
    Route::get('sorts/upSort/{sort}', ['uses' => 'Admin\SortController@upSort', 'as' => 'sorts.upSort']);
    Route::resource('sorts', 'Admin\SortController');
    Route::put('/results/animalGain/{dailySort}', ['uses' => 'User\ResultController@animalGain', 'as' => 'results.animalGain']);
});
