<?php
   
namespace App\Console\Commands;
   
use Illuminate\Console\Command;
use App\User;
use App\Queue;
use App\Notification;
use Carbon\Carbon;
   
class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       
        $queues=Queue::join('users', function ($join) {
            $join->on('users.id', '=', 'queues.user_id')
                 ->whereRaw('DATE(queues.created_at) = CURDATE()')->where('queues.notification_sent',0);
        })->select('users.device_token','users.device_type','queues.*')->get();
foreach($queues as $single_queue){
date_default_timezone_set($single_queue->timezone);
$the_date = strtotime($single_queue->start_time_date);
date_default_timezone_set("UTC");
$date2 =strtotime(date("Y-m-d H:i:s", $the_date));
$date1 = strtotime(date("Y-m-d H:i:s"));
$diff = $date2 - $date1;
//echo $date2 - $date1."<br>";
//$check_minus=strpos($diff,"-");
$check_minus = strpos('going'.$diff, "-");
if($check_minus==false ){

$minutes= floor(abs($diff) / 60);  
if($single_queue->slot_id==1){
    $slot_min="3";
}else{
     $slot_min="2";
}          
if($minutes==$slot_min){
    Queue::where('id',$single_queue->id)->update(['notification_sent'=>1]);
    $message='Your Live performance is going to start in '.$slot_min.' minutes';   
    SendAllNotification($single_queue->device_token,$message,'two_minutes');
   // Notification::create(['user_id'=>$single_queue->user_id,'notification_message'=>$message]);
    // Send Notification to all users

    $users=User::where('id','!=', $single_queue->user_id)->whereHas(
        'roles', function($q){
            $q->where('id', '2')->whereNotNull('device_token');
        }
    )->get();   
    foreach($users as $user){    
        if(!empty($user->device_type)){
             $message='Live performance is going to start in '.$slot_min.' minutes';
                SendAllNotification($user->device_token,$message,'two_minutes');
    
            //Notification::create(['user_id'=>$user->id,'notification_message'=>$message]);
        }
    }
}
}

}
$this->info('Demo:Cron Cummand Run successfully!');
 \Log::info("Cron is working fine!");
    }
}