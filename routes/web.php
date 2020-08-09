<?php

use App\Http\Controllers\FirebaseController;
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
Route::get('/firebase-index', 'FirebaseController@index');
Route::get('/firebase-user', 'FirebaseController@fireBaseUserCreation');
Route::get('/firebase-user-list', 'FirebaseController@fireBaseUserList');
Route::get('/firebase-create-user', 'FirebaseController@fireBaseCreateUser');
