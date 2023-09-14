<?php

include('config.php');

$host = $dbConfig['host'];
$dbname = $dbConfig['dbname'];
$username = $dbConfig['username'];
$password = $dbConfig['password'];

try {
    $mysqli = new mysqli($host, $username, $password, $dbname);
    if ($mysqli->connect_errno) {
        throw new Exception("Connection error: " . $mysqli->connect_error);
    }
} catch (Exception $e) {
    echo '<div style="text-align: center;">';
    echo "Database error: " . $e->getMessage();
    echo "<br><br>";
    echo "<br>Please contact support at <a href='mailto:admin@appcybernetica.com'>admin@appcybernetica.com</a>";
    echo '</div>';
    exit; // Terminate the script
}

return $mysqli;
