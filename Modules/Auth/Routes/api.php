<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Entities\jobCard;
use Modules\Auth\Entities\User;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\StewardController;
use Modules\Auth\Http\Controllers\DutyRosterController;
use Modules\Auth\Http\Controllers\CompanyController;
use Modules\Auth\Http\Controllers\CommentController;
use Modules\Auth\Http\Controllers\JobCardController;

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

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('login', 'Login');
    Route::post('register', 'register');
    Route::post('forget-password', 'forgetPassword');
    Route::post('reset-password/{token}', 'resetPassword')->name('password.reset');
   
    Route::middleware('auth:api')->post('change-password', 'changePassword');
    Route::middleware('auth:api')->get('logout', 'logout'); 
    Route::middleware('auth:api')->get('user','getUser');
});

Route::middleware('check_permission')->prefix('company')->name('company.')->group(function () {
    Route::post('create', [CompanyController::class, 'store']);
    Route::get('list',  [CompanyController::class, 'index'])->middleware('queryParameters:Company');
    // show stewards for the company
    Route::get('steward/{company_id}/list', [StewardController::class, 'showByCompany']);
    // show job cards for company
    Route::get('jobCard/{company_id}/list', [JobCardController::class, 'showByCompany']);
});

Route::middleware('check_permission')->prefix('steward')->name('steward.')->group(function () {
    Route::post('create', [StewardController::class, 'store']);
    Route::get('list', [StewardController::class, 'index']);
    Route::get('{id}/list', [StewardController::class, 'show']);
    Route::put('update/{user}', [StewardController::class, 'update']);
    Route::delete('delete/{user}', [StewardController::class, 'delete']);
});

Route::middleware('check_permission')->prefix('jobCard')->name('jobCard.')->group(function(){
      /* store comments for job card 
     @{jobcard_id}
     */
    Route::post('{jobcard_id}/comment/create', [CommentController::class, 'store']);
    //create job card for company
    Route::post('{company_id}/create', [JobCardController::class, 'store']);
    /* get data with advanced results 
        ** Select **
        ** Sort **
        ** Pagination **
        ** Limit **
            @ middleWare QueryParameters
            Route::get('list', [JobCardController::class, 'index'])->middleware('queryParameters:jobCard');
    */
    Route::get('{id}', [JobCardController::class, 'show']);
    Route::put('update/{id}', [JobCardController::class, 'update'])->name('update');
    Route::delete('delete/{id}', [JobCardController::class, 'delete']);
    // show comments for the job card
    Route::get('{jobcard_id}/comment/list', [CommentController::class, 'showByJobCard']);
});

Route::middleware('check_permission')->prefix('comments')->name('comments.')->group(function(){
    Route::get('list', [CommentController::class, 'show']);
    Route::get('user/list', [CommentController::class, 'showByUser']);
    Route::put('{id}/update', [CommentController::class, 'update']);
});

Route::middleware('check_permission')->prefix('jobRoster')->name('jobRoster.')->group(function(){
    Route::get('list', [CommentController::class, 'show']);
    Route::get('user/list', [CommentController::class, 'showByUser']);
    Route::put('{id}/update', [CommentController::class, 'update']);
});

Route::middleware('check_permission')->prefix('dutyroster')->name('dutyroster.')->group(function(){
    Route::get('list', [DutyRosterController::class, 'index']);
    Route::get('user/{user_id}/list', [DutyRosterController::class, 'showByUser']);
    Route::put('update/{id}', [DutyRosterController::class, 'update']); 
});

Route::get('userz', function(){
    $SuperAdmin = User::with("roles")->whereHas("roles", function($q) {
        $q->whereIn("name", ['superAdmin']);
    })->get();
    $user = User::with('jobCard')->find(2);
    $jobcards = jobCard::find(18);
    $comments = $jobcards->comments()->get();
    $roles = $user->getRoleNames();
    // dd($comments);
    dd($SuperAdmin);
    foreach ($roles as $role) {
         ;// Output each comment ID
    }
});

