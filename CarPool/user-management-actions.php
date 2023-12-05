<?php
require __DIR__ . "/dbconfig/database.php";

// function to fetch data
if ($_GET["action"] === "fetchData") {
  $sql = "SELECT * FROM `user`";
  $result = mysqli_query($mysqli, $sql);

  if (!$result) {
    // Handle the database query error, e.g., log the error or return an error response.
    echo json_encode([
      "error" => "Database query error: " . mysqli_error($mysqli)
    ]);
    exit; // Terminate the script
  }

  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }
  mysqli_close($mysqli);

  header('Content-Type: application/json');
  echo json_encode([
    "data" => $data
  ]);
}



// insert data to database
if ($_GET["action"] === "insertData") {
  if (!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
    $name = $_POST["name"];
    $email = mysqli_real_escape_string($mysqli, $_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Check if email already exists
    $checkDuplicateEmailQuery = "SELECT COUNT(*) as count FROM `user` WHERE `email` = ?";
    $checkDuplicateEmailStmt = mysqli_prepare($mysqli, $checkDuplicateEmailQuery);
    mysqli_stmt_bind_param($checkDuplicateEmailStmt, "s", $email);
    mysqli_stmt_execute($checkDuplicateEmailStmt);
    mysqli_stmt_bind_result($checkDuplicateEmailStmt, $emailCount);
    mysqli_stmt_fetch($checkDuplicateEmailStmt);
    mysqli_stmt_close($checkDuplicateEmailStmt);

    if ($emailCount > 0) {
      echo json_encode([
        "statusCode" => 500,
        "message" => "Email address already exists 😓"
      ]);
      exit; // Stop further execution
    }

    // Check if teacher_id is set in the form
    if (!empty($_POST["teacher_id"])) {
      $teacher_id = mysqli_real_escape_string($mysqli, $_POST["teacher_id"]);

      // Check if teacher_id already exists
      $checkDuplicateQuery = "SELECT COUNT(*) as count FROM `user` WHERE `teacher_id` = ?";
      $checkDuplicateStmt = mysqli_prepare($mysqli, $checkDuplicateQuery);
      mysqli_stmt_bind_param($checkDuplicateStmt, "s", $teacher_id);
      mysqli_stmt_execute($checkDuplicateStmt);
      mysqli_stmt_bind_result($checkDuplicateStmt, $teacherIdCount);
      mysqli_stmt_fetch($checkDuplicateStmt);
      mysqli_stmt_close($checkDuplicateStmt);

      if ($teacherIdCount > 0) {
        echo json_encode([
          "statusCode" => 500,
          "message" => "Teacher ID already exists 😓"
        ]);
        exit; // Stop further execution
      }

      // Use a prepared statement to prevent SQL injection
      $sql = "INSERT INTO `user` (`name`, `email`, `password_hash`, `teacher_id`) VALUES (?, ?, ?, ?)";
      $stmt = mysqli_prepare($mysqli, $sql);
      mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $password, $teacher_id);
    } else {
      // If teacher_id is not set, skip its insertion
      // Use a prepared statement to prevent SQL injection
      $sql = "INSERT INTO `user` (`name`, `email`, `password_hash`) VALUES (?, ?, ?)";
      $stmt = mysqli_prepare($mysqli, $sql);
      mysqli_stmt_bind_param($stmt, "sss", $name, $email, $password);
    }

    if ($stmt) {
      // Bind parameters and execute the statement
      if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
          "statusCode" => 200,
          "message" => "Data inserted successfully 😀"
        ]);
      } else {
        echo json_encode([
          "statusCode" => 500,
          "message" => "Failed to insert data 😓"
        ]);
      }
      mysqli_stmt_close($stmt);
    } else {
      echo json_encode([
        "statusCode" => 500,
        "message" => "Failed to prepare the statement 😓"
      ]);
    }
  } else {
    echo json_encode([
      "statusCode" => 400,
      "message" => "Please fill all the required fields 🙏"
    ]);
  }
}




// fetch data of individual user for edit form
if ($_GET["action"] === "fetchSingle") {
  if (!empty($_POST["id"])) {
    $id = mysqli_real_escape_string($mysqli, $_POST["id"]);

    // Use a prepared statement to prevent SQL injection
    $sql = "SELECT * FROM user WHERE `id`=?";
    $stmt = mysqli_prepare($mysqli, $sql);
    if ($stmt) {
      // Bind the parameter and execute the statement
      mysqli_stmt_bind_param($stmt, "i", $id);
      if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
          $data = mysqli_fetch_assoc($result);
          header("Content-Type: application/json");
          echo json_encode([
            "statusCode" => 200,
            "data" => $data
          ]);
        } else {
          echo json_encode([
            "statusCode" => 404,
            "message" => "No user found with this id 😓"
          ]);
        }
      } else {
        echo json_encode([
          "statusCode" => 500,
          "message" => "Failed to execute the statement 😓"
        ]);
      }
      mysqli_stmt_close($stmt);
    } else {
      echo json_encode([
        "statusCode" => 500,
        "message" => "Failed to prepare the statement 😓"
      ]);
    }
    mysqli_close($mysqli);
  } else {
    echo json_encode([
      "statusCode" => 400,
      "message" => "Invalid request 😓"
    ]);
  }
}



