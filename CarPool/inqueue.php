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

?>
<!DOCTYPE html>
<html>
<style>
    .selected-row {
        background-color: gray !important;
    }
</style>

<head>
    <title>In Queue - CarPool Management</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/dark.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<script>
    function logoutAndClearLocalStorage() {
        // Clear localStorage here
        localStorage.clear();
        // Redirect to the logout page
        document.location.href = 'logout.php';
    }
</script>

<script>
    // Function to toggle checkbox state and update database
    function toggleCheckbox(queueId, studentId, el) {
        var isChecked = el.checked ? 1 : 0;
        var url = 'inqueue-actions.php?action=toggleCheckbox&queue_id=' + queueId + '&student_id=' + studentId + '&checkbox_state=' + isChecked;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                //console.log('Checkbox state updated:', data);
            }).catch(error => {
                // checkbox unchecked
                //console.log('Checkbox state updated:', error);
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

    // Function to toggle row highlight and store selection in localStorage
    function toggleHighlight(rowId) {
        var row = document.getElementById(rowId);
        if (row) {
            // Check if the row is for student ID 999
            if (row.style.backgroundColor === 'red') {
                return; // Don't toggle if it's a red-highlighted row
            }
            row.classList.toggle("selected-row");
            var isSelected = row.classList.contains("selected-row");
            if (isSelected) {
                localStorage.setItem(rowId, "selected");
            } else {
                localStorage.removeItem(rowId);
            }
        } else {
            //console.log('Row element not found for ID:', rowId);
        }
    }

    //Function to restore selected rows on page load
    function restoreSelectedRows() {
        var selectedRows = Object.keys(localStorage);
        //console.log(selectedRows);
        selectedRows.forEach(function(rowId) {
            var row = document.getElementById(rowId);
            //console.log(row);
            if (row) {
                row.classList.add("selected-row");
                //console.log("added to row", rowId);
            } else {
                //console.log('Row element not found for ID:', rowId);
            }
        });
    }
</script>

<script>
    // Function to load table data via AJAX
    function loadTableData() {
        fetch('/ajax/inqueue_data.php') // Fetch both data and count in a single request
            .then(response => response.text()) // Parse the response as plain text
            .then(data => {
                const [tableData, count] = data.split("|||"); // Split the response into table data and count

                // Update table data
                document.querySelector("#queueTable tbody").innerHTML = tableData;

                // Update the count element
                const countElement = document.querySelector("#queueCountNumber");
                if (countElement) {
                    countElement.innerText = count;
                }
                // Restore selected rows
                restoreSelectedRows();
            })
            .catch(error => {
                console.error('Error loading data:', error);
            });

    }
    // Call loadTableData initially and set an interval for auto-refresh
    loadTableData();
    setInterval(loadTableData, 3000); // Refresh every 3 seconds
</script>

<body>
    <center>
        <a href="index.php"><img src="img/txlogo.png" alt="Thanksgiving Elementary" title="Home"></a>
        <h1><a href="index.php"> Queue List - CarPool Management</a></h1>
        <br>
        <?php if (isset($user)) : ?>
            <p>Hello, Welcome : <?= htmlspecialchars($user["name"]) ?></p>
            <br>
            <!-- display a count of the number of students in the queue -->
            <h2 id="queueCount" style="text-align: center;">Total in Queue: <span id="queueCountNumber">0</span></h2>
            </br>
            <div class="container">

                <table class="table" id="queueTable">
                    <!-- Table body will be updated via AJAX -->
                    <tbody>
                    </tbody>
                </table>
            </div>
            <br>
            <br>
            <br>
            <input type="button" value="Log out" onClick="logoutAndClearLocalStorage()" />

        <?php else : ?>
            <p><a href="login.php">Log in</a> <!--or <a href="signup.html">Sign up</a>--></p>
        <?php endif; ?>
    </center>

</body>
<footer>
    <p><?php include "includes/footer.php"; ?></p>
</footer>

</html>