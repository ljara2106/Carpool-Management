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



        <div class="container">

        <input type="text" class="form-control" id="search" autocomplete="off" placeholder="Search student id..."

        </div>

        <div id="searchresult"> </div>




        <script>
        function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
        return true;
        }    
        </script>


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function(){

                $("#search").keyup(function(){
                    var input = $(this).val();
                    //alert(input);
                    if(input != ""){
                        $.ajax({

                            url:"searchajax.php",
                            method: "POST",
                            data: {input:input},

                            success:function(data){
                                $("#searchresult").html(data);
                            }
                        })

                    }else{
                        $("#searchresult").css("display","none");
                    }

                });
            });


        </script>




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
    
    
    
    
    
    
    
    
    
    
    