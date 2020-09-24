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
   Route::any('/manage-users', ['as' => 'manage.users', 'uses' => 'UserController@manageUsers']);
   Route::any('/fetch-user/{id}', ['as' => 'fetch.user', 'uses' => 'UserController@fetchUser']);
   Route::post('update-profile', 'UserController@UpdateProfile');
 Route::get('delete-user/{id}', 'UserController@RemoveUser');

    // Refresh the JWT Token
    // Route::get('refresh', 'UserController@refresh');
    // // Below mention routes are available only for the authenticated users.
    // Route::middleware('jwt.verify')->group(function () {
    //     // Get user info
    //     Route::get('user', 'UserController@user');
    //     // Logout user from application
    //     Route::post('logout', 'UserController@logout');
    // });
 });
// Mobile Apis

  Route::prefix('v1')->group(function () {
    Route::post('login', 'Api\V1\LoginController@login');
    Route::post('social-login', 'Api\V1\LoginController@socialSignupLogin');
    Route::post('music-login', 'Api\V1\LoginController@musicSignupLogin');
    Route::post('update-profile', 'Api\V1\ProfileController@updateProfile');
    Route::post('getuserprofile', 'Api\V1\ProfileController@Getuserprofile');
    Route::post('create_stream', 'Api\V1\WowzaController@AddStream');
    Route::post('add_queue', 'Api\V1\QueueController@AddUserQueue');
    Route::get('get_live_user', 'Api\V1\QueueController@GetUserLive');
    Route::get('list_avaliable_slot', 'Api\V1\QueueController@listallslots');
    Route::get('listfutureslots', 'Api\V1\QueueController@listfutureslots');
    Route::get('listbda/{user_id}', 'Api\V1\CommonController@listBda');
    Route::post('listuserslots', 'Api\V1\QueueController@listuserslots');  
    Route::post('start_stream', 'Api\V1\QueueController@StartStream');  
    Route::post('end_stream', 'Api\V1\QueueController@EndStream'); 
    Route::post('user_watching_stream', 'Api\V1\QueueController@UserWatching');
    //upload video
    Route::post('upload_video', 'Api\V1\CommonController@uploadVideo');
    Route::post('like_unlike', 'Api\V1\ProfileController@LikeUnlikeStream');
    Route::post('add_comment', 'Api\V1\ProfileController@AddComments');
    Route::post('follow_unfollow', 'Api\V1\ProfileController@FollowUnfollowUser');
    Route::post('stream_comments', 'Api\V1\QueueController@GetComments');
    Route::post('get_audio', 'Api\V1\QueueController@GetAudio');
    Route::get('send_notification', 'Api\V1\NotificationController@SendNotification');
    Route::get('send_two_minutes', 'Api\V1\ProfileController@Sendtwominutes');
    Route::post('logout', 'Api\V1\LoginController@Logout'); 
   Route::post('confirm_slot', 'Api\V1\QueueController@ConfirmSlot'); 
    Route::post('notification_list', 'Api\V1\NotificationController@AllNotifications'); 
  });
