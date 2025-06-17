<?php
include '../connection/db_connect.php';

if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
    $from = $_GET['from_date'];
    $to = $_GET['to_date'];

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=task_report.xls");
    echo "Task Title\tEmployee Name\tStatus\n";

    $query = "
        SELECT t.title, et.status, e.name
        FROM tasks t
        JOIN employee_task et ON t.task_id = et.task_id
        JOIN employees e ON et.employee_task_id = e.emp_id
        WHERE t.start_date BETWEEN '$from' AND '$to'
    ";
    $res = $conn->query($query);

    while ($row = $res->fetch_assoc()) {
        echo "{$row['title']}\t{$row['name']}\t{$row['status']}\n";
    }
}
?>
