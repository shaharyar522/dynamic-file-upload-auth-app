<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Check if user is logged in
if (!isset($_SESSION["user"])) {
  echo "Unauthorized access!";
  exit;
}

$email = is_array($_SESSION["user"]) ? $_SESSION["user"]["email"] : $_SESSION["user"];

// Connect to database
$conn = new mysqli("localhost", "root", "", "tickting");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if new password is submitted
if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
  $newPassword = $_POST['new_password'];

  // Store the password directly (plain text version)
  $sql = "UPDATE log_in SET password='$newPassword' WHERE email='$email'";
  if ($conn->query($sql)) {
    echo "Password updated successfully!";
  } else {
    echo "Failed to update password!";
  }
} else {
  echo "Please enter a new password.";
}

$conn->close();
?>
