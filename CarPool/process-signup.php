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

if (!preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if (!preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

if (isset($_POST['g-recaptcha-response'])) {

    $ip = $_SERVER['REMOTE_ADDR'];
    $secret = '6LdtiwwUAAAAAAfXRgEh9zg37goVaeuRL1btdONl';
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret .  '&remoteip' . $ip . '&response=' . $_POST['g-recaptcha-response']);
    $responseData = json_decode($verifyResponse);
    if ($responseData->success) {

        $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $mysqli = require __DIR__ . "/dbconfig/database.php";

        $sql = "INSERT INTO user (name, email, password_hash, teacher_id)
                    VALUES (?, ?, ?, ?)";

        $stmt = $mysqli->stmt_init();

        if (!$stmt->prepare($sql)) {
            die("SQL error: " . $mysqli->error);
        }

        // Check if teacher_id is provided and is a valid integer, otherwise set it to NULL
        $teacher_id = isset($_POST["teacher_id"]) && is_numeric($_POST["teacher_id"]) ? $_POST["teacher_id"] : NULL;

        $stmt->bind_param(
            "ssss",
            $_POST["name"],
            $_POST["email"],
            $password_hash,
            $teacher_id
        );

        if ($stmt->execute()) {

            header("Location: signup-success.html");
            exit;
        } else {

            if ($mysqli->errno === 1062) {
                die("email already taken");
            } else {
                die($mysqli->error . " " . $mysqli->errno);
            }
        }
    } else {
        die("Please check reCAPTCHA");
    }
}