// function to update data
if ($_GET["action"] === "updateData") {

  if (!empty($_POST["name"]) && !empty($_POST["email"])) {
    $id = mysqli_real_escape_string($mysqli, $_POST["id"]);
    $teacher_id = !empty($_POST["teacher_id"]) ? mysqli_real_escape_string($mysqli, $_POST["teacher_id"]) : null; //check if teacher_id is provided
    $name = $_POST["name"]; // No escaping here
    $email = mysqli_real_escape_string($mysqli, $_POST["email"]);
    $password = !empty($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : null; // Check if password is provided
    
    // Selects teacher name from user table to assign to students table in the teacher_name column
    $sqlSelect = "SELECT teacher_id, name FROM user WHERE teacher_id = '$teacher_id'";
    $rows = mysqli_query($mysqli, $sqlSelect);

    // Check if teacher_id is null or if teacher_id exists in user table
    if ($teacher_id === null || mysqli_num_rows($rows) > 0) {
      $update_teachername = mysqli_fetch_assoc($rows);

      // Use prepared statements to prevent SQL injection
      if ($password !== null) {
        // If password is provided, include it in the update
        $sql = "UPDATE `user` SET `teacher_id`=?, `name`=?, `email`=?, `password_hash`=? WHERE `id`=?";

        // syncs teacher name to students table
        $syncNameToStudents = "UPDATE students s INNER JOIN user u ON s.teacher_id = u.teacher_id SET s.teacher_name = u.name WHERE u.teacher_id IS NOT NULL";

        $stmt = mysqli_prepare($mysqli, $sql);
        if ($stmt) {
          // Bind parameters and execute the statement
          mysqli_stmt_bind_param($stmt, "ssssi", $teacher_id, $name, $email, $password, $id);
          if (mysqli_stmt_execute($stmt)) {
            echo json_encode([
              "statusCode" => 200,
              "message" => "Data updated successfully 😀"
            ]);
            
            //execute $syncNameToStudents query
            mysqli_query($mysqli, $syncNameToStudents);

          } else {
            echo json_encode([
              "statusCode" => 500,
              "message" => "Failed to update data 😓"
            ]);
          }
          mysqli_stmt_close($stmt);
        } else {
          echo json_encode([
            "statusCode" => 500,
            "message" => "Failed to prepare the statement 😓"
          ]);
        }
      } else {
        // If password is not provided, exclude it from the update
        $sql = "UPDATE `user` SET `teacher_id`=?, `name`=?, `email`=? WHERE `id`=?";

        // syncs teacher name to students table
        $syncNameToStudents = "UPDATE students s INNER JOIN user u ON s.teacher_id = u.teacher_id SET s.teacher_name = u.name WHERE u.teacher_id IS NOT NULL";    

        $stmt = mysqli_prepare($mysqli, $sql);
        if ($stmt) {
          // Bind parameters and execute the statement
          mysqli_stmt_bind_param($stmt, "sssi", $teacher_id, $name, $email, $id);
          if (mysqli_stmt_execute($stmt)) {
            echo json_encode([
              "statusCode" => 200,
              "message" => "Data updated successfully 😀"
            ]);

            //execute $syncNameToStudents query
            mysqli_query($mysqli, $syncNameToStudents);

          } else {
            echo json_encode([
              "statusCode" => 500,
              "message" => "Failed to update data 😓"
            ]);
          }
          mysqli_stmt_close($stmt);
        } 
        else {
          echo json_encode([
            "statusCode" => 500,
            "message" => "Failed to prepare the statement 😓"
          ]);
        }
      }
      mysqli_close($mysqli);
    } else {
      echo json_encode([
        "statusCode" => 400,
        "message" => "Please fill all the required fields 🙏"
      ]);
    }
  } else {
    echo json_encode([
      "statusCode" => 500,
      "message" => "Failed to update data 😓"
    ]);
  }
}



// function to delete data
if ($_GET["action"] === "deleteData") {
  if (!empty($_POST["id"])) {
    $id = mysqli_real_escape_string($mysqli, $_POST["id"]);

    // Use a prepared statement to prevent SQL injection
    $sql = "DELETE FROM user WHERE `id`=?";
    $stmt = mysqli_prepare($mysqli, $sql);
    if ($stmt) {
      // Bind the parameter and execute the statement
      mysqli_stmt_bind_param($stmt, "i", $id);
      if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
          "statusCode" => 200,
          "message" => "Data deleted successfully 😀"
        ]);
      } else {
        echo json_encode([
          "statusCode" => 500,
          "message" => "Failed to delete data 😓"
        ]);
      }
      mysqli_stmt_close($stmt);
    } else {
      echo json_encode([
        "statusCode" => 500,
        "message" => "Failed to prepare the statement 😓"
      ]);
    }
  } else {
    echo json_encode([
      "statusCode" => 400,
      "message" => "Invalid request 😓"
    ]);
  }
}
