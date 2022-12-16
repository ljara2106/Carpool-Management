<?php 
$is_invalid = false;
$error_message = '';
if(isset($_POST['g-recaptcha-response'])){
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $secret = '6LdtiwwUAAAAAAfXRgEh9zg37goVaeuRL1btdONl';
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret .  '&remoteip' . $ip . '&response=' . $_POST['g-recaptcha-response']);
    $responseData = json_decode($verifyResponse);
    if ($responseData->success) {

        $mysqli = require __DIR__ . "/dbconfig/database.php";
    
        $sql = sprintf("SELECT * FROM user
                        WHERE email = '%s'",
                       $mysqli->real_escape_string($_POST["email"]));
        
        $result = $mysqli->query($sql);
        
        $user = $result->fetch_assoc();
        
        if ($user) {
            
            if (password_verify($_POST["password"], $user["password_hash"])) {
                
                session_start();
                
                session_regenerate_id();
                
                $_SESSION["user_id"] = $user["id"];
                
                header("Location: index.php");
                exit;
            }

        }
        $is_invalid = true;
    }

    else{
        $error_message = 'Please check reCAPTCHA';        
    }
}

    

?> 
