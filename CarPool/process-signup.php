<?php

if (empty($_POST["name"])) {
    die("Name is required");
}

if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

if (!preg_match("/[a-zA-Z]/", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if (!preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
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
            $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

            // Include database configuration
            require __DIR__ . "/dbconfig/database.php";

            $sql = "INSERT INTO user (name, email, password_hash, teacher_id) VALUES (?, ?, ?, ?)";
            $stmt = $mysqli->stmt_init();

            if ($stmt->prepare($sql)) {
                // Check if teacher_id is provided and is a valid integer, otherwise set it to NULL
                $teacher_id = isset($_POST["teacher_id"]) && is_numeric($_POST["teacher_id"]) ? $_POST["teacher_id"] : NULL;

                $stmt->bind_param("ssss", $_POST["name"], $_POST["email"], $password_hash, $teacher_id);

                if ($stmt->execute()) {
                    header("Location: signup-success.html");
                    exit;
                } else {
                    if ($mysqli->errno === 1062) {
                        die("Email already taken");
                    } else {
                        die("Database error: " . $mysqli->error);
                    }
                }
            } else {
                die("SQL error: " . $mysqli->error);
            }
        } else {
            die("Please check reCAPTCHA");
        }
    } else {
        // Handle cURL error
        $error_message = 'cURL error: ' . curl_error($ch);
        die($error_message);
    }

    curl_close($ch);
}
?>
