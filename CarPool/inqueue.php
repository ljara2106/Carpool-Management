<?php

session_start();

if (isset($_SESSION["user_id"])) {
    
    $mysqli = require __DIR__ . "/dbconfig/database.php";
    
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
}
$page = $_SERVER['PHP_SELF'];
$sec = "5";

?>
<!DOCTYPE html>
<html>
<head>
    <title>In Queue - CarPool Management</title>
    <meta charset="UTF-8">
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">-->
    <link rel="stylesheet" href="css/dark.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>


    <center>
    <h1><a href = "index.php"> Queue List - CarPool Management</a></h1>
    <br>
    <a href="index.php"><img src="img/txlogo.png" alt="Thanksgiving Elementary" ></a>
    
</br>
    <?php if (isset($user)): ?>
        
        <p>Hello, Welcome :  <?= htmlspecialchars($user["name"]) ?></p>
        <br>
        <br>
        <br>
        <br>


        <div class="container">
        <table class ="table">
            <?php
            
                 
                 $sql="SELECT * FROM `inqueue` WHERE DATE(datetime_added) = CURDATE() and picked_up=0  ";
            
                 $results=mysqli_query($mysqli,$sql);

                if($result){
                   if(mysqli_num_rows($results)>0){
                    echo '<thead>
                    <tr>
                    
                    <th><strong>Student ID</strong></th>
                    <th><strong>First Name</strong></th>
                    <th><strong>Last Name</strong></th>
                    <th><strong>Grade</strong></strig></th>
                    <th><strong>Teacher</strong></th>
                    <th><strong>Added @</strong></th>
                    <th><strong>Action</strong></th>
                    </tr>
                    </thead>
                    ';

                    while($row=mysqli_fetch_assoc($results)){
                    echo '<tbody>
                    <tr>
                    
                    <td>'.$row['student_id'].'</td>
                    <td>'.$row['first_name'].'</td>
                    <td>'.$row['last_name'].'</td>
                    <td>'.$row['grade'].'</td>
                    <td>'.$row['teacher_name'].'</td>
                    <td>'.$row['datetime_added'].'</td>
                    <td><a href="actions.php?action=movetoPickedup&student_id='.$row['student_id'].'">✔️ Sent</a></td>
                    </tr>
                    </tbody>';
                    }
                   }

                   else{
                       echo '<h2 class=text-danger>No student data found, please add student to the queue.</h2>'; 
                   }

                }
                

            ?>
                 
        </table>
        <!-- to set all as picked up function -->      
        <!--<p><a href="actions.php?action=moveAll">Set ALL as picked up</a></p>-->

        </div>



   

        <br>
        <br>
        <br>
        <!--<p><a href="logout.php">Log out</a></p>-->
        <!--<button><font size="3" <a href="logout.php">Log out</a></font></button>-->
        <input type="button" value="Log out" onClick="document.location.href='logout.php'" />
        

    

    <?php else: ?>
        
        <p><a href="login.php">Log in</a> or <a href="signup.html">sign up</a></p>
        
    <?php endif; ?>


    </center>
</body>

<footer>
    <p><?php include "includes/footer.php";?></p>
</footer>

</html>
    
    
    
    
    
    
    
    
    
    
    