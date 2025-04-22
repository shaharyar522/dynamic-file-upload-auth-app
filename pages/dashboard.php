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
  <link rel="stylesheet" href="../css/navbar.css">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
  /* Additional styles for image preview can be added here */
  </style>
</head>
<body>
<nav class="navbar">
        <div class="navbar-brand">
            <h1>Ticketing Dashboard</h1>
            <div class="nav-item">
                <a href="#" class="nav-link">Tickets Detail</a>
                <div class="dropdown-menu">
                    <a href="complete_tickets.php" class="dropdown-item">Complete Tickets</a>
                    <a href="pending_tickets.php" class="dropdown-item">Pending Tickets</a>
                </div>
            </div>
        </div>
        <div class="btn-group">
            <button class="btn btn-outline" onclick="openPasswordModal()">Update Password</button>
            <button class="btn btn-danger" onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </nav>

    <script>
        function openPasswordModal() {
            // Modal logic here
        }
    </script>


<div class="container">
  <h2>Welcome to Ticketing Application Software as a Service</h2>

  <div class="form-section">
    <h3>Upload Image </h3>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
      <label><strong>Image File:</strong></label><br>
      <small style="color: gray;">Only .png, .jpg, .jpeg images are allowed.</small><br>
      <input type="file" name="image[]" accept=".png,.jpg,.jpeg" multiple>
      
      <!-- pdf ko remove kya hnva hain or databse main path none hngi  -->
      <!-- <label>PDF File:</label>
      <input type="file" name="pdf" accept=".pdf"><br><br> -->

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

<script>
  <?php if (isset($_GET['upload']) && $_GET['upload'] == 'success_image') : ?>
    Swal.fire({
      icon: 'success',
      title: 'Image Uploaded!',
      text: 'Your image has been uploaded successfully.',
      confirmButtonColor: '#3085d6'
    });
  <?php elseif (isset($_GET['upload']) && $_GET['upload'] == 'success_pdf') : ?>
    Swal.fire({
      icon: 'success',
      title: 'PDF Uploaded!',
      text: 'Your PDF has been uploaded successfully.',
      confirmButtonColor: '#3085d6'
    });
  <?php endif; ?>









  
</script>

<!-- now here is my my uploading image or pdf -->
<?php if (isset($_GET['upload'])) : ?>
<script>
  Swal.fire({
    title: 'Uploading...',
    text: 'Please wait while we upload your files.',
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading(); // Show loader
    }
  });

  // Simulate a short delay, then show success alert
  setTimeout(() => {
    Swal.fire({
      icon: 'success',
      title: 'Uploaded successfully!',
      confirmButtonColor: '#3085d6'
    }).then(() => {
      // Remove upload param from URL
      const url = new URL(window.location);
      url.searchParams.delete('upload');
      window.history.replaceState({}, document.title, url);
    });
  }, 1500); // 1.5 second delay before success
</script>
<?php endif; ?>

<!-- jab koe person without button per click karay ga then us ko uay message show hnga  -->

<script>
  // Form validation for image upload
  document.querySelector('form[action="upload.php"]').addEventListener('submit', function (e) {
    const imageInput = document.querySelector('input[name="image[]"]');
    if (!imageInput.files.length) {
      e.preventDefault(); // Stop form submission
      Swal.fire({
        icon: 'error',
        title: 'No Files Selected!',
       
        confirmButtonColor: '#d33'
      });
    }
  });
</script>



</body>
</html>
