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

$page = $_SERVER['PHP_SELF'];
$sec = 3;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Monitor Classroom 📚 - CarPool Management 🚦</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/dark.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <noscript>
        <p style="text-align: center;">Please enable JavaScript in your browser before using this website.</p>
    </noscript>

    <center>
        <a href="index.php"><img src="img/txlogo.png" alt="Thanksgiving Elementary"></a>
        <h1><a href="index.php">Monitor - CarPool Management</a></h1>

        <br>
        <?php if (isset($user)) : ?>
            <p>Hello, Welcome: <?= htmlspecialchars($user["name"]) ?></p>
            <br>
            <br>

            <div class="container">
                <table id="student-table" class="table">
                    <!-- Table content will be loaded dynamically -->
                </table>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <input type="button" value="Log out" onClick="document.location.href='logout.php'" />

            <script>
                // Function to load table data using AJAX
                function loadTableData() {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("student-table").innerHTML = this.responseText;
                        }
                    };
                    xhttp.open("GET", "/ajax/monitorview_classroom_data.php", true);
                    xhttp.send();
                }

                // Load table data initially and refresh every 3 seconds
                loadTableData();
                setInterval(loadTableData, <?php echo $sec * 1000 ?>);
            </script>

        <?php else : ?>
            <p><a href="login.php">Log in</a> <!--or <a href="signup.html">Sign up</a>--></p>
        <?php endif; ?>
    </center>
</body>
<footer>
    <p><?php include "includes/footer.php"; ?></p>
</footer>

</html>