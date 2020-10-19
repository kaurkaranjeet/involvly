<!DOCTYPE html>

<html>
  <head>
    <title>Assigned task</title>
  </head>
  <style>
            a:hover {
                text-decoration: none;
            }
            .ucenter {
                text-align:center
            }
            table, th, td {
                border: 1px solid black;
            }
        </style>
  <body>
    <h2>Hello {{ $name}}, </h2>
    <br/>
    You have assigned a new task placed by {{ $task_creator}} .

    <br/>
    <p>Task details  are as Follows , <br>
                    <table class="ucenter">
                        <tr>
                            <td>Name</td>
                            <td>Date</td>
                            <td>Time</td>
                            <td>Description</td>
                        </tr>
                        <tr>
                            <td>{{$task_name}}</td>
                            <td>{{$task_date}}</td>
                            <td>{{$task_time}}</td>
                            <td>{{$task_description}}</td>
                        </tr>

                    </table><br><br>
                    <p>Thankyou!</p>
   

  </body>
</html>
