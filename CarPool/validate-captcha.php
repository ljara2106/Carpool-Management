<?php
$is_invalid = false;
$error_message = '';

// Function to get the visitor's IP address
function getVisitorIP()
{
    // If you're testing on localhost, return a placeholder or handle it as needed
    if ($_SERVER['REMOTE_ADDR'] == '::1' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
        return 'localhost';
    }

    // Try to get the visitor's IP address from the X-Forwarded-For header
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddresses = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim(end($ipAddresses));
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];
    }

    return '';
}

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
                     // Fetching visitor's IP address section ////////////////////////////////
                     $visitorIP = getVisitorIP();
                     // Convert UTC time to Eastern Time using DateTime
                     $utcDateTime = new DateTime($logData, new DateTimeZone('UTC'));
                     $utcDateTime->setTimezone(new DateTimeZone('America/New_York'));
                     $easternTime = $utcDateTime->format('Y-m-d H:i:s');
 
                     // Log the IP address along with the current date and time (now in Eastern Time)
                     $logData = $easternTime . " - IP: $visitorIP\n";
                     $logFile = 'ip_log.txt';
                     // Check if the log file exists, and create it if not
                     if (!file_exists($logFile)) {
                         touch($logFile);
                         chmod($logFile, 0640); // Blocks public access to the log file
                     }
                     file_put_contents($logFile, $logData, FILE_APPEND);
                     // Fetching visitor's IP address section ////////////////////////////////
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
