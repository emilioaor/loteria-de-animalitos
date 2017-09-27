<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('{ticket}/ticketText', ['uses' => 'ApiController@getTicketText', 'as' => 'api.ticketText']);
Route::get('{printCode}/printSpooler', ['uses' => 'ApiController@getPrintSpooler', 'as' => 'api.printSpooler']);
