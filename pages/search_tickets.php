<?php
include("conn.php");

$date = $_POST['date'];
$badge_id = $_POST['ticket_number'];

$date = $conn->real_escape_string($date);
$badge_id = $conn->real_escape_string($badge_id);
$sql = "SELECT * FROM tickts WHERE (date = '$date' OR badge_id = '$badge_id') AND pdf_path IS NOT NULL";

$result = $conn->query($sql);

$tickets = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tickets[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $tickets]);
} else {
   echo json_encode(['status' => 'no_results', 'data' => []]);
}
?>
