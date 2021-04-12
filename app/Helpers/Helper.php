<?php
use App\Models\SubmittedAssignments;
function number_format_short( $n, $precision = 1 ) {
	if ($n < 900) {
		// 0 - 900
		$n_format = number_format($n, $precision);
		$suffix = '';
	} else if ($n < 900000) {
		// 0.9k-850k
		$n_format = number_format($n / 1000, $precision);
		$suffix = 'K';
	} else if ($n < 900000000) {
		// 0.9m-850m
		$n_format = number_format($n / 1000000, $precision);
		$suffix = 'M';
	} else if ($n < 900000000000) {
		// 0.9b-850b
		$n_format = number_format($n / 1000000000, $precision);
		$suffix = 'B';
	} else {
		// 0.9t+
		$n_format = number_format($n / 1000000000000, $precision);
		$suffix = 'T';
	}

  // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
  // Intentionally does not affect partials, eg "1.50" -> "1.50"
	if ( $precision > 0 ) {
		$dotzero = '.' . str_repeat( '0', $precision );
		$n_format = str_replace( $dotzero, '', $n_format );
	}

	return $n_format . $suffix;
}


function SendIosNotification($tToken,$message){
	$tHost = 'gateway.sandbox.push.apple.com';
	$tPort = 2195;
// Provide the Certificate and Key Data.
$tCert = public_path().'/push_notifications/PushNotificationAppCertificateKey.pem';
// Provide the Private Key Passphrase (alternatively you can keep this secrete 
// and enter the key manually on the terminal -> remove relevant line from code).
// Replace XXXXX with your Passphrase
//$tPassphrase = 'XXXXX';
// Provide the Device Identifier (Ensure that the Identifier does not have spaces in it).
// Replace this token with the token of the iOS device that is to receive the notification.
//$tToken = '45ecc48b3999bdae381dfa0b057d93436fa38dd6e0690bc81337a7bc3ed6f48e';
// The message that is to appear on the dialog.
//$tAlert = 'You have a LiveCode APNS Message';
// The Badge Number for the Application Icon (integer >=0).
$tBadge = 1;
// Audible Notification Option.
$tSound = 'default';
// The content that is returned by the LiveCode "pushNotificationReceived" message.
$tPayload = 'APNS Message Handled by LiveCode';
// Create the message content that is to be sent to the device.
$tBody['aps'] = array (
	'alert' => $message,
	'badge' => $tBadge,
	'sound' => $tSound,
	);
$tBody ['payload'] = $message;
// Encode the body to JSON.
$tBody = json_encode ($tBody);
// Create the Socket Stream.
$tContext = stream_context_create ();
stream_context_set_option ($tContext, 'ssl', 'local_cert', $tCert);
// Remove this line if you would like to enter the Private Key Passphrase manually.
//stream_context_set_option ($tContext, 'ssl', 'passphrase', $tPassphrase);
// Open the Connection to the APNS Server.
$tSocket = stream_socket_client ('ssl://'.$tHost.':'.$tPort, $error, $errstr, 30, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $tContext);
// Check if we were able to open a socket.
if (!$tSocket)
	exit ("APNS Connection Failed: $error $errstr" . PHP_EOL);
// Build the Binary Notification.
$tMsg = chr (0) . chr (0) . chr (32) . pack ('H*', $tToken) .  pack ('n', strlen ($tBody)) . $tBody;
// Send the Notification to the Server.
$tResult = fwrite ($tSocket, $tMsg, strlen ($tMsg));
/*if ($tResult)
	echo 'Delivered Message to APNS' . PHP_EOL;
else
	echo 'Could not Deliver Message to APNS' . PHP_EOL;*/
// Close the Connection to the Server.
fclose ($tSocket);
}

function SendAllNotification($token,$message,$notify_type,$schedule=null,$type=null,$post_id=null,$student_id=null,$class_id=null,$userreceiver=null){
$API_ACCESS_KEY='AAAAwP6ydfI:APA91bHzk-W1vsuXNWWNDJI1dzt9jnnd1BbDRFnRvKU_qmOIn0DRK4BLTUAGpz6FbDKF9a4UmrIm8Sb6tajxHfCJBzKnDnf7jgg9dgu3oLDNFD0bonhRNUOf9-Cl8jQhbs3mPoaqKxge';
 $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
// $token='235zgagasd634sdgds46436';

    $notification = [
            'title' =>'Involvvely',
            'body' => $message,
            'sound' => 'default',
            "click_action"=>"FLUTTER_NOTIFICATION_CLICK",
            'badge' => '1',
        ];
        if(is_object($schedule)){
        $extraNotificationData = ["body" => $notification,"title" =>$message,"notification_type"=>$notify_type,"push_type"=>$type,'user_reciever' => $userreceiver,"schedule"=>$schedule,"post_id"=>$post_id,"student_id"=>$student_id,"class_id"=>$class_id];
    }else{
        if($type=='add_assign'||$type=='submitted'){
           $subject= SubmittedAssignments::where("assignment_id",$schedule)->select("subject_id","class_id")->first();
                $extraNotificationData = ["body" => $notification,"title" =>$message,"notification_type"=>$notify_type,"push_type"=>$type,'user_reciever' => $userreceiver,"assignment_id"=>$schedule,"subject_id"=>$subject->subject_id,"class_id"=>$subject->class_id,"post_id"=>$post_id,"student_id"=>$student_id,"class_id"=>$class_id];
            }
                else{
                        $extraNotificationData = ["body" => $notification,"title" =>$message,"notification_type"=>$notify_type,"push_type"=>$type,
                        'user_reciever' => $userreceiver,"task_id"=>$schedule,"post_id"=>$post_id,"student_id"=>$student_id,"class_id"=>$class_id];

                }  
        }
    



        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'notification' => $notification,
             'priority'=>'high',
            'data' => $extraNotificationData,
            "click_action"=>"FLUTTER_NOTIFICATION_CLICK"

        ];
         $headers = [
            'Authorization: key=' . $API_ACCESS_KEY,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);


//echo $result;

}

?>