<?php
require __DIR__ . "/dbconfig/database.php"; // Adjust the path as needed

// function to fetch data
if ($_GET["action"] === "fetchData") {
  $sql = "SELECT * FROM students where student_id != 999";
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
  if (!empty($_POST["student_id"]) && !empty($_POST["first_name"]) && !empty($_POST["last_name"]) && !empty($_POST["grade"]) && !empty($_POST["teacher_name"]) && !empty($_POST["teacher_id"])) {
    $student_id = mysqli_real_escape_string($mysqli, $_POST["student_id"]);
    $first_name = $_POST["first_name"]; // No escaping here
    $last_name = $_POST["last_name"];   // No escaping here
    $grade = mysqli_real_escape_string($mysqli, $_POST["grade"]);
    $teacher_name = mysqli_real_escape_string($mysqli, $_POST["teacher_name"]);
    $teacher_id = mysqli_real_escape_string($mysqli, $_POST["teacher_id"]);


    $sqlSelect = "SELECT teacher_id, name from `user` where teacher_id = '$teacher_id'";
    $rows = mysqli_query($mysqli, $sqlSelect);
    if (mysqli_num_rows($rows) > 0) {
      $update_teachername = mysqli_fetch_assoc($rows);
      // Use a prepared statement to prevent SQL injection
      $sql = "INSERT INTO `students` (`student_id`, `first_name`, `last_name`, `grade`, `teacher_name`, `teacher_id`) VALUES (?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($mysqli, $sql);
      if ($stmt) {
        // Bind parameters and execute the statement
        mysqli_stmt_bind_param($stmt, "ssssss", $student_id, $first_name, $last_name, $grade, $update_teachername['name'], $teacher_id);
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
      }
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
    $sql = "SELECT * FROM students WHERE `id`=?";
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

  if (!empty($_POST["student_id"]) && !empty($_POST["first_name"]) && !empty($_POST["last_name"]) && !empty($_POST["grade"]) && !empty($_POST["teacher_name"]) && !empty($_POST["teacher_id"])) {
    $id = mysqli_real_escape_string($mysqli, $_POST["id"]);
    $student_id = mysqli_real_escape_string($mysqli, $_POST["student_id"]);
    $first_name = $_POST["first_name"]; // No escaping here
    $last_name = $_POST["last_name"];   // No escaping here
    $grade = mysqli_real_escape_string($mysqli, $_POST["grade"]);
    $teacher_name = mysqli_real_escape_string($mysqli, $_POST["teacher_name"]);
    $teacher_id = mysqli_real_escape_string($mysqli, $_POST["teacher_id"]);

    // Selects teacher name from user table to assign to students table in the teacher_name column
    $sqlSelect = "select teacher_id, name from user where teacher_id = '$teacher_id'";
    $rows = mysqli_query($mysqli, $sqlSelect);
    if (mysqli_num_rows($rows) > 0) {
      $update_teachername = mysqli_fetch_assoc($rows);

      // Use prepared statements to prevent SQL injection
      $sql = "UPDATE `students` SET `student_id`=?, `first_name`=?, `last_name`=?, `grade`=?, `teacher_name`=?, `teacher_id`=? WHERE `id`=?";
      $stmt = mysqli_prepare($mysqli, $sql);
      if ($stmt) {
        // Bind parameters and execute the statement
        mysqli_stmt_bind_param($stmt, "ssssssi", $student_id, $first_name, $last_name, $grade, $update_teachername['name'], $teacher_id, $id);
        if (mysqli_stmt_execute($stmt)) {
          echo json_encode([
            "statusCode" => 200,
            "message" => "Data updated successfully 😀"
          ]);
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
    $sql = "DELETE FROM students WHERE `id`=?";
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
