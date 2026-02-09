<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once 'db.php';

date_default_timezone_set('Asia/Karachi');
$current_time = date('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Flutter se 'student_id' aur 'device_status' receive kar rahe hain
    $student_id = isset($_POST['student_id']) ? mysqli_real_escape_string($conn, $_POST['student_id']) : '';
    $status = isset($_POST['device_status']) ? mysqli_real_escape_string($conn, $_POST['device_status']) : 'Ready';

    if (empty($student_id)) {
        echo json_encode(["status" => "false", "message" => "Student ID is missing"]);
        exit();
    }

    // ON DUPLICATE KEY UPDATE tabhi kaam karega jab student_id UNIQUE hogi
    $sql = "INSERT INTO student_sync_logs (student_id, status, sync_time) 
            VALUES ('$student_id', '$status', '$current_time') 
            ON DUPLICATE KEY UPDATE 
            status = '$status', 
            sync_time = '$current_time'";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "true", "message" => "Sync success"]);
    } else {
        echo json_encode(["status" => "false", "message" => mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "false", "message" => "POST method required"]);
}
mysqli_close($conn);
?>