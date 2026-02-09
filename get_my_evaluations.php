<?php
// 1. Security & Compatibility Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Connection: keep-alive"); 

// 2. Database Connection
include 'db.php'; 

if (!$conn) {
    echo json_encode(["status" => false, "message" => "Database connection failed"]);
    exit;
}

// 3. Get Parameters safely
$student_id = isset($_GET['evaluated_student_id']) ? $_GET['evaluated_student_id'] : '';
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';

if (empty($student_id) || empty($category_id)) {
    echo json_encode(["status" => false, "message" => "Missing Parameters"]);
    exit;
}

// 4. Sanitize Input
$s_id = mysqli_real_escape_string($conn, trim($student_id));
$c_id = mysqli_real_escape_string($conn, trim($category_id));

// 5. Query (Date select ki hai aur DESC order lagaya hai)
// Note: Agar aapke column ka naam 'created_at' ki jagah kuch aur hai to wo likhein
$sql = "SELECT se.evaluated_by, se.comments, se.created_at 
        FROM student_evaluation se
        INNER JOIN assessment_details ad ON se.assessment_detail_id = ad.id
        WHERE ad.assessment_category_id = '$c_id' 
        AND se.evaluated_student_id = '$s_id'
        ORDER BY se.id DESC"; 

$result = $conn->query($sql);
$feedbackData = [];

if ($result) {
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $feedbackData[] = [
                "evaluated_by" => $row['evaluated_by'],
                "comments" => $row['comments'],
                "evaluation_date" => $row['created_at'] // Date yahan add ho gayi
            ];
        }
        echo json_encode(["status" => true, "data" => $feedbackData]);
    } else {
        echo json_encode(["status" => false, "message" => "No records found", "data" => []]);
    }
} else {
    echo json_encode(["status" => false, "message" => "SQL Error: " . $conn->error]);
}

$conn->close();
?> 
