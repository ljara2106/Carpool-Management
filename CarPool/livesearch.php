<?php

session_start();

if (isset($_SESSION["user_id"])) {
    
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
}


if (isset($_POST)['input'])){
        $input  =$_POST['input'];

        $sql="select * from `students` where student_id='{$input}''";
        
        $results=mysqli_query($mysqli,$sql);

        if($results){
            if(mysqli_num_rows($results)>0){
             echo '<thead>
             <tr>
             <th><strong>ID</strong></th>
             <th><strong>Student ID</strong></th>
             <th><strong>First Name</strong></th>
             <th><strong>Last Name</strong></th>
             <th><strong>Grade</strong></strig></th>
             <th><strong>Teacher Name</strong></th>
             </tr>
             </thead>
             ';

             while($row=mysqli_fetch_assoc($results)){
             echo '<tbody>
             <tr>
             <td>'.$row['id'].'</td>
             <td>'.$row['student_id'].'</td>
             <td>'.$row['first_name'].'</td>
             <td>'.$row['last_name'].'</td>
             <td>'.$row['grade'].'</td>
             <td>'.$row['teacher_name'].'</td>
             </tr>
             </tbody>';   
             
             

          //add search result to inqueue table
          $check_queue =  $mysqli->query("SELECT student_id FROM `inqueue`  WHERE student_id = '$search' and DATE(datetime_added) = CURDATE()");
          if($check_queue->num_rows == 0) {
               // row not found, do stuff...
               $add_queue = "insert into `inqueue` ( `student_id`, `first_name`, `last_name`, `grade`, `teacher_name`) 
               values ($row[student_id], '$row[first_name]','$row[last_name]', $row[grade],'$row[teacher_name]')"; 
               $result_queue = mysqli_query($mysqli,$add_queue);

                 echo '  <strong><h2 style="background-color:DodgerBlue;"> '  .$row['first_name'].  ' added to QUEUE list!</h2> </strong><br><br><br>';
            
          } else {
              // do other stuff...
              echo '  <strong><h2 style="background-color:red;"> '  .$row['first_name'].  ' already in QUEUE list!</h2> </strong><br><br><br>';
          }
          $mysqli->close();
          
 
          //var_dump($add_queue);
         // die;         s
     
          

        


      }


  }


     else{
         echo '<h2 class=text-danger>Student data not found</h2>'; 
     }

     }
}

?>