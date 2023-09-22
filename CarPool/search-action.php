<?php

session_start();

if (isset($_SESSION["user_id"])) {
    require __DIR__ . "/dbconfig/database.php"; 
    $sql = "SELECT * FROM user WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

if(isset($_POST['query'])){
    $search = $mysqli->real_escape_string($_POST['query']);
    $stmt = $mysqli->prepare("SELECT * FROM `students` WHERE student_id = ?");
    $stmt->bind_param("s", $search);
}
else{
    $stmt = $mysqli->prepare("SELECT * FROM `students`");
}

$stmt->execute();
$result = $stmt->get_result();

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

    // Sanitize input and prevent SQL injection
    $search = $mysqli->real_escape_string($search);
    $search = htmlspecialchars($search);

    // Add search result to inqueue table
    $check_queue = $mysqli->query("SELECT * FROM `inqueue` WHERE student_id = '$search' AND student_id != '999' AND DATE(datetime_added) = CURDATE()");

    if ($check_queue->num_rows == 0) {
        // Row not found, do stuff...
        $insert_row['student_id'] = $mysqli->real_escape_string($insert_row['student_id']);
        $insert_row['first_name'] = $mysqli->real_escape_string($insert_row['first_name']);
        $insert_row['last_name'] = $mysqli->real_escape_string($insert_row['last_name']);
        $insert_row['grade'] = $mysqli->real_escape_string($insert_row['grade']);
        $insert_row['teacher_name'] = $mysqli->real_escape_string($insert_row['teacher_name']);
        $insert_row['teacher_id'] = $mysqli->real_escape_string($insert_row['teacher_id']);

        $add_queue = "INSERT INTO `inqueue` (`student_id`, `first_name`, `last_name`, `grade`, `teacher_name`, `teacher_id`, `picked_up`)
                    VALUES ('$insert_row[student_id]', '$insert_row[first_name]', '$insert_row[last_name]', '$insert_row[grade]', '$insert_row[teacher_name]', '$insert_row[teacher_id]', '0')";
        
        $result_queue = mysqli_query($mysqli, $add_queue);

        $message = '<strong><h2 style="background-color:green;"> ' . $insert_row['first_name'] . ' added to QUEUE list!</h2></strong><br>';
    } else {
        // Do other stuff...
        $message = '<strong><h2 style="background-color:red;"> ' . $insert_row['first_name'] . ' is already in QUEUE list!</h2></strong><br>';
    }

    echo $message;
    echo $output;
    
}
    else{
        echo "<h2 class=text-danger>Student data not found</h2>";
    }
