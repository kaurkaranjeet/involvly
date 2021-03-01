     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

 <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
 
<script>
    $(document).ready(function () {
        alert("like_post");
     
        var pusher = new Pusher("89e6c9370799fbae957c", {
        cluster: "ap2",
         useTLS: true
       
       
    });

            var channel = pusher.subscribe('notification-channel');

             channel.bind('notification_all_read', function(data) {
                 alert("done")
            console.log(data)
      //  displayMessage(data.data);
    });
    });

</script>
