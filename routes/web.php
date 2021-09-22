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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/newApi', [App\Http\Controllers\ApiController::class, 'newApi'])->name('newApi');
Route::post('/saveApi', [App\Http\Controllers\ApiController::class, 'saveApi'])->name('saveApi');
Route::post('/updateApi/{id}', [App\Http\Controllers\ApiController::class, 'updateApi'])->name('updateApi');
Route::post('/submitUserGivenValues/{id}', [App\Http\Controllers\ApiController::class, 'submitUserGivenValues'])->name('submitUserGivenValues');
Route::get('/editApi/{id}', [App\Http\Controllers\ApiController::class, 'editApi'])->name('editApi');
Route::get('/getApi/{id}', [App\Http\Controllers\ApiController::class, 'getApi'])->name('getApi');
Route::get('/deleteApi/{id}', [App\Http\Controllers\ApiController::class, 'deleteApi'])->name('deleteApi');
Route::get('/runApi/{id}', [App\Http\Controllers\ApiController::class, 'runApi'])->name('runApi');
Route::get('/checkUserGivenValue/{id}', [App\Http\Controllers\ApiController::class, 'checkUserGivenValue'])->name('checkUserGivenValue');
