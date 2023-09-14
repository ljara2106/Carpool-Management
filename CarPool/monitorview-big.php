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
$sec = 3;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Monitor - CarPool Management</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/dark.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <center>
        <a href="index.php"><img src="img/txlogo.png" alt="Thanksgiving Elementary" title="Home"></a>
        <h1><a href="index.php">Monitor - CarPool Management</a></h1>
        <br>

        <?php if (isset($user)) : ?>
            <p>Hello, Welcome: <?= htmlspecialchars($user["name"]) ?></p>
            <br>
            <br>
            <div class="container">
                <table id="studentTable" class="table">
                    <!-- Table content will be updated through AJAX -->
                </table>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <input type="button" value="Log out" onClick="document.location.href='logout.php'" />
        <?php else : ?>
            <p><a href="login.php">Log in</a> or <a href="signup.html">Sign up</a></p>
        <?php endif; ?>
    </center>

    <script>
        // Function to load and refresh table data using AJAX
        function refreshTable() {
            $.ajax({
                url: "/ajax/monitorview_big_data.php", // Create a separate PHP file for handling the AJAX request
                success: function(data) {
                    $('#studentTable').html(data); // Update the table with new data
                }
            });
        }

        // Automatically refresh the table every 3 seconds
        setInterval(refreshTable, <?php echo $sec * 1000 ?>); // Convert seconds to milliseconds
        refreshTable(); // Initial table load
    </script>
</body>
<footer>
    <p><?php include "includes/footer.php"; ?></p>
</footer>

</html>
