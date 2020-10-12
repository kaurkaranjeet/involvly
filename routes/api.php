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
   Route::post('login', 'UserController@login');
   Route::any('/manage-users/{id}', ['as' => 'manage.users', 'uses' => 'UserController@manageUsers']);
   Route::any('/fetch-user/{id}', ['as' => 'fetch.user', 'uses' => 'UserController@fetchUser']);
   Route::post('update-profile', 'UserController@UpdateProfile');
   Route::get('delete-user/{id}', 'UserController@RemoveUser');
   Route::get('user', 'UserController@getAuthenticatedUser');
   Route::get('get_total_statistic/{id}', 'UserController@gettotalStatistic');

 });
// Mobile Apis
  


  Route::prefix('v1')->group(function () {
    Route::post('forgot-password', 'Api\V1\LoginController@forgot_password');

Route::group(['middleware' => 'auth:api'], function () {
 Route::post('change-password', 'Auth\AuthController@change_password');
});
Route::post('login', 'Api\V1\LoginController@login');
Route::post('logout', 'Api\V1\LoginController@Logout');
Route::post('signup_student', 'Api\V1\StudentController@StudentRegister');
Route::post('signup_teacher', 'Api\V1\TeacherController@TeacherRegister');
Route::post('signup_parent', 'Api\V1\ParentController@ParentRegister');
Route::post('get_cities', 'Api\V1\CommonController@GetCities');
Route::get('list_states', 'Api\V1\CommonController@GetStates');
Route::post('list_schools', 'Api\V1\CommonController@GetSchools');
Route::get('list_subjects', 'Api\V1\CommonController@GetSubjects');
Route::post('list_students', 'Api\V1\ParentController@GetStudents');
Route::post('verify_otp', 'Api\V1\LoginController@VerifyOtp');
Route::post('check_classcode', 'Api\V1\StudentController@Checkifclassvalid');
Route::post('change_password', 'Api\V1\LoginController@ChnagePassword');
Route::post('add_children', 'Api\V1\ParentController@AddChildren');
Route::post('email_exist', 'Api\V1\LoginController@EmailExist');
Route::post('join_community', 'Api\V1\CommonController@Joincommunity');
Route::post('add_post', 'Api\V1\PostController@AddPost');
Route::post('add_comment', 'Api\V1\PostController@AddComments');
Route::get('run_migration', 'Api\V1\CommonController@RunMigration');
Route::get('get_home_feed', 'Api\V1\PostController@GetPostHomefeed');
Route::post('get_comments', 'Api\V1\PostController@GetComments');
Route::post('like_post', 'Api\V1\PostController@LikeUnlikePost');
Route::post('list_home_students', 'Api\V1\ParentController@GethomeStudents');

  });
