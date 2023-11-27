<?php
$is_invalid = false;
$error_message = '';

if (isset($_POST['cf-turnstile-response'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $secret = 'YOUR_SECRET_KEY';
    
    $postData = [
        'secret' => $secret,
        'remoteip' => $ip,
        'response' => $_POST['cf-turnstile-response'],
    ];

    $ch = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

    $verifyResponse = curl_exec($ch);

    if ($verifyResponse !== false) {
        $responseData = json_decode($verifyResponse);

        if ($responseData && isset($responseData->success) && $responseData->success === true) {
            $mysqli = require __DIR__ . "/dbconfig/database.php";
            $email = $mysqli->real_escape_string($_POST["email"]);
            $sql = sprintf("SELECT * FROM user WHERE email = '%s'", $email);
            $result = $mysqli->query($sql);

            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();

                if (password_verify($_POST["password"], $user["password_hash"])) {
                    session_start();
                    session_regenerate_id();
                    $_SESSION["user_id"] = $user["id"];
                    header("Location: index.php");
                    exit;
                }
            }

            $is_invalid = true;
        } else {
            $error_message = 'Please check reCAPTCHA';
        }
    } else {
        // Handle cURL error
        $error_message = 'cURL error: ' . curl_error($ch);
    }

    curl_close($ch);
}
?>
