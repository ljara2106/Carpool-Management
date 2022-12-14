<?php

session_start();

if (isset($_SESSION["user_id"])) {
    
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <center>
    <h1>Home - CarPool Management</h1>
    <a href="index.php"><img src="/CarPool/img/txlogo.png" alt="Thanksgiving Elementary" ></a>
    <br>
    <br>
</br>
    <?php if (isset($user)): ?>
        
        <p>Hello, Welcome :  <?= htmlspecialchars($user["name"]) ?></p>
        <br>

        <button> <a href="monitorview.php">Monitor View</a> </button>    
        <button> <a href="inqueue.php">In Queue</a> </button>   
        <button> <a href="search.php">Search Student</a> </button>






        <br>
        <br>
        <br>
        <p><a href="logout.php">Log out</a></p>
        

    

    <?php else: ?>
        
        <p><a href="login.php">Log in</a> or <a href="signup.html">sign up</a></p>
        
    <?php endif; ?>















    </center>
</body>
</html>
    
    
    
    
    
    
    
    
    
    
    