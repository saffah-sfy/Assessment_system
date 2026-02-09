<?php
header('Content-Type: application/json');
include 'db.php'; // Apna database connection yahan include karein

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'] ?? '';
    $assessment_id = $_POST['assessment_id'] ?? '';
    $ai_analysis = $_POST['ai_analysis'] ?? '';

    if (!empty($student_id) && !empty($assessment_id)) {
        // Query: Hum us record ko update kar rahe hain jahan student aur assessment match kare
        $sql = "UPDATE student_evaluation 
                SET ai_analysis = ? 
                WHERE evaluated_student_id = ? AND assessment_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $ai_analysis, $student_id, $assessment_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "true", "message" => "AI Insight Updated"]);
        } else {
            echo json_encode(["status" => "false", "message" => "Database Error"]);
        }
    } else {
        echo json_encode(["status" => "false", "message" => "Missing Parameters"]);
    }
} else {
    echo json_encode(["status" => "false", "message" => "Invalid Request Method"]);
}
?>