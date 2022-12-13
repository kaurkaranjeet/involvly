<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobFailed;
 

class ProcessRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $usersData;
    protected $notificationobj;

  
    public function __construct($usersData)
    {
        $this->usersData = $usersData;
    }
   
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $usersData = $this->usersData;
        // $notificationobj = $this->notificationobj;

  
        $message = $usersData->name . ' You have recieved a place request!';
        $rom_user_id = 2;
       
        if (!empty($usersData->device_token) && $usersData->device_token != null) {
            if ($usersData->notification_settings == 1) {
                SendAllNotification($usersData->device_token, $message, 'place_notification');
            }
        }
            Notification::create([
                'user_id'=> $usersData->id,
                'notification_message'=> $message,
                'notification_type'=> 'PlaceRequest',
                'type'=> 'teacher_notification',
                'push_type'=> 'place_request',
                'from_user_id'=> $rom_user_id,
             ]);
        
       
    }
}
