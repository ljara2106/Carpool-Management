<?php
session_start();

if (isset($_SESSION["user_id"])) {
  require __DIR__ . "/dbconfig/database.php";
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
  <!--<link rel="stylesheet" href="/css/sm-style.css">-->
</head>
<noscript>
  <p style="text-align: center;">Please enable JavaScript in your browser before using this website.</p>
</noscript>
<?php if (isset($user)) : ?>

  <body>

    <nav class="navbar justify-content-center fs-3 mb-3" style="background-color:#DA6126;">
      <a href="index.php" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
        <img src="/img/buffaloes_sm.png" alt="Buffaloes" style="width: 50px; height: 50px; margin-right: 10px;">
        TES Carpool - User Management
      </a>
    </nav>

    <br>
    <div style="text-align: center;">
      <p>Hello, Welcome: <strong><?= htmlspecialchars($user["name"]) ?></strong></p>
    </div>
    <br>
    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="text-body-secondary">
          <span class="h5">All Users</span>
          <br>
          Manage all your existing users or add a new one
          <br>
          <br>
          <strong>
            Access Note:</strong>
            <p>
            Users without a Teacher ID are administrators with full system access. 
            <br>
            Classroom teachers must have an assigned Teacher ID for proper authorization.
            </p>
        </div>
        <!-- Button to trigger Add user offcanvas -->
        <button class="btn btn-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser">
          <i class="fa-solid fa-user-plus fa-xs"></i>
          Add new user
        </button>
      </div>


      <table class="table table-bordered table-striped table-hover align-middle" id="myTable" style="width:100%;">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th style="width: 100px;">Teacher ID</th>
            <th>Name</th>
            <th>Email</th>
            <th style="width: 100px;">Actions</th>
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
            <label for="teacher_id" class="form-label">Teacher ID (auto-generated)</label>
            <?php
            // Assuming you have a database connection and query here
            $query = "SELECT MAX(teacher_id) AS max_teacher_id FROM user";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_assoc($result);
            $next_teacher_id = $row['max_teacher_id'] + 1;
            ?>
            <input type="number" class="form-control" id="teacher_id" name="teacher_id" value="<?php echo $next_teacher_id; ?>" ;>
          </div>
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="John">
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
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
            <label for="edit_teacher_id" class="form-label">Teacher ID (read-only)</label>
            <input type="number" class="form-control" id="edit_teacher_id" name="teacher_id" readonly style="background-color: lightgray" ;>
          </div>
          <div class="mb-3">
            <label for="edit_name" class="form-label">Name</label>
            <input type="text" class="form-control" id="edit_name" name="name" placeholder="John">
          </div>
          <div class="mb-3">
            <label for="edit_email" class="form-label">Email</label>
            <input type="email" class="form-control" id="edit_email" name="email">
          </div>
          <div class="mb-3">
            <label for="edit_password" class="form-label">Password</label>
            <input type="password" class="form-control" id="edit_password" name="password">
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
      // JavaScript to allow blank fields and prevent non-numeric input in the "Teacher ID" field
      document.getElementById("teacher_id").addEventListener("input", function() {
        // Allow blank fields
        if (this.value.trim() === "") {
          return;
        }
        // Prevent non-numeric input
        this.value = this.value.replace(/[^0-9]/g, "");
      });
    </script>

    <script>
      // JavaScript to set the name field to uppercase
      document.getElementById("name").addEventListener("input", function() {
        this.value = this.value.toUpperCase();
      });

      document.getElementById("edit_name").addEventListener("input", function() {
        this.value = this.value.toUpperCase();
      });
    </script>


    <!-- Bootstrap  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Datatables  -->
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.js"></script>
    <!-- JS  -->
    <script src="/js/user-management.js"></script>
    <br>
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