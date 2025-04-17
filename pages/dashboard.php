<?php
session_start();
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ticketing Dashboard</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/update_password.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
  
  </style>
</head>
<body>

<div class="header">
  <h1>Ticketing Dashboard</h1>
  <div class="top-btns">
    <button onclick="openPasswordModal()">Update Password</button>
    <button onclick="window.location.href='logout.php'">Logout</button>
  </div>
</div>

<div class="container">
  <h2>Welcome to Ticketing Application Software as a Service</h2>

  <div class="form-section">
    <h3>Upload Image and PDF</h3>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
    <label><strong>Image File:</strong></label><br>
<small style="color: gray;">Only .png, .jpg, .jpeg images are allowed.</small><br>
<input type="file" name="image" accept=".png,.jpg,.jpeg" required onchange="previewImage(event)">



      <label>PDF File:</label>
      <input type="file" name="pdf" accept=".pdf" required><br><br>

      <button type="submit" name="upload_files">Upload</button>
    </form>
  </div>
</div>

<!-- Password Modal -->
<div id="passwordModal">
  <div class="modal-content">
    <h3>Update Password</h3>
    <form id="updatePasswordForm">
      <input type="password" name="old_password" placeholder="Current Password" required>
      <input type="password" name="new_password" placeholder="New Password" required>
      <div>
        <button type="submit">Update</button>
        <button type="button" onclick="closePasswordModal()">Cancel</button>
      </div>
    </form>
    <p id="passwordMessage"></p>
  </div>
</div>

<script>
  function openPasswordModal() {
    document.getElementById('passwordModal').style.display = 'flex';
  }

  function closePasswordModal() {
    document.getElementById('passwordModal').style.display = 'none';
  }

  document.getElementById('updatePasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(e.target);

    fetch('update-password.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.text())
    .then(data => {
      if (data === "success") {
        Swal.fire({
          icon: 'success',
          title: 'Password Updated!',
          text: 'Your password has been updated successfully.',
          confirmButtonColor: '#3085d6'
        });
        closePasswordModal();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: data,
          confirmButtonColor: '#d33'
        });
      }
      e.target.reset();
    });
  });
</script>

<script>
  function openPasswordModal() {
  const modal = document.getElementById('passwordModal');
  modal.style.display = 'flex';
  setTimeout(() => modal.classList.add('show'), 10);
}

function closePasswordModal() {
  const modal = document.getElementById('passwordModal');
  modal.classList.remove('show');
  setTimeout(() => modal.style.display = 'none', 300);
}
</script>



<!-- jab image of pdf upload hn jian -->
<?php if (isset($_GET['upload']) && $_GET['upload'] == 'success') : ?>
<script>
  Swal.fire({
    icon: 'success',
    title: 'Upload Successful!',
    text: 'Your image and PDF were submitted successfully. Thank you for uploading!',
    confirmButtonColor: '#3085d6'
  }).then(() => {
    // Remove the upload=success from URL without reloading
    if (window.history.replaceState) {
      const url = new URL(window.location);
      url.searchParams.delete('upload');
      window.history.replaceState({}, document.title, url);
    }
  });
</script>
<?php endif; ?>


</body>
</html>
