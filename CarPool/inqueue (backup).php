<?php

session_start();

if (isset($_SESSION["user_id"])) {
    // Include the database configuration
    require __DIR__ . "/dbconfig/database.php"; // Adjust the path as needed
    $sql = "SELECT * FROM user WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

$page = $_SERVER['PHP_SELF'];
$sec = "3";

?>
<!DOCTYPE html>
<html>
<style>
    .selected-row {
        background-color: gray !important;
    }
</style>
<script>

    // Function to toggle row highlight and store selection in localStorage
    function toggleHighlight(rowId) {
        var row = document.getElementById(rowId);
        if (row) {
            row.classList.toggle("selected-row");
            var isSelected = row.classList.contains("selected-row");
            if (isSelected) {
                localStorage.setItem(rowId, "selected");
            } else {
                localStorage.removeItem(rowId);
            }
        }
    }

    // Function to restore selected rows on page load
    function restoreSelectedRows() {
        var selectedRows = Object.keys(localStorage);
        selectedRows.forEach(function(rowId) {
            var row = document.getElementById(rowId);
            if (row) {
                row.classList.add("selected-row");
            }
        });
    }

    // Call the restoreSelectedRows function on page load
    document.addEventListener("DOMContentLoaded", restoreSelectedRows);



    // Function to toggle checkbox state and update database
    function toggleCheckbox(queueId, studentId, el) {

        var isChecked = el.checked ? 1 : 0;
        var url = 'inqueue-actions.php?action=toggleCheckbox&queue_id=' + queueId + '&student_id=' + studentId + '&checkbox_state=' + isChecked;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('Checkbox state updated:', data);
            }).catch(error => {
                // checkbox unchecked
                console.log('Checkbox state updated:', error);
                el.checked = false;
            })

    }

    // Function to confirm removal of student from queue
    function confirmRemove(queueId, studentId) {
        var confirmation = confirm("Are you sure you want to remove Student ID " + studentId + " from queue ID " + queueId + "?" + "\n\n" + "Press OK to confirm.");
        if (confirmation) {
            // If user confirms, redirect to the removal action
            window.location.href = 'inqueue-actions.php?action=removeStudent&queue_id=' + queueId;
        } else {
            // If user cancels, do nothing
            return false;
        }
    }
</script>

<head>
    <title>In Queue - CarPool Management</title>
    <meta charset="UTF-8">
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">-->
    <link rel="stylesheet" href="css/dark.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="<?php echo $sec ?>;URL='<?php echo $page ?>'">
</head>

<body>


    <center>
        <a href="index.php"><img src="img/txlogo.png" alt="Thanksgiving Elementary" title="Home"></a>
        <h1><a href="index.php"> Queue List - CarPool Management</a></h1>
        <br>
        <?php if (isset($user)) : ?>

            <p>Hello, Welcome : <?= htmlspecialchars($user["name"]) ?></p>
            <br>

            <!-- display queue total list count -->
            <?php
            $sql = "SELECT * FROM `inqueue` WHERE DATE(`datetime_added`) = CURDATE() and picked_up=0 and student_id != 999";
            $results = mysqli_query($mysqli, $sql);
            $count = mysqli_num_rows($results);
            echo "<h2>Total in Queue: $count</h2>";
            ?>
            </br>


            <div class="container">
                <table class="table"> <!-- border="1" Added the border attribute -->
                    <?php

                    $sql = "SELECT * FROM `inqueue` WHERE DATE(`datetime_added`) = CURDATE() AND `picked_up` = 0 LIMIT 52";

                    $results = mysqli_query($mysqli, $sql);

                    if ($result) {
                        if (mysqli_num_rows($results) > 0) {
                            echo '<thead>
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
                    '; //<!-- Added the tbody tag -->


                            while ($row = mysqli_fetch_assoc($results)) {
                                $highlightStyle = ($row['student_id'] == 999) ? 'background-color: red;' : '';
                                $rowId = 'row_' . $row['queue_id'];
                                $isChecked = ($row['checkbox_state'] == 1) ? 'checked' : '';
                                echo '<tbody id="' . $rowId . '">
                    <tr onclick="toggleHighlight(\'' . $rowId . '\')" style="' . $highlightStyle . '">
                    <td><input type="checkbox" onclick="toggleCheckbox(' . $row['queue_id'] . ', ' . $row['student_id'] . ', this)" ' . $isChecked . '></td> 
  
                    <td>' . $row['queue_id'] . '</td>
                    <td>' . $row['student_id'] . '</td>
                    <td>' . $row['first_name'] . '</td>
                    <td>' . $row['last_name'] . '</td>
                    <td>' . $row['grade'] . '</td>
                    <td>' . $row['teacher_name'] . '</td>
                   
                    <td>';

                                if ($row['student_id'] != 999) {
                                    echo '<a href="inqueue-actions.php?action=movetoPickedup&student_id=' . $row['student_id'] . '">✔️ Sent</a>';
                                }
                                echo '<td style="text-align: center;"><a href="#" onclick="confirmRemove(' . $row['queue_id'] . ', ' . $row['student_id'] . ')">🗑️</a></td>';


                                echo '</td></tr>';
                            }
                            echo '</tbody>';
                        } else {
                            echo '<h2 class=text-danger>No student data found, please add student to the queue.</h2>';
                        }
                    }


                    ?>

                </table>
                <!-- to set all as picked up function -->
                <!--<p><a href="inqueue-actions.php?action=moveAll">Set ALL as picked up</a></p>-->

            </div>

            <br>
            <br>
            <br>
            <!--<p><a href="logout.php">Log out</a></p>-->
            <!--<button><font size="3" <a href="logout.php">Log out</a></font></button>-->
            <input type="button" value="Log out" onClick="logoutAndClearLocalStorage()" />

            <script>
                function logoutAndClearLocalStorage() {
                    // Clear localStorage here
                    localStorage.clear();

                    // Redirect to the logout page
                    document.location.href = 'logout.php';
                }
            </script>

        <?php else : ?>

            <p><a href="login.php">Log in</a> or <a href="signup.html">Sign up</a></p>

        <?php endif; ?>

    </center>

    <script>
        restoreSelectedRows();
    </script>

</body>

<footer>
    <p><?php include "includes/footer.php"; ?></p>
</footer>

</html>