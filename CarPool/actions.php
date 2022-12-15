<?php

session_start();

if (isset($_SESSION["user_id"])) {
    
    $mysqli = require __DIR__ . "/dbconfig/database.php";
    
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
}

class actions {

    private $db;

    function __construct($mysqli)
    {
        $this->db = $mysqli;
        
    }

    public function movetoPickedup($student_id) {
        try {    
            $check_pickedup =  $this->db->query("SELECT * FROM `inqueue`  WHERE student_id = '$student_id' and DATE(datetime_added) = CURDATE()");
            
            if (mysqli_num_rows($check_pickedup) > 0) {
                $row=mysqli_fetch_assoc($check_pickedup);
                
                // row not found, do stuff...
                $add_pickedup = "insert into `pickedup` ( `student_id`, `first_name`, `last_name`, `grade`, `teacher_name`) 
                values ($student_id, '$row[first_name]','$row[last_name]', $row[grade],'$row[teacher_name]')"; 
                $result_queue = mysqli_query($this->db,$add_pickedup);

                
                $update_queue = "update  `inqueue` set picked_up = 1 where student_id = $student_id and DATE(datetime_added) = CURDATE()"; 

                $result_queue = mysqli_query($this->db,$update_queue);

                $host = $_SERVER['HTTP_HOST'];
                header('Location: http://'.$host.'/carpool/inqueue.php');exit;
            } else {
                // do other stuff...
            }

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }

        $this->db->close();
    } 



    public function moveAll() {
        try {    
            $check_pickedup =  $this->db->query("SELECT * FROM `inqueue` WHERE DATE(datetime_added) = CURDATE() AND  picked_up=0 ");
            
            if (mysqli_num_rows($check_pickedup) > 0) {
                while ($row=mysqli_fetch_assoc($check_pickedup)){
                // row not found, do stuff...
                $add_pickedup = "insert into `pickedup` ( `student_id`, `first_name`, `last_name`, `grade`, `teacher_name`) 
                values ($row[student_id], '$row[first_name]','$row[last_name]', $row[grade],'$row[teacher_name]')"; 
                $result_queue = mysqli_query($this->db,$add_pickedup);

                
                $update_queue = "update  `inqueue` set picked_up = 1 where student_id = $row[student_id] and DATE(datetime_added) = CURDATE()"; 

                $result_queue = mysqli_query($this->db,$update_queue);

               
                }

                $host = $_SERVER['HTTP_HOST'];
                header('Location: http://'.$host.'/CarPool/inqueue.php');exit;

                
               
            } else {
                // do other stuff...
            }

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }

        $this->db->close();
    } 



}

$action = $_GET['action'];
$student_id = $_GET['student_id'];

$actions = new actions($mysqli);

if ($action === 'movetoPickedup') {
    $actions->movetoPickedup($student_id);
}
else if ($action === 'moveAll'){
    $actions->moveAll();
}


?>