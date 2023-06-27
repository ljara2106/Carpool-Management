<?php

session_start();

if (isset($_SESSION["user_id"])) {
    
    $mysqli = require __DIR__ . "/dbconfig/database.php";

    $stmt = $mysqli->prepare("SELECT * FROM user WHERE id = ?");
    
    // "s" indicates that the parameter is a string, replace with "i" for integer, "d" for double, "b" for blob
    $stmt->bind_param("s", $_SESSION["user_id"]);

    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $user = $result->fetch_assoc();
}


?>


<!DOCTYPE html>
<html>
<style>

    a {
    font-size: 25px; /* example size, can be any size, in px, em, rem, % */
    }

</style>


<head>
    <title>Home - CarPool Management</title>
    <meta charset="UTF-8">
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">-->
    <link rel="stylesheet" href="css/dark.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <center>
    <a href="index.php"><img src="img/txlogo.png" alt="Thanksgiving Elementary" title="Home" ></a>
    <h1>Home - CarPool Management</h1>
    <br>
    <br>
    
    <?php if (isset($user)): ?>
        
        <p>Hello, Welcome :  <?= htmlspecialchars($user["name"]) ?></p>
        <br>

        <?php
            // Check if the 'teacher_id' key exists in the user array
            // and if its value is not zero
            if(isset($user['teacher_id']) && $user['teacher_id'] != 0) {
                // If user is a teacher, display only monitorview-classroom.php
        ?>
                <button> <a href="monitorview-classroom.php">Monitor View (By Teacher)</a> </button>  <br>

        <?php
            } else {
                // If user is not a teacher, display all other .php pages
        ?>
                <button> <a href="monitorview-big.php">Monitor View</a> </button> <br>  
                <button> <a href="inqueue.php">In Queue</a> </button>   <br>
                <button> <a href="search-ajax.php">Search Student</a> </button> <br>

        <?php
            }
        ?>

        <br>
        <br>
        <br>
        <br>
        <br>
        <p><a href="logout.php">Log out</a></p>
        

    

    <?php else: ?>
        
        <p><a href="login.php">Log in</a> or <a href="signup.html">Sign up</a></p>
        
    <?php endif; ?>




    </center>
</body>

<footer>
    <p>Copyright &copy AppCybernetica.com</a> <?php echo date("Y"); ?></p>
</footer>

</html>
    
    
    
    
    
    
    
    
    
    
    