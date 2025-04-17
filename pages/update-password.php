<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION["user"])) {
  echo "Unauthorized access!";
  exit;
}

$email = is_array($_SESSION["user"]) ? $_SESSION["user"]["email"] : $_SESSION["user"];

$conn = new mysqli("localhost", "root", "", "tickting");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['old_password'], $_POST['new_password'])) {
  $oldPassword = $_POST['old_password'];
  $newPassword = $_POST['new_password'];

  $stmt = $conn->prepare("SELECT password FROM log_in WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->bind_result($dbPassword);
  $stmt->fetch();
  $stmt->close();

  if ($dbPassword === $oldPassword) {
    $update = $conn->prepare("UPDATE log_in SET password = ? WHERE email = ?");
    $update->bind_param("ss", $newPassword, $email);
    if ($update->execute()) {
      echo "success";
    } else {
      echo "Failed to update password!";
    }
    $update->close();
  } else {
    echo "Old password is incorrect!";
  }
} else {
  echo "Please provide old and new passwords!";
}

$conn->close();
?>
