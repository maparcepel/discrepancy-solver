<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ImportController;
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

Route::get('/import-discrepances', [ImportController::class, 'importDiscrepances']);

Route::get('/look', [ImportController::class, 'look']);

Route::get('/organice-center/{center}', [ImportController::class, 'organiceCenter']);

Route::get('/create-feeds', [ImportController::class, 'createFeeds']);