<?php
session_start();

if (isset($_SESSION["user_id"])) {
    // Include the database configuration
    require __DIR__ . "/../dbconfig/database.php"; // Adjust the path as needed
    $user_id = $_SESSION["user_id"];

    if (is_numeric($user_id)) { // Check if $user_id is numeric (assuming it's an integer)
        // Fetch the user's teacher_id if available
        $teacher_id = 0; // Default value
        if ($user_id != 0) {
            $sql_user = "SELECT teacher_id FROM user WHERE id = ?";
            $stmt_user = $mysqli->prepare($sql_user);
            $stmt_user->bind_param("i", $user_id);
            $stmt_user->execute();
            $result_user = $stmt_user->get_result();

            if ($result_user && $result_user->num_rows > 0) {
                $user_data = $result_user->fetch_assoc();
                $teacher_id = $user_data['teacher_id'];
            }
            $stmt_user->close();
        }

        $sql = "SELECT * FROM `inqueue` WHERE DATE(datetime_added) = CURDATE() AND picked_up = 0 AND teacher_id = ? LIMIT 20";

        // Debugging: Output the generated SQL query for inspection
        //echo "SQL Query: " . $sql . "<br>";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        $results = $stmt->get_result();

        if ($results) {
            if ($results->num_rows > 0) {
                echo '<thead>
                    <tr>
                        <th><strong>First Name</strong></th>
                        <th><strong>Last Name</strong></th>
                        <th><strong>Grade</strong></th>
                        <th><strong>Teacher</strong></th>
                        <th><strong>Added @</strong></th>
                    </tr>
                </thead>';

                echo '<tbody>';

                while ($row = $results->fetch_assoc()) {
                    $datetime_added = date("h:i:s A", strtotime($row['datetime_added']));
                    echo '<tr>
                        <td>' . htmlspecialchars($row['first_name']) . '</td>
                        <td>' . htmlspecialchars($row['last_name']) . '</td>
                        <td>' . htmlspecialchars($row['grade']) . '</td>
                        <td>' . htmlspecialchars($row['teacher_name']) . '</td>
                        <td>' . htmlspecialchars($datetime_added) . '</td>
                        <td style="font-size: 20px; color:green"> ⌚🚗 Go! </td>
                    </tr>';
                }

                echo '</tbody>';
            } else {
                echo '<center><h2 class=text-danger>No student data found, please add student to the queue.</h2></center>'; 
            }
        } else {
            echo '<tr><td colspan="6">Error executing SQL query: ' . htmlspecialchars(mysqli_error($mysqli)) . '</td></tr>';
        }
    } else {
        echo '<tr><td colspan="6">Invalid user ID.</td></tr>';
    }
} else {
    echo "<center><h2 class='text-danger'>You are not authorized to access this page.</h2></center>";
}
?>
