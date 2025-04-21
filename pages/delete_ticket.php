<?php
include 'conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM tickts WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirect with success flag
        header("Location: complete_tickets.php?deleted=1");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
