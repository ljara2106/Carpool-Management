<?php

session_start();

if (isset($_SESSION["user_id"])) {
    
    $mysqli = require __DIR__ . "/dbconfig/database.php";
    
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
}

if(isset($_POST['query'])){
    $search=$_POST['query'];
    $stmt=$mysqli->prepare( "select * from `students` where student_id=?");
    $stmt->bind_param("s",$search);



}
else{
    $stmt=$mysqli->prepare("select * from `students`");
}


$stmt->execute();
$result=$stmt->get_result();

if($result->num_rows>0){
     

    $output = "<table class ='table' id='table-data'><thead>
    <tr>
    <th><strong>Student ID</strong></th>
    <th><strong>First Name</strong></th>
    <th><strong>Last Name</strong></th>
    <th><strong>Grade</strong></strig></th>
    <th><strong>Teacher</strong></th>
    
    </tr>
    </thead> 
    <tbody>";
    $first_name = '';
    $insert_row = array();
    while($row=$result->fetch_assoc()){
        $insert_row = $row;
        $first_name = $row['first_name'];
        $output .='
        <tr>
            <td>'.$row['student_id'].'</td>
            <td>'.$row['first_name'].'</td>
            <td>'.$row['last_name'].'</td>
            <td>'.$row['grade'].'</td>
            <td>'.$row['teacher_name'].'</td>
            
        </tr>';

        
    }
    $output .="</tbody></table>";

    $message = '';

    //add search result to inqueue table
    $check_queue =  $mysqli->query("SELECT * FROM `inqueue`  WHERE student_id = '$search' and student_id != '999' and DATE(datetime_added) = CURDATE()");
    if($check_queue->num_rows == 0) {
           $row = $insert_row;
         // row not found, do stuff...
         $add_queue = "insert into `inqueue` ( `student_id`, `first_name`, `last_name`, `grade`, `teacher_name`, `teacher_id`, `picked_up`) 
         values ($row[student_id], '$row[first_name]','$row[last_name]', $row[grade],'$row[teacher_name]', $row[teacher_id], '0')"; 
         $result_queue = mysqli_query($mysqli,$add_queue);

          $message = '<strong><h2 style="background-color:green;"> '  .$row['first_name'].  ' added to QUEUE list!</h2> </strong><br>';

      
    } else {
        // do other stuff...
       $message = '<strong><h2 style="background-color:red;"> '  .$first_name.  ' is already in QUEUE list!</h2> </strong><br>';
    }

    echo $message;
    echo $output;
    
}
    else{
        echo "<h2 class=text-danger>Student data not found</h2>";
    }
    



?>

