<?php
session_start();
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit;
}

$conn = new mysqli("localhost", "root", "", "tickting"); // replace with your DB name

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$imagePath = '';
$pdfPath = '';

// Upload Image
if (isset($_POST['upload_image']) && isset($_FILES['image'])) {
  $file = $_FILES['image'];
  $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
  $allowed = ['png', 'jpg', 'jpeg'];

  if (in_array(strtolower($ext), $allowed)) {
    $newName = uniqid() . "." . $ext;
    $path = "../upload/image/" . $newName;
    move_uploaded_file($file['tmp_name'], $path);
    $imagePath = "upload/image/" . $newName;

    $conn->query("INSERT INTO tickts (image_path, pdf_path, status) VALUES ('$imagePath', '', 0)");
    echo "Image uploaded successfully.";
  } else {
    echo "Invalid image file!";
  }
}

// Upload PDF
if (isset($_POST['upload_pdf']) && isset($_FILES['pdf'])) {
  $file = $_FILES['pdf'];
  $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

  if (strtolower($ext) === 'pdf') {
    $newName = uniqid() . ".pdf";
    $path = "../upload/pdf/" . $newName;
    move_uploaded_file($file['tmp_name'], $path);
    $pdfPath = "upload/pdf/" . $newName;

    $conn->query("INSERT INTO tickts (image_path, pdf_path, status) VALUES ('', '$pdfPath', 0)");
    echo "PDF uploaded successfully.";
  } else {
    echo "Invalid PDF file!";
  }
}
?>
