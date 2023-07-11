<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use Modules\Auth\Http\Controllers\ChatMessageController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('api/auth/reset-password/{token}', function($token){
//     return view('auth::resetPassword\index', ['token' => $token]);
// })->name('reset.password.page');
Route::get('login', function (){
    return view('auth::auth.login');
  })->name('login');
Route::get('register', function (){
    return view('auth::auth.register');
  })->name('register');

Route::get('/',[ ChatMessageController::class,'index'])->middleware(['auth']);
Route::get('/messages', [ChatMessageController::class,'fetchMessages']);  
Route::post('auth1', [ChatMessageController::class, 'authenticate'])->name('authenticate');
Route::post('/messages', [ChatMessageController::class,'sendMessage']);
