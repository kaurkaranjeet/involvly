     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

 <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
 
<script>
    $(document).ready(function () {
     
        var pusher = new Pusher("89e6c9370799fbae957c", {
        cluster: "ap2",
         useTLS: true
       
       
    });

            var channel = pusher.subscribe('comment-channel');

             channel.bind('add_comment', function(data) {
        console.log(data)
      //  displayMessage(data.data);
    });
    });

</script>
