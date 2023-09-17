<?php
// Include your database connection code if required
session_start();

if (isset($_SESSION["user_id"])) {
    // Include the database configuration
    require __DIR__ . "/../dbconfig/database.php"; // Adjust the path as needed

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM user WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    $sqlCount = "SELECT COUNT(*) as count FROM `inqueue` WHERE DATE(`datetime_added`) = CURDATE() AND `picked_up` = 0 AND student_id != 999";
    $resultsCount = mysqli_query($mysqli, $sqlCount);

    if ($resultsCount) {
        $row = mysqli_fetch_assoc($resultsCount);
        $count = $row['count'];
    } else {
        $count = 0;
    }

    $sqlData = "SELECT * FROM `inqueue` WHERE DATE(`datetime_added`) = CURDATE() AND `picked_up` = 0 LIMIT 52";
    $resultsData = mysqli_query($mysqli, $sqlData);

    $output = ""; // Initialize the output variable

    if ($resultsData) {
        if (mysqli_num_rows($resultsData) > 0) {
            $output .= '<thead>
                <tr>
                    <th><strong> </strong></th>
                    <th><strong>Queue ID</strong></th>
                    <th><strong>Student ID</strong></th>             
                    <th><strong>First Name</strong></th>
                    <th><strong>Last Name</strong></th>
                    <th><strong>Grade</strong></th>
                    <th><strong>Teacher</strong></th>
                    <th><strong>Action</strong></th>
                    <th><strong>Remove</strong></th>
                </tr>
            </thead>
            <tbody>';

            while ($row = mysqli_fetch_assoc($resultsData)) {
                $highlightStyle = ($row['student_id'] == 999) ? 'background-color: red;' : '';
                $rowId = 'row_' . $row['queue_id'];
                $isChecked = ($row['checkbox_state'] == 1) ? 'checked' : '';
                $output .= '<tr id="' . $rowId . '" onclick="toggleHighlight(\'' . $rowId . '\')" style="' . $highlightStyle . '">
                    <td><input type="checkbox" onclick="toggleCheckbox(' . $row['queue_id'] . ', ' . $row['student_id'] . ', this)" ' . $isChecked . '></td> 
                    <td>' . $row['queue_id'] . '</td>
                    <td>' . $row['student_id'] . '</td>
                    <td>' . $row['first_name'] . '</td>
                    <td>' . $row['last_name'] . '</td>
                    <td>' . $row['grade'] . '</td>
                    <td>' . $row['teacher_name'] . '</td>
                    <td>';
                if ($row['student_id'] != 999) {
                    $output .= '<a href="/../inqueue-actions.php?action=movetoPickedup&student_id=' . $row['student_id'] . '">✔️ Sent</a>';
                }
                $output .= '</td><td style="text-align: center;"><a href="#" onclick="confirmRemove(' . $row['queue_id'] . ', ' . $row['student_id'] . ')">🗑️</a></td></tr>';
            }

            $output .= '</tbody>';
        } else {
            $output .= '<center><h2 class="text-danger">No student data found, please add students to the queue.</h2></center>';
        }
    }

    // Close the database connection
    $mysqli->close();

    // Echo the count and table data separated by "|||"
    echo $output . '|||' . $count;
} else {
    echo "<center><h2 class='text-danger'>You are not authorized to access this page.</h2></center>";
}
?>