<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\User;
use App\Queue;
use App\LikeUnlike;
use Pusher\Pusher;
use App\FollowUnfollow;
use App\Comment;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Notification;
use Carbon\Carbon;

class ProfileController extends Controller {
    public function __construct()
    {
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        );
        $this->pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'), 
            $options
        );

        
    }

    public function updateProfile(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'username' => 'required',
                    'user_id' => 'required|exists:users,id'
        ]);
       
       if ($validator->fails()) {
            return response()->json(array('error' => true, 'errors' => $validator->errors()), 200);
        }  
        else{ 
       $count_user=User::where('id','!=', $request->user_id)->where('username','=',$request->username)->count();
         
          if($count_user==0){
            $updateData = User::where('id', $request->user_id)->update([
                'name' => $request->name,
                'username' => $request->username,
                'bio' => $request->bio,
                'insta_key' => $request->insta_key
            ]);
            // upload image file
            if ($request->hasfile('image')) {
              $video = $request->file('image');
              $name = time() . '.' . trim($video->getClientOriginalExtension());
              $destinationPath = public_path('/uploads');
              $video->move($destinationPath, $name);
              $videourl = url('/') . '/uploads/' . $name;
              $updateData = User::where('id', $request->user_id)->update([
                'image' => $videourl
              ]);
            }
            $update= User::find( $request->user_id);
            return response()->json(array('error' => false, 'message' => 'profile update successfully', 'data' => $update), 200);
          }
        else{
           return response()->json(array('error' => true, 'message' => 'Username already exist', 'data' => []), 200);
        }
      }
       
    }

    public function Getuserprofile(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
            'user_id' => 'required'

        ]);    
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'errors' => $validator->errors()), 200);
        }  
        else{ 
            $data = User::fetchUser($request->user_id);
            $get_followers=User::FollowedUsers($request->user_id);
            $following_users=User::FollowingUser($request->user_id);
            $data->followers=$get_followers;
            $data->following=$following_users;
            if(isset($request->viewing_user_id)){
                $folow_or_not=FollowUnfollow::where('following_user_id',$request->viewing_user_id)->where('followed_user_id',$request->user_id)->count();
                if($folow_or_not >0){
                    $data->is_follow=1;
                }else{
                  $data->is_follow=0;
                }
            }
           // DB::enableQueryLog(); 

            $streams=  Queue::select(DB::raw('GROUP_CONCAT(id) as streams'))->where('user_id' , $request->user_id)->first();
            if(!empty($streams->streams)){
              $results = DB::select( DB::raw("select count(*) as total_likes from `like_unlike` where `stream_id` in (".$streams->streams.") and `like` = 1") );

              if(isset($results[0])){
                $data->total_likes=number_format_short($results[0]->total_likes);
              }
              else{
               $data->total_likes=0;
             }

           }
            else{
               $data->total_likes=0;
             }

            return response()->json(array('error' => false, 'message' => 'profile fetched successfully', 'data' => $data), 200);

        }

    }

   
}
