<!DOCTYPE html>
<html>

<head>
    <title>Time Report - CarPool Management</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/dark.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <center>
        <a href="index.php"><img src="img/txlogo.png" alt="Thanksgiving Elementary"></a>
        <h1><a href="index.php">Time Report - CarPool Management</a></h1>
        <br>
        <?php
        session_start();
        if (isset($_SESSION["user_id"])) {
            $mysqli = require __DIR__ . "/dbconfig/database.php";
            $stmt = $mysqli->prepare("SELECT * FROM user WHERE id = ?");
            $stmt->bind_param("s", $_SESSION["user_id"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        }
        ?>
        <?php if (isset($user)) : ?>
            <p>Hello, Welcome : <?= htmlspecialchars($user["name"]) ?></p>
            <br>
            <p>(Time Report for the Past 7 Weekdays and Today)</p>
            <br>
            <!-- HTML container for the table -->
            <div id="timeReportContainer">
                <!-- Content will be loaded here via AJAX -->
            </div>
            <script>
                // Function to load and update the time report table via AJAX
                function loadTimeReport() {
                    $.ajax({
                        url: 'ajax/load_time_report.php', // Create a separate PHP file to handle the AJAX request
                        type: 'GET',
                        dataType: 'html',
                        success: function(data) {
                            $('#timeReportContainer').html(data); // Update the content of the container
                        }
                    });
                }

                // Load the time report initially and then refresh every 3 seconds
                loadTimeReport();
                setInterval(loadTimeReport, 3000); // Refresh every 3 seconds
            </script>

            <br>
            <br>
            <br>

            <input type="button" value="Log out" onClick="document.location.href='logout.php'" />

        <?php else : ?>
            <p><a href="login.php">Log in</a> <!--or <a href="signup.html">Sign up</a>--> </p>
        <?php endif; ?>


    </center>
</body>
<footer>
    <p><?php include "includes/footer.php"; ?></p>
</footer>

</html>