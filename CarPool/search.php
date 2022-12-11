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
    <title>Search Student - CarPool Management</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>


    <center>
    <h1><a href = "index.php">Search Student - CarPool Management</a></h1>
</br>
    <?php if (isset($user)): ?>
        
        <p>Hello, Welcome :  <?= htmlspecialchars($user["name"]) ?></p>
        <br>

        <!--<button><font size="6" <a href="search.php">Search Student</a></font>   </button>-->
        <script>
        function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
        return true;
        }    
        </script>

        <div class="container">
        <form method="POST">
            <input type="text" placeholder="Search student ID" name="search" onkeypress="return isNumberKey(event)">

            <button name="submit">Search</button>
            
        </form>

        <br>
        <br>
        <br>
        <div class="container">
        <table class ="table">
            <?php
            if(isset($_POST['submit'])){
                 $search=$_POST['search'];
                 
                 $sql="select * from `students` where student_id='$search'";
            
                 $results=mysqli_query($mysqli,$sql);



                if($result){
                   if(mysqli_num_rows($results)>0){
                    echo '<thead>
                    <tr>
                    <th>ID</th>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Grade</th>
                    <th>Teacher Name</th>
                    </tr>
                    </thead>
                    ';

                    while($row=mysqli_fetch_assoc($results)){
                    echo '<tbody>
                    <tr>
                    <td>'.$row['id'].'</td>
                    <td>'.$row['student_id'].'</td>
                    <td>'.$row['first_name'].'</td>
                    <td>'.$row['last_name'].'</td>
                    <td>'.$row['grade'].'</td>
                    <td>'.$row['teacher_name'].'</td>
                    </tr>
                    </tbody>';   
                    
                    

                 //add search result to inqueue table
                 $add_queue = "insert into `inqueue` (`id`, `student_id`, `first_name`, `last_name`, `grade`, `teacher_name`) 
                 values ($results[id], $results[student_id], $results[first_name], $results[last_name], $results[grade], $results[teacher_name])"; 

                 $result_queue = $mysqli->query($add_queue);
                 
                 if($result_queue==true){
                     echo 'Student added to Queue list';
                }



                }



   


            }


            else{
                echo '<h2 class=text-danger>Student data not found</h2>'; 
            }

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
    
    
    
    
    
    
    
    
    
    
    