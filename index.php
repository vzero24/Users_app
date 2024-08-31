<?php
include_once('db.php'); // Include the database connection file
session_start(); // Start a new session or resume the existing one

$action = false; // Initialize action variable to track the type of operation performed

// Check if the form has been submitted
if (isset($_POST['save'])) {

  // Retrieve form data
  $name = $_POST['name'];
  $email = $_POST['email'];
  $mobile = $_POST['mobile'];
  $password = $_POST['password'];

  // Determine if we are adding a new user or updating an existing one
  if ($_POST['save'] == "Save") {
    // SQL query to insert a new user
    $save_sql = "INSERT INTO `users`(`name`, `email`, `password`, `mobile`) VALUES 
              ('$name','$email','$password','$mobile')";
  } else {
    // SQL query to update an existing user
    $id = $_POST['id']; // Get the user ID from the form
    $save_sql = "UPDATE `users` SET `name`='$name', `email`='$email', `mobile`='$mobile', `password`='$password' WHERE id = $id";
  }

  // Execute the SQL query
  $res_save = mysqli_query($con, $save_sql);
  if (!$res_save) {
    // Display error if query fails
    die(mysqli_error($con));
  } else {
    // Set session action to 'edit' or 'add' based on whether an ID is set
    $_SESSION['action'] = isset($_POST['id']) ? "edit" : "add";
    header('Location: index.php'); // Redirect to avoid resubmission on refresh
    exit(); // Ensure no further code is executed
  }
}

// Check if the delete action has been triggered
if (isset($_GET['action']) && $_GET['action'] == 'del') {
  $id = $_GET['id']; // Get the user ID from the URL
  $del_sql = "DELETE FROM users WHERE id = $id"; // SQL query to delete the user
  $res_del = mysqli_query($con, $del_sql);
  if (!$res_del) {
    // Display error if query fails
    die(mysqli_error($con));
  } else {
    // Set session action to 'del' for deletion
    $_SESSION['action'] = "del";
    header('Location: index.php'); // Redirect to avoid resubmission on refresh
    exit(); // Ensure no further code is executed
  }
}

// SQL query to fetch all users
$users_sql = "SELECT * FROM users";
$all_user = mysqli_query($con, $users_sql); // Execute the query

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/toster.css">
  <title>Users App</title>
</head>

<body>
  <div class="container">
    <div class="wrapper p-5 m-5">
      <div class="d-flex p-2 justify-content-between mb-2">
        <h2>All Users</h2>
        <div><a href="add_user.php"><i data-feather="user-plus"></i></a></div>
      </div>
      <hr>
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Mobile</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($user = $all_user->fetch_assoc()) { ?>
            <tr>
              <td><?php echo $user['id']; ?></td>
              <td><?php echo $user['name']; ?></td>
              <td><?php echo $user['email']; ?></td>
              <td><?php echo $user['mobile']; ?></td>
              <td>
                <div class="d-flex p-2 justify-content-evenly mb-2">
                  <!-- Icons for delete and edit actions -->
                  <i onclick="confirm_delete(<?php echo $user['id']; ?>);" class="text-danger" data-feather="trash-2"></i>
                  <i onclick="edit(<?php echo $user['id']; ?>);" class="text-success" data-feather="edit"></i>
                </div>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Include JavaScript files -->
  <script src="js/jq.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/icons.js"></script>
  <script src="js/toster.js"></script>
  <script src="js/main.js"></script>

  <?php
  // Display appropriate message based on the session action
  if (isset($_SESSION['action'])) {
    $action = $_SESSION['action'];
    unset($_SESSION['action']); // Clear the session action after displaying
    if ($action == 'add') { ?>
      <script>
        show_add(); // Show add message
      </script>
    <?php } elseif ($action == 'del') { ?>
      <script>
        show_del(); // Show delete message
      </script>
    <?php } elseif ($action == 'edit') { ?>
      <script>
        show_update(); // Show update message
      </script>
  <?php }
  }
  ?>

  <script>
    feather.replace(); // Replace feather icons
  </script>
</body>

</html>