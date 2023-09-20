<?php
session_start();

if (isset($_SESSION["user_id"])) {
  // Include the database configuration
  require __DIR__ . "/dbconfig/database.php"; // Adjust the path as needed
  $sql = "SELECT * FROM user WHERE id = ?";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("i", $_SESSION["user_id"]);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  $stmt->close();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TES Carpool - Student Management</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <!-- Font Awesome  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Datatables CSS  -->
  <link href="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.css" rel="stylesheet" />
  <!-- CSS  -->
  <link rel="stylesheet" href="/css/sm-style.css">
</head>
<noscript>
  <p style="text-align: center;">Please enable JavaScript in your browser before using this website.</p>
</noscript>
<?php if (isset($user)) : ?>

  <body>

    <nav class="navbar justify-content-center fs-3 mb-3" style="background-color:#00ff5573;">
      <a href="index.php" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
        <img src="/img/buffaloes_sm.png" alt="Buffaloes" style="width: 50px; height: 50px; margin-right: 10px;">
        TES Carpool - Student Management
      </a>
    </nav>


    <div style="text-align: center;">
      <p>Hello, Welcome: <?= htmlspecialchars($user["name"]) ?></p>
    </div>

    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="text-body-secondary">
          <span class="h5">All Students</span>
          <br>
          Manage all your existing students or add a new one
        </div>
        <!-- Button to trigger Add user offcanvas -->
        <button class="btn btn-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser">
          <i class="fa-solid fa-user-plus fa-xs"></i>
          Add new student
        </button>
      </div>


      <table class="table table-bordered table-striped table-hover align-middle" id="myTable" style="width:100%;">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Student ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Grade</th>
            <th>Teacher Name</th>
            <th>Teacher ID</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>



    <!-- Add user offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" style="width: 600px;">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Add New User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <form method="POST" id="insertForm">
          <div class="mb-3">
            <label for="student_id" class="form-label">Student ID</label>
            <?php
            // Assuming you have a database connection and query here
            $query = "SELECT MAX(student_id) AS max_student_id FROM students";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_assoc($result);
            $next_student_id = $row['max_student_id'] + 1;
            ?>
            <input type="number" class="form-control" id="student_id" name="student_id" value="<?php echo $next_student_id; ?>" readonly>
          </div>
          <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="John">
          </div>
          <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Doe">
          </div>
          <div class="mb-3">
            <label for="grade" class="form-label">Grade</label>
            <input type="number" class="form-control" id="grade" name="grade" placeholder="0">
          </div>
          <div class="mb-3">
            <label for="teacher_name" class="form-label">Teacher Name</label>
            <select class="form-select" id="teacher_name" name="teacher_name">
              <option value="" selected disabled>--Select Teacher--</option>
              <?php
              // Assuming you have a database connection
              $sql = "SELECT teacher_id, name FROM user WHERE teacher_id IS NOT NULL";
              $result = mysqli_query($mysqli, $sql);

              while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row['teacher_id'] . '">' . $row['name'] . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="teacher_id" class="form-label">Teacher ID</label>
            <input type="number" class="form-control" id="teacher_id" name="teacher_id" placeholder="0" readonly>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-primary me-2" id="insertBtn">Submit</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
          </div>
        </form>
      </div>
    </div>




    <!-- Edit user offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditUser" style="width: 600px;">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Edit User Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <form method="POST" id="editForm">
          <input type="hidden" name="id" id="id">
          <div class="mb-3">
            <label for="edit_student_id" class="form-label">Student ID</label>
            <input type="number" class="form-control" id="edit_student_id" name="student_id" placeholder="1000" readonly>
          </div>
          <div class="mb-3">
            <label for="edit_first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="edit_first_name" name="first_name" placeholder="John">
          </div>
          <div class="mb-3">
            <label for="edit_last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="edit_last_name" name="last_name" placeholder="Doe">
          </div>
          <div class="mb-3">
            <label for="edit_grade" class="form-label">Grade</label>
            <input type="number" class="form-control" id="edit_grade" name="grade" placeholder="0">
          </div>
          <div class="mb-3">
            <label for="edit_teacher_name" class="form-label">Teacher Name</label>
            <select class="form-select" id="edit_teacher_name" name="teacher_name">
              <option value="" selected disabled>--Select Teacher--</option>
              <?php
              // Assuming you have a database connection
              $sql = "SELECT teacher_id, name FROM user WHERE teacher_id IS NOT NULL";
              $result = mysqli_query($mysqli, $sql);

              while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row['teacher_id'] . '">' . $row['name'] . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="edit_teacher_id" class="form-label">Teacher ID</label>
            <input type="number" class="form-control" id="edit_teacher_id" name="teacher_id" placeholder="0" readonly>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-primary me-2" id="editBtn">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
          </div>
        </form>
      </div>
    </div>




    <!-- Toast container  -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
      <!-- Success toast  -->
      <div class="toast align-items-center text-bg-success" role="alert" aria-live="assertive" aria-atomic="true" id="successToast">
        <div class="d-flex">
          <div class="toast-body">
            <strong>Success!</strong>
            <span id="successMsg"></span>
          </div>
          <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
      <!-- Error toast  -->
      <div class="toast align-items-center text-bg-danger" role="alert" aria-live="assertive" aria-atomic="true" id="errorToast">
        <div class="d-flex">
          <div class="toast-body">
            <strong>Error!</strong>
            <span id="errorMsg"></span>
          </div>
          <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>

    <script>
      // JavaScript to prevent non-numeric input in the "Grade" and "Teacher ID" fields
      document.getElementById("grade").addEventListener("input", function() {
        this.value = this.value.replace(/[^0-9]/g, "");
      });

      document.getElementById("teacher_id").addEventListener("input", function() {
        this.value = this.value.replace(/[^0-9]/g, "");
      });
    </script>

    <script>
      // JavaScript to update the teacher_id input when a teacher is selected
      const teacherNameSelect = document.getElementById("teacher_name");
      const teacherIdInput = document.getElementById("teacher_id");

      teacherNameSelect.addEventListener("change", function() {
        const selectedTeacherOption = teacherNameSelect.options[teacherNameSelect.selectedIndex];
        const selectedTeacherId = selectedTeacherOption.value;

        // Update the teacher_id input with the selected teacher's ID
        teacherIdInput.value = selectedTeacherId;
      });
    </script>

    <script>
      // JavaScript to update the edit_teacher_id input when a teacher is selected
      const editTeacherNameSelect = document.getElementById("edit_teacher_name");
      const editTeacherIdInput = document.getElementById("edit_teacher_id");

      editTeacherNameSelect.addEventListener("change", function() {
        const selectedEditTeacherOption = editTeacherNameSelect.options[editTeacherNameSelect.selectedIndex];
        const selectedEditTeacherId = selectedEditTeacherOption.value;

        // Update the edit_teacher_id input with the selected teacher's ID
        editTeacherIdInput.value = selectedEditTeacherId;
      });
    </script>

    <!-- Bootstrap  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- Jquery -->
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Datatables  -->
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.js"></script>
    <!-- JS  -->
    <script src="/js/student-management.js"></script>

    <br>
    <br>
    <div style="text-align: center;"> <input type="button" value="Log out" onClick="document.location.href='logout.php'" /></div>


  </body>

</html>


<?php else : ?>

  <link rel="stylesheet" href="css/dark.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <div style="text-align: center;">
    <a href="index.php"><img src="img/txlogo.png" alt="Thanksgiving Elementary" title="Home"></a>
    <br>
    <h1><a href="index.php">Student Management - Carpool Management</a></h1>
    <br>
    <div>
      <br>
      <br>
      <div style="text-align: center;">
        <p><a href="login.php">Log in</a></p>
      </div>
    <?php endif; ?>

    <br>
    <br>
    <footer>
      <div style="text-align: center;">
        <p><?php include "includes/footer.php"; ?></p>
      </div>
    </footer>