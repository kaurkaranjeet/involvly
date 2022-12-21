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
    You have been assigned a new task by {{ $task_creator}} .


          <br/>
          Task Details as follow 
          <div class="container">
            <div>Name:  {{$task_name}}</div>		  `
            <div>Description:  {{$task_description}}</div>
            <div> Task Dates:
              @foreach($task_date as $single)
              {{$single}}
              <br>
              @endforeach

            </div>
            <div>Task time:  {{$task_time}}</div>
            <div>Description:  {{$task_description}}</div>
          </div>
        </body>
</html>
