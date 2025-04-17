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
  <style>
    /* RESET + BASE */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f2f5;
      color: #333;
    }

    /* HEADER */
    .header {
      background-color: #1d3557;
      color: white;
      padding: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .header h1 {
      font-size: 1.8rem;
    }
    .top-btns {
      display: flex;
      gap: 10px;
    }
    .top-btns button {
      background-color: #e63946;
      border: none;
      padding: 8px 14px;
      color: white;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
      transition: background 0.3s ease;
    }
    .top-btns button:first-child {
      background-color: #457b9d;
    }
    .top-btns button:hover {
      background-color: #a4161a;
    }

    /* CONTAINER */
    .container {
      max-width: 700px;
      margin: 40px auto;
      background: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .container h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #1d3557;
    }

    /* FORMS */
    .form-section {
      margin-bottom: 30px;
    }
    .form-section h3 {
      margin-bottom: 15px;
      color: #333;
    }
    input[type="file"] {
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
      width: 100%;
    }
    button[type="submit"] {
      background-color: #2a9d8f;
      border: none;
      padding: 10px 20px;
      color: white;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s ease;
      font-weight: 500;
    }
    button[type="submit"]:hover {
      background-color: #21867a;
    }

    /* SUBMITTED FILES BUTTON */
    .submitted-files {
      text-align: center;
    }

    /* MODAL */
    #passwordModal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }
    .modal-content {
      background: white;
      padding: 25px;
      border-radius: 8px;
      width: 300px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      text-align: center;
    }
    .modal-content input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    .modal-content button {
      background-color: #1d3557;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 6px;
      cursor: pointer;
      margin: 0 5px;
    }
    .modal-content button:hover {
      background-color: #0d2b4e;
    }

    #passwordMessage {
      margin-top: 10px;
      font-size: 0.9rem;
      color: green;
    }
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
  <h2>Welcome to Application Software as a Service</h2>

  <!-- Image Upload -->
  <div class="form-section">
    <h3>Upload Image</h3>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
      <input type="file" name="image" accept=".png,.jpg,.jpeg" required>
      <button type="submit" name="upload_image">Upload Image</button>
    </form>
  </div>

  <!-- PDF Upload -->
  <div class="form-section">
    <h3>Upload PDF</h3>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
      <input type="file" name="pdf" accept=".pdf" required>
      <button type="submit" name="upload_pdf">Upload PDF</button>
    </form>
  </div>

  <!-- Submitted Files View -->
  <div class="form-section submitted-files">
    <form action="submitted-files.php" method="GET">
      <button type="submit">View Submitted Files</button>
    </form>
  </div>
</div>

<!-- Password Modal -->
<div id="passwordModal">
  <div class="modal-content">
    <h3>Update Password</h3>
    <form id="updatePasswordForm">
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
    }).then(res => res.text())
      .then(data => {
        document.getElementById('passwordMessage').innerText = data;
        e.target.reset();
      });
  });
</script>

</body>
</html>
