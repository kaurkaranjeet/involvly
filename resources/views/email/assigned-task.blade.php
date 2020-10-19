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
    <div class="container">
	
		<div>Name:  {{$task_name}}</div>		
		<div>Description:  {{$task_description}}</div>
		<div>Date:  {{$task_date}}</div>
		<div>Time:  {{$task_time}}</div>
	 </div>
  </body>
</html>
