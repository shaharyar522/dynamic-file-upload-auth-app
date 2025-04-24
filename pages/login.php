<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tickting");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM log_in WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $error = "";
    $success = false;

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if ($password === $user["password"]) {
            if ($user["emailverify"] === "none") {
                $error = "email_not_verified";
            } elseif ($user["emailverify"] === "verify" && $user["status"] == 0) {
                $error = "account_not_active";
            } elseif ($user["emailverify"] === "verify" && $user["status"] == 1) {
                $_SESSION["user"] = $user;
                $success = true;
            }
        } else {
            $error = "invalid_credentials";
        }
    } else {
        $error = "invalid_credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="../css/login.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="wrapper">
  <h2>Tickting - Login</h2>
  <form method="post" autocomplete="off">
    <div class="input-field">
      <input type="email" name="email" required placeholder=" " />
      <label>Email Address</label>
    </div>
    <div class="input-field">
      <input type="password" name="password" required placeholder=" " />
      <label>Password</label>
    </div>
    <button type="submit">Login</button>
  </form>
</div>

<!-- SweetAlert Responses -->
<?php if (isset($error) && $error === "invalid_credentials"): ?>
<script>
  Swal.fire({
    icon: 'error',
    title: 'Login Failed',
    text: '❌ Incorrect email or password. Please try again.',
    confirmButtonText: 'OK'
  });
</script>
<?php endif; ?>

<?php if (isset($error) && $error === "email_not_verified"): ?>
<script>
  Swal.fire({
    icon: 'info',
    title: 'Email Not Verified ✉️',
    text: 'Please verify your email address first.',
    confirmButtonText: 'OK'
  });
</script>
<?php endif; ?>

<?php if (isset($error) && $error === "account_not_active"): ?>
<script>
  Swal.fire({
    icon: 'warning',
    title: '⛔ Account Not Active',
    text: 'This unit has not been activated by Admin.',
    confirmButtonText: 'OK'
  });
</script>
<?php endif; ?>

<?php if (isset($success) && $success === true): ?>
<script>
  Swal.fire({
    icon: 'success',
    text: 'Your account has been successfully logged in Please Wait...',
    timer: 2500,
    showConfirmButton: false,
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  }).then(() => {
    window.location.href = '/tickting/pages/dashboard.php';
  });
</script>
<?php endif; ?>



</body>
</html>
