<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

  Route::prefix('auth')->group(function () {
    // Below mention routes are public, user can access those without any restriction.
    // Create New User
//    Route::post('register', 'UserController@register');
    // Login User
  /* Route::post('login', 'UserController@login');
   Route::any('/manage-users', ['as' => 'manage.users', 'uses' => 'UserController@manageUsers']);
   Route::any('/fetch-user/{id}', ['as' => 'fetch.user', 'uses' => 'UserController@fetchUser']);
   Route::post('update-profile', 'UserController@UpdateProfile');
 Route::get('delete-user/{id}', 'UserController@RemoveUser');*/
 });
// Mobile Apis

  Route::prefix('v1')->group(function () {
    Route::post('login', 'Api\V1\LoginController@login');
    Route::post('signup_student', 'Api\V1\StudentController@StudentRegister');
      Route::post('signup_student', 'Api\V1\TeacherController@TeacherRegister');
    Route::post('get_cities', 'Api\V1\CommonController@GetCities');
    Route::get('list_states', 'Api\V1\CommonController@GetStates');
    Route::get('list_schools', 'Api\V1\CommonController@GetSchools');
  });
