<?php
session_start();
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit;
}

// $conn = new mysqli("localhost", "root", "", "tickting");
// if ($conn->connect_error) {
//       die("Connection failed: " . $conn->connect_error);
// }

include('conn.php');

$pdfPath = null;
$imagePaths = [];

if (isset($_POST['upload_files'])) {
  $qr_code = $_POST['qr_code'] ?? '';
  $title = $_POST['title'] ?? '';
  $timestamp = $_POST['timestamp'] ?? '';
  $name = $_POST['name'] ?? '';
  $entrance = $_POST['entrance'] ?? '';
  $section = $_POST['section'] ?? '';
  $row = $_POST['row'] ?? '';
  $seat = $_POST['seat'] ?? '';

  // Handle PDF upload (optional)
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

  // Handle image uploads
  if (isset($_FILES['image'])) {
    $totalImages = count($_FILES['image']['name']);
    $allowedImage = ['png', 'jpg', 'jpeg'];

    for ($i = 0; $i < $totalImages; $i++) {
      if ($_FILES['image']['error'][$i] === 0) {
        $imageName = $_FILES['image']['name'][$i];
        $imageTmp = $_FILES['image']['tmp_name'][$i];
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        if (in_array($imageExt, $allowedImage)) {
          $uniqueName = uniqid() . "." . $imageExt;
          $targetPath = "../upload/image/" . $uniqueName;
          move_uploaded_file($imageTmp, $targetPath);
          $imagePaths[] = "upload/image/" . $uniqueName;
        }
      }
    }
  }

  // Save to database
  if (!empty($imagePaths)) {
    $stmt = $conn->prepare("INSERT INTO tickts (image_path, pdf_path, status, qr_code, title, timestamp, name, entrance, section, row, seat) VALUES (?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($imagePaths as $imgPath) {
      $stmt->bind_param("ssssssssss", $imgPath, $pdfPath, $qr_code, $title, $timestamp, $name, $entrance, $section, $row, $seat);
      $stmt->execute();
    }
    $stmt->close();
    header("Location: dashboard.php?upload=multi_success");
    exit;
  } elseif ($pdfPath !== null) {
    // Only PDF
    $stmt = $conn->prepare("INSERT INTO tickts (image_path, pdf_path, status, qr_code, title, timestamp, name, entrance, section, row, seat) VALUES (?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?)");
    $null = null;
    $stmt->bind_param("ssssssssss", $null, $pdfPath, $qr_code, $title, $timestamp, $name, $entrance, $section, $row, $seat);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?upload=pdf_success");
    exit;
  } else {
    header("Location: dashboard.php?upload=error_no_file");
    exit;
  }
}
?>
