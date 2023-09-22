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

class actions
{

    private $db;

    function __construct($mysqli)
    {
        $this->db = $mysqli;
    }

    // Add this function to move student to picked up
    public function movetoPickedup($student_id)
    {
        try {
            $student_id = $this->db->real_escape_string($student_id);

            $check_pickedup = $this->db->query("SELECT * FROM `inqueue` WHERE student_id = '$student_id' AND DATE(datetime_added) = CURDATE()");

            if ($check_pickedup->num_rows > 0) {
                $row = $check_pickedup->fetch_assoc();

                $student_id = $row['student_id'];
                $first_name = $this->db->real_escape_string($row['first_name']);
                $last_name = $this->db->real_escape_string($row['last_name']);
                $grade = $row['grade'];
                $teacher_name = $this->db->real_escape_string($row['teacher_name']);
                $teacher_id = $row['teacher_id'];

                // Insert into pickedup table
                $add_pickedup = "INSERT INTO `pickedup` (`student_id`, `first_name`, `last_name`, `grade`, `teacher_name`, `teacher_id`) 
                                 VALUES ('$student_id', '$first_name', '$last_name', $grade, '$teacher_name', $teacher_id)";

                $result_pickedup = $this->db->query($add_pickedup);

                // Update inqueue table
                $update_queue = "UPDATE `inqueue` SET picked_up = 1 WHERE student_id = '$student_id' AND DATE(datetime_added) = CURDATE()";

                $result_queue_update = $this->db->query($update_queue);

                $host = $_SERVER['HTTP_HOST'];
                header('Location: http://' . $host . '/inqueue.php');
                exit; //real location: header('Location: http://'.$host.'/carpool/inqueue.php');exit;
            } else {
                // do other stuff...
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }

        $this->db->close();
    }


    // Function to move all students to picked up
    public function moveAll()
    {
        try {
            $check_pickedup =  $this->db->query("SELECT * FROM `inqueue` WHERE DATE(datetime_added) = CURDATE() AND picked_up = 0 ");

            if ($check_pickedup->num_rows > 0) {
                while ($row = $check_pickedup->fetch_assoc()) {
                    $student_id = $row['student_id'];
                    $first_name = $this->db->real_escape_string($row['first_name']);
                    $last_name = $this->db->real_escape_string($row['last_name']);
                    $grade = $row['grade'];
                    $teacher_name = $this->db->real_escape_string($row['teacher_name']);
                    $teacher_id = $row['teacher_id'];

                    // Insert into pickedup table
                    $add_pickedup = "INSERT INTO `pickedup` (`student_id`, `first_name`, `last_name`, `grade`, `teacher_name`, `teacher_id`) 
                                     VALUES ('$student_id', '$first_name', '$last_name', $grade, '$teacher_name', $teacher_id)";

                    $result_pickedup = $this->db->query($add_pickedup);

                    // Update inqueue table
                    $update_queue = "UPDATE `inqueue` SET picked_up = 1 WHERE student_id = '$student_id' AND DATE(datetime_added) = CURDATE()";

                    $result_queue_update = $this->db->query($update_queue);
                }

                $host = $_SERVER['HTTP_HOST'];
                header('Location: http://' . $host . '/inqueue.php');
                exit; //real location = header('Location: http://'.$host.'/carpool/inqueue.php');exit; 

            } else {
                // do other stuff...
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }

        $this->db->close();
    }

    // Function to update checkbox state
    public function updateCheckboxState($queueId, $checkboxState, $studentId)
    {
        try {
            $updateSql = "UPDATE inqueue SET checkbox_state = {$checkboxState} WHERE queue_id = {$queueId} AND student_id = {$studentId}";
            $this->db->query($updateSql);

            // return json success true
            return json_encode(['success' => true]);
        } catch (Exception $e) {
            // echo $e->getMessage();
            return json_encode(['success' => false]);
        }
    }

    // Function to remove student row from queue by deleting from database by queue_id
    public function removeStudent($queue_id)
    {
        try {
            $queue_id = $this->db->real_escape_string($queue_id);
    
            $stmt = $this->db->prepare("DELETE FROM `inqueue` WHERE queue_id = ?");
            $stmt->bind_param("i", $queue_id);
            $stmt->execute();
    
            $host = $_SERVER['HTTP_HOST'];
            header('Location: http://' . $host . '/inqueue.php');
            exit;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    
        $this->db->close();
    }

}

$action = $_GET['action'];
$student_id = $_GET['student_id'];
$queue_id = $_GET['queue_id']; // Add this line to get queue_id

$actions = new actions($mysqli);

if ($action === 'movetoPickedup') {
    $actions->movetoPickedup($student_id);
} else if ($action === 'moveAll') {
    $actions->moveAll();
} else if ($action === 'toggleCheckbox') { // Add checkbox state toggle action
    $checkbox_state = $_GET['checkbox_state'];
    echo $actions->updateCheckboxState($queue_id, $checkbox_state, $student_id);
} else if ($action === 'removeStudent') { // Add remove student action
    $actions->removeStudent($queue_id);
}