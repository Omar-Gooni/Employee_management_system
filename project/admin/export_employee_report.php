<?php
include '../connection/db_connect.php';

if (isset($_GET['filter_by']) && isset($_GET['query'])) {
    $filter = $_GET['filter_by'];
    $query = $_GET['query'];
    
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=employee_report.xls");
    echo "Name\tID\tEmail\tPresent Days\tAbsent Days\tTasks Assigned\tTasks Completed\n";

    $sql = "SELECT * FROM employees WHERE $filter = '$query'";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        $emp = $res->fetch_assoc();
        $emp_id = $emp['emp_id'];
        $present = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE emp_id=$emp_id AND status='Present'")->fetch_assoc()['count'];
        $absent = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE emp_id=$emp_id AND status='Absent'")->fetch_assoc()['count'];
        $assigned = $conn->query("SELECT COUNT(*) as count FROM employee_task WHERE employee_task_id=$emp_id")->fetch_assoc()['count'];
        $completed = $conn->query("SELECT COUNT(*) as count FROM employee_task WHERE employee_task_id=$emp_id AND status='Completed'")->fetch_assoc()['count'];

        echo "{$emp['name']}\t{$emp['emp_id']}\t{$emp['email']}\t$present\t$absent\t$assigned\t$completed\n";
    }
}
?>
