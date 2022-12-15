<?php

session_start();

if (isset($_SESSION["user_id"])) {
    
    $mysqli = require __DIR__ . "/dbconfig/database.php";
    
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
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">-->
    <link rel="stylesheet" href="css/dark.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <script
      src="https://code.jquery.com/jquery-3.6.0.min.js"
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
      crossorigin="anonymous"
    ></script>  
    

</head>
<body>


    <center>
    <h1><a href = "index.php">Search Student - CarPool Management</a></h1>
    <br>
    <a href="index.php"><img src="img/txlogo.png" alt="Thanksgiving Elementary" ></a>

</br>
    <?php if (isset($user)): ?>
        
        <p>Hello, Welcome :  <?= htmlspecialchars($user["name"]) ?></p>
        <br>

       <script src="js/html5-qrcode.min.js"></script>


       <div style="width: 300px" id="reader"></div>

       <script>
            var key_map = {};
            function search_student(search) {
                //console.log("search", search);
                if( search in key_map )
                    return;

                key_map[search] = true;

                setTimeout(function() {
                    delete key_map[search];
                }, 300);
                $.ajax({
                        url:'searchaction.php',
                        method:'post',
                        data:{query:search},
                        success:function(response){
                            $("#table-container").html(response);
                        }
                    });
            }  

            function onScanSuccess(decodedText, decodedResult) {
                // Handle on success condition with the decoded text or result.
                console.log(`Scan result: ${decodedText}`, decodedResult);
                document.getElementById("search").value=decodedText;
                search_student(decodedText);
            }

            function onScanError(errorMessage) {
                // handle on error condition, with error message
                console.log(errorMessage);
            }

            var html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", { fps: 1, qrbox: 200});
            html5QrcodeScanner.render(onScanSuccess, onScanError);


       </script>

            <br>

       <script>
        function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
        return true;
        }    
        </script>


        <script type="text/javascript">   
                   
            $(document).ready(function(){
                
                //console.log("init");
                $("#search").keyup(function(){
                    var search = $(this).val();                  
                
                    search_student(search);
                
                });
            });

        </script>

        <div class="container">
        <form method="POST">
            <input type="text" placeholder="Search student ID" id="search" name="search" onkeypress="return isNumberKey(event)">

            <!--<button name="submit">Search</button>-->
            
        </form>

        <br>
        <br>
        <br>
        <div class="container" id="table-container">       
            <table class ="table" id="table-data">
                <thead>
                    <tr>
                    <th><strong>Student ID</strong></th>
                    <th><strong>First Name</strong></th>
                    <th><strong>Last Name</strong></th>
                    <th><strong>Grade</strong></strig></th>
                    <th><strong>Teacher</strong></th>
                    </tr>
                    </thead>
                <tbody>
                       
                </tbody>                

            </table>
        </div>
       



        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <p><a href="logout.php">Log out</a></p>
        <!--<input type="button" value="Log out" onClick="document.location.href='logout.php'" />-->
        

    

    <?php else: ?>
        
        <p><a href="login.php">Log in</a> or <a href="signup.html">sign up</a></p>
        
    <?php endif; ?>















    </center>
</body>

<footer>
    <p><?php include "includes/footer.php";?></p>
</footer>


</html>
    
    
    
    
    
    
    
    
    
    
    