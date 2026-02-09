<?php
// get_criteria.php
header("Content-Type: application/json");
include "db.php";

// Check if presentation_id is provided
if (!isset($_GET['presentation_id']) || empty($_GET['presentation_id'])) {
    echo json_encode(["status" => "false", "message" => "Presentation ID is missing"]);
    exit;
}

$presentation_id = $_GET['presentation_id'];

// Database screenshot ke mutabiq columns: id, title, max_marks
$sql = "SELECT id, title, max_marks 
        FROM presentation_subcategories 
        WHERE presentation_id = '$presentation_id'";

$result = $conn->query($sql);
$criteria = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Data types ko sahi format mein convert karna (Optional but recommended)
        $row['id'] = (int)$row['id'];
        $row['max_marks'] = (float)$row['max_marks'];
        $criteria[] = $row;
    }
    echo json_encode(["status" => "true", "data" => $criteria]);
} else {
    echo json_encode(["status" => "false", "message" => "No criteria found for this presentation"]);
}
?>