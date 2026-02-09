<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "db.php";

// Agar aapke pas categories ka table alag hai, toh hum join use karenge 
// taaki category ka NAAM mil sake, sirf ID nahi.
$sql = "SELECT * FROM assessment_categories ORDER BY id DESC";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode([
        "status" => true,
        "data" => $data
    ]);
} else {
    echo json_encode([
        "status" => false,
        "data" => [],
        "message" => "No assessments found"
    ]);
}
?> 