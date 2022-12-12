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
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>


    <center>
    <h1><a href = "index.php">Search Student - CarPool Management</a></h1>
</br>
    <?php if (isset($user)): ?>
        
        <p>Hello, Welcome :  <?= htmlspecialchars($user["name"]) ?></p>
        <br>

        <!--<button><font size="6" <a href="search.php">Search Student</a></font>   </button>-->

        <!--// scan the qr code part

          //  scanner.addListener('scan', function(c){
          //  document.getElementById("text").value=c;

       // });-->

       <script src="js/html5-qrcode.min.js"></script>


       <div style="width: 300px" id="reader"></div>

       <script>

            function onScanSuccess(decodedText, decodedResult) {
                // Handle on success condition with the decoded text or result.
                console.log(`Scan result: ${decodedText}`, decodedResult);
                document.getElementById("search").value=decodedText;
            }

            function onScanError(errorMessage) {
                // handle on error condition, with error message
                console.log(errorMessage);
            }

            var html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", { fps: 10, qrbox: 200});
            html5QrcodeScanner.render(onScanSuccess, onScanError);


       </script>


        <br>
        <br>
        <br>


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
            <input type="text" placeholder="Search student ID" id="search" name="search" onkeypress="return isNumberKey(event)">

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



                if($results){
                   if(mysqli_num_rows($results)>0){
                    echo '<thead>
                    <tr>
                   
                    <th><strong>Student ID</strong></th>
                    <th><strong>First Name</strong></th>
                    <th><strong>Last Name</strong></th>
                    <th><strong>Grade</strong></strig></th>
                    <th><strong>Teacher</strong></th>
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
                    </tr>
                    </tbody>';   
                    
                    

                 //add search result to inqueue table
                 $check_queue =  $mysqli->query("SELECT student_id FROM `inqueue`  WHERE student_id = '$search' and DATE(datetime_added) = CURDATE()");
                 if($check_queue->num_rows == 0) {
                      // row not found, do stuff...
                      $add_queue = "insert into `inqueue` ( `student_id`, `first_name`, `last_name`, `grade`, `teacher_name`, `picked_up`) 
                      values ($row[student_id], '$row[first_name]','$row[last_name]', $row[grade],'$row[teacher_name]', '0')"; 
                      $result_queue = mysqli_query($mysqli,$add_queue);
    
                        echo '  <strong><h2 style="background-color:DodgerBlue;"> '  .$row['first_name'].  ' added to QUEUE list!</h2> </strong><br><br><br>';
                   
                 } else {
                     // do other stuff...
                     echo '  <strong><h2 style="background-color:red;"> '  .$row['first_name'].  ' is already in QUEUE list!</h2> </strong><br><br><br>';
                 }
                 $mysqli->close();

                 //var_dump($add_queue);
                // die;         s

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
    
    
    
    
    
    
    
    
    
    
    