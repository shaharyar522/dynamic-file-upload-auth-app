<?php
session_start();
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit;
}

$conn = new mysqli("localhost", "root", "", "tickting");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$imagePath = '';
$pdfPath = '';

if (isset($_POST['upload_files'])) {
  // Upload image
  if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $imageFile = $_FILES['image'];
    $imageExt = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
    $allowedImage = ['png', 'jpg', 'jpeg'];

    if (in_array($imageExt, $allowedImage)) {
      $imageName = uniqid() . "." . $imageExt;
      $imageFullPath = "../upload/image/" . $imageName;
      move_uploaded_file($imageFile['tmp_name'], $imageFullPath);
      $imagePath = "upload/image/" . $imageName;
    } else {
      echo "Invalid image file type!";
      exit;
    }
  }

  // Upload PDF
  if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
    $pdfFile = $_FILES['pdf'];
    $pdfExt = strtolower(pathinfo($pdfFile['name'], PATHINFO_EXTENSION));

    if ($pdfExt === 'pdf') {
      $pdfName = uniqid() . ".pdf";
      $pdfFullPath = "../upload/pdf/" . $pdfName;
      move_uploaded_file($pdfFile['tmp_name'], $pdfFullPath);
      $pdfPath = "upload/pdf/" . $pdfName;
    } else {
      echo "Invalid PDF file type!";
      exit;
    }
  }

  // Insert into database
  if ($imagePath !== '' && $pdfPath !== '') {
    $stmt = $conn->prepare("INSERT INTO tickts (image_path, pdf_path, status) VALUES (?, ?, 0)");
    $stmt->bind_param("ss", $imagePath, $pdfPath);
    $stmt->execute();
    $stmt->close();
    
    // Redirect back to dashboard with success query
    header("Location: dashboard.php?upload=success");
    exit;
  } else {
    echo "Both image and PDF files are required.";
  }
}
?>
