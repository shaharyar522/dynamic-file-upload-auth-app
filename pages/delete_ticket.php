<?php
include("conn.php");

if (isset($_GET['id']) && isset($_GET['confirmed'])) {
    $id = $_GET['id'];
    $imagePath = $_GET['image'];
    $pdfPath = $_GET['pdf'];

    // Delete image if exists
    // if (!empty($imagePath) && file_exists($imagePath)) {
    //     unlink($imagePath);
    // }
    
    // es main hum nay file_exits () hum unlik karay guy 

    if(!empty ($imagePath) && file_exists($imagePath)){
        unlink($imagePath);
    }

    // Delete PDF if exists
    if (!empty($pdfPath) && file_exists("upload/pdf/" . basename($pdfPath))) {
        unlink("upload/pdf/" . basename($pdfPath));
    }

    // Delete from database
    $sql = "DELETE FROM tickts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: complete_tickets.php?deleted=1");
    exit();
} else {
    header("Location: complete_tickets.php");
    exit();
}
?>
