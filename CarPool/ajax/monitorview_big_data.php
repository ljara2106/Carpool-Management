<?php
session_start();

if (isset($_SESSION["user_id"])) {

    require __DIR__ . "/../dbconfig/database.php"; // Database connection configuration file

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM user WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    $sql = "SELECT * FROM `inqueue` WHERE DATE(datetime_added) = CURDATE() AND picked_up = 0 LIMIT 75";
    $results = mysqli_query($mysqli, $sql);

    if ($results && mysqli_num_rows($results) > 0) {
        echo '<thead>
            <tr>
                <th style="font-size: 20px;"><strong>First Name</strong></th>
                <th style="font-size: 20px;"><strong>Last Name</strong></th>
                <th style="font-size: 20px;"><strong>Grade</strong></th>
                <th style="font-size: 20px;"><strong>Teacher</strong></th>
                <th style="font-size: 20px;"><strong>Added @</strong></th>
            </tr>
        </thead>';

        echo '<tbody>';

        while ($row = mysqli_fetch_assoc($results)) {
            $highlightStyle = ($row['student_id'] == 999) ? 'background-color: red;' : '';
            echo '<tr style="' . htmlspecialchars($highlightStyle) . '">';
            echo '<td style="font-size: 20px;">' . htmlspecialchars($row['first_name']) . '</td>';
            echo '<td style="font-size: 20px;">' . htmlspecialchars($row['last_name']) . '</td>';
            echo '<td style="font-size: 20px;">' . htmlspecialchars($row['grade']) . '</td>';
            echo '<td style="font-size: 20px;">' . htmlspecialchars($row['teacher_name']) . '</td>';
            $datetime_added = date("h:i:s A", strtotime($row['datetime_added']));
            echo '<td>' . htmlspecialchars($datetime_added) . '</td>';

            echo '<td style="font-size: 20px; color:green"> ⌚🚗 Go! </td>';
            echo '</tr>';
        }

        echo '</tbody>';
    } else {
        echo '<center><h2 class="text-danger">No student data found, please add students to the queue.</h2><center>';
    }

    // Close the database connection
    $mysqli->close();
} else {
    echo "<center><h2 class='text-danger'>You are not authorized to access this page.</h2></center>";
}
