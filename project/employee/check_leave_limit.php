<?php
include '../connection/db_connect.php';

$employee_id = $_POST['employee_id'] ?? 0;

$response = ['allowed' => true];

$stmt = $conn->prepare("
    SELECT COUNT(*) AS count 
    FROM leave_requests 
    WHERE employee_id = ? 
      AND request_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data['count'] > 0) {
    $response['allowed'] = false;
    $response['message'] = "You already submitted a leave request in the last 30 days.";
}

header('Content-Type: application/json');
echo json_encode($response);
