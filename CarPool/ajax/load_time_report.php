<?php
// This is a separate PHP file for generating the time report table content via AJAX

// Your MySQL database connection code goes here
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

    $today = date("Y-m-d"); // Get today's date in the format used in the database

    // Query to fetch data for the past 7 days and today
    $query = "SELECT
        DATE(datetime_added) AS date,
        DAYNAME(datetime_added) AS day,
        DATE_FORMAT(MIN(datetime_added), '%h:%i:%s %p') AS earliest_time,
        DATE_FORMAT(MAX(datetime_added), '%h:%i:%s %p') AS latest_time,
        TIMEDIFF(MAX(datetime_added), MIN(datetime_added)) AS time_difference,
        COUNT(CASE WHEN student_id != 999 THEN 1 ELSE NULL END) AS scanned
        FROM inqueue
        WHERE DATE(datetime_added) >= CURDATE() - INTERVAL 8 DAY
        AND STUDENT_ID != 999 -- Exclude records where STUDENT_ID is 999
        AND DAYOFWEEK(datetime_added) NOT IN (1, 7) -- Exclude Sunday (1) and Saturday (7)
        GROUP BY DATE(datetime_added)
        ORDER BY date DESC;";

    // Execute the query
    $result = $mysqli->query($query);

    echo "<table border='1'>"; // Start the table with borders
    echo "<tr>";
    echo "<th>Date</th>";
    echo "<th>Day</th>";
    echo "<th>Earliest Time</th>";
    echo "<th>Latest Time</th>";
    echo "<th>Time Difference</th>";
    echo "<th>Scanned #</th>";
    echo "</tr>";

    // Check if there are any results
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rowDate = $row['date'];
            $rowClass = ($rowDate === $today) ? 'highlight' : ''; // Add 'highlight' class for today's date

            echo "<tr class='$rowClass'>";
            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['day']) . "</td>";
            echo "<td>" . htmlspecialchars($row['earliest_time']) . "</td>";
            echo "<td>" . htmlspecialchars($row['latest_time']) . "</td>";
            echo "<td>" . htmlspecialchars($row['time_difference']) . "</td>";
            echo "<td>" . htmlspecialchars($row['scanned']) . "</td>";
            echo "</tr>";
        }
    } else {
        // If no data is available, display a message in a table row
        echo "<tr>";
        echo "<td colspan='6'>No data available</td>";
        echo "</tr>";
    }

    echo "</table>"; // Close the table
    // Close the database connection
    $mysqli->close();
} else {
    echo "<center><h2 class='text-danger'>You are not authorized to access this page.</h2></center>";
}
?>
