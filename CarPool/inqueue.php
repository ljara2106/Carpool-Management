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
<head>
    <title>In Queue - CarPool Management</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>


    <center>
    <h1><a href = "index.php">In Queue - CarPool Management</a></h1>
</br>
    <?php if (isset($user)): ?>
        
        <p>Hello, Welcome :  <?= htmlspecialchars($user["name"]) ?></p>
        <br>

        <!--<button><font size="6" <a href="search.php">Search Student</a></font>   </button>-->

        <br>
        <br>
        <br>


        <div class="container">
        <table class ="table">
            <?php
            
                 
                 $sql="SELECT * FROM `inqueue` WHERE DATE(datetime_added) = CURDATE() ";
            
                 $results=mysqli_query($mysqli,$sql);

                if($result){
                   if(mysqli_num_rows($results)>0){
                    echo '<thead>
                    <tr>
                    <th><strong>Queue ID</strong></th>
                    <th><strong>Student ID</strong></th>
                    <th><strong>First Name</strong></th>
                    <th><strong>Last Name</strong></th>
                    <th><strong>Grade</strong></strig></th>
                    <th><strong>Teacher Name</strong></th>
                    <th><strong>Date / Time</strong></th>
                    </tr>
                    </thead>
                    ';

                    while($row=mysqli_fetch_assoc($results)){
                    echo '<tbody>
                    <tr>
                    <td>'.$row['queue_id'].'</td>
                    <td>'.$row['student_id'].'</td>
                    <td>'.$row['first_name'].'</td>
                    <td>'.$row['last_name'].'</td>
                    <td>'.$row['grade'].'</td>
                    <td>'.$row['teacher_name'].'</td>
                    <td>'.$row['datetime_added'].'</td>
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


        </div>



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
    
    
    
    
    
    
    
    
    
    
    