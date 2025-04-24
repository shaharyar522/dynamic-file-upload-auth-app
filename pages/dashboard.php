<?php
session_start();
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit;
}

include('conn.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Ticketing Dashboard</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/update_password.css">
  <link rel="stylesheet" href="../css/navbar.css">
  <!-- OCR and QR Code Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/tesseract.js@2.1.5/dist/tesseract.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>


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
    <div id="loader" style="
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 24px;
  font-weight: bold;
  z-index: 9999;
  display: none;
">
      Processing...
    </div>

    <h2>Welcome to Ticketing Application Software as a Service</h2>

    <div class="form-section">

      <canvas id="canvas" style="display:none;"></canvas>

      <div>

        <pre style="display: none;" id="textOutput"></pre>


        <pre style="display: none;" id="qrOutput"></pre>
      </div>

      <h3>Upload Image </h3>
      <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="image[]" accept=".png,.jpg,.jpeg" multiple required>

        <!-- Hidden fields for QR and OCR data -->
        <input type="hidden" name="qr_code" id="qr_code">
        <input type="hidden" name="title" id="title">
        <input type="hidden" name="timestamp" id="timestamp">
        <input type="hidden" name="name" id="name">
        <input type="hidden" name="entrance" id="entrance">
        <input type="hidden" name="section" id="section">
        <input type="hidden" name="row" id="row">
        <input type="hidden" name="seat" id="seat">

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
    document.querySelector('form[action="upload.php"]').addEventListener('submit', function(e) {
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


  <script>
    const imageInput = document.querySelector('input[name="image[]"]');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const textOutput = document.getElementById('textOutput');
    const qrOutput = document.getElementById('qrOutput');

    imageInput.addEventListener('change', (event) => {
      const file = event.target.files[0];
      if (!file) return;
      document.getElementById('loader').style.display = 'flex';
      const img = new Image();
      img.onload = () => {
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.drawImage(img, 0, 0);

        // OCR
        Tesseract.recognize(canvas, 'eng')
          .then(({
            data: {
              text
            }
          }) => {
            textOutput.textContent = text;

            // Extract structured data (optional enhancement)
            extractStructuredData(text);
            document.getElementById('loader').style.display = 'none';
          });

        // QR
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, canvas.width, canvas.height);
        if (code) {
          qrOutput.textContent = code.data;
          document.getElementById('qr_code').value = code.data; // Hidden input
        } else {
          qrOutput.textContent = "No QR code detected.";
        }
      };

      const reader = new FileReader();
      reader.onload = (e) => img.src = e.target.result;
      reader.readAsDataURL(file);
    });

    function extractStructuredData(text) {
      const lines = text.split('\n').map(line => line.trim());

      for (let i = 0; i < lines.length; i++) {
        const line = lines[i];

        // Extract match title (e.g., Manchester United v Olympique Lyonnais)
        if (!document.getElementById('title').value) {
          const titleLine = lines.find(line => /.+\sv\s.+/i.test(line));
          if (titleLine) {
            document.getElementById('title').value = titleLine;
          }
        }

        // Extract timestamp
        if (/^\d{2}\/\d{2}\/\d{4}/.test(line)) {
          document.getElementById('timestamp').value = line;
        }

        // Extract name
        if (/NAME/i.test(line)) {
          const nameMatch = lines.find(l => /[a-zA-Z]+\s+[a-zA-Z]+\s*-\s*\d+/.test(l));
          if (nameMatch) {
            document.getElementById('name').value = nameMatch.split('-')[0].trim();
          }
        }

        // Extract entrance
        if (/ENTRANCE/i.test(line)) {
          document.getElementById('entrance').value = lines[i + 1] || '';
        }

        // Extract section
        if (/SECTION/i.test(line)) {
          document.getElementById('section').value = lines[i + 1] || '';
        }

        // Extract row and seat
        if (/ROW/i.test(line)) {
          const rowSeatLine = lines[i + 1] || '';
          const parts = rowSeatLine.split(/\s+/);
          document.getElementById('row').value = parts[0] || '';
          document.getElementById('seat').value = parts[1] || '';
        }

        // Fallback: in case SEAT appears separately
        if (/SEAT/i.test(line)) {
          const seatOnly = lines[i + 1] || '';
          if (!document.getElementById('seat').value) {
            document.getElementById('seat').value = seatOnly;
          }
        }
      }
    }
  </script>


</body>

</html>