<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Queue;
use App\Slot;
use Illuminate\Support\Facades\Validator;
use App\Events\SlotEvent;
use Pusher\Pusher;
use DB;
use Carbon\Carbon;
use App\VideoUser;
use File;
use Pawlox\VideoThumbnail\Facade\VideoThumbnail;

class CommonController extends Controller {

    public function __construct() {
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

        $this->start_date = date("d-m-Y h:i:s a");
    }

    /**
     * upload video
     * @param Request $request
     * @return type
     */
    public function uploadVideo(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:users,id',
                    'file' => 'required',
                     'stream_id' => 'required|exists:queues,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'errors' => $validator->errors()->first()), 200);
        } else {
            if ($request->hasfile('file')) {
                $video = $request->file('file');
                $name = time() . '.' . $video->getClientOriginalExtension();
                $destinationPath = public_path('/uploads');
                $video->move($destinationPath, $name);
                $videourl = url('/') . '/uploads/' . $name;
                $path = $videourl;
            } else {
                $path = url('/') . '/uploads/dummy.png';
            }
            $testGD = get_extension_funcs("gd"); // Grab function list 
//            if (!$testGD) {
//                echo "GD not even installed.";
//                phpinfo();  // Display the php configuration for the web server
//                exit;
//            }
//            echo"<pre>" . print_r($testGD, true) . "</pre>";
            $storageUrl = public_path('/images');
            $fileName = 'thumb-'.time().'.jpg';
            $second = 5;
            $thumb = VideoThumbnail::createThumbnail(public_path('/uploads/' . $name), public_path('/uploads/'), $fileName, $second);
//                $thumb = VideoThumbnail::createThumbnail($videourl, exec('sudo chmod -R 0777 ' . $storageUrl), $fileName, $second);
            $thumbPath = url('/') . '/uploads/' . $fileName;
            $video = new VideoUser();
            $video['user_id'] = $request->input('user_id');
            $video['video_url'] = $path;
            $video['thumbnail_url'] = $thumbPath;
            $video->save();
            if(isset($request->stream_id)){
             Queue::where('id',$request->stream_id)->update(['video_id' => $video->id,'total_user_watching'=> $request->total_user_watching]);
            }
            
            if (!empty($video)) {
                return response()->json(array('error' => false, 'message' => 'video uploaded successfully', 'data' => $video), 202);
            } else {
                return response()->json(array('error' => true, 'message' => 'video  not uploaded', 'data' => []), 200);
            }
        }
    }

    public function listBda($user_id) {
        if (!empty($user_id)) {
          
            $list = VideoUser::leftJoin('queues', 'queues.video_id', '=', 'video_user.id')->where("video_user.user_id", $user_id)->select('video_user.*','queues.total_user_watching')->orderBy('video_user.id', 'DESC')->get();


            if (!empty($list)) {
                return response()->json(array('error' => false, 'message' => 'data get successfully', 'data' => $list), 202);
            }
        } else {
            return response()->json(array('error' => true, 'message' => 'user id is empty', 'data' => []), 200);
        }
    }

}
