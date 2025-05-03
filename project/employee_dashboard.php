<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['emp_id'])) {
    header("Location: login.php");
    exit();
}

$emp_id = $_SESSION['emp_id'];
$name = $_SESSION['name'];

// Attendance records
$attendance = $conn->query("SELECT * FROM attendance WHERE emp_id = $emp_id");

// Task records
$tasks = $conn->query("SELECT * FROM employee_task WHERE emp_id = $emp_id");

// Handle task status update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['task_id'], $_POST['status'])) {
    $task_id = $conn->real_escape_string($_POST['task_id']);
    $status = $conn->real_escape_string($_POST['status']);

    $conn->query("UPDATE employee_task SET status = '$status' WHERE task_id = $task_id AND emp_id = $emp_id");
    header("Location: employee_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 220px;
            background-color: #343a40;
            padding-top: 30px;
            color: white;
        }
        .sidebar a {
            color: white;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main {
            flex: 1;
            padding: 30px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center">Employee Panel</h4>
    <a href="employee_dashboard.php">Dashboard</a>
    <a href="logout.php">Logout</a>
</div>

<!-- Main Content -->
<div class="main">
    <h2 class="mb-4">Welcome, <?php echo htmlspecialchars($name); ?></h2>

    <!-- Attendance Section -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">My Attendance</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $attendance->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                            <td><?= htmlspecialchars($row['time_in']) ?></td>
                            <td><?= htmlspecialchars($row['time_out']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Task Section -->
    <div class="card">
        <div class="card-header bg-success text-white">My Tasks</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($task = $tasks->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['task_name']) ?></td>
                            <td><?= htmlspecialchars($task['description']) ?></td>
                            <td><?= htmlspecialchars($task['status']) ?></td>
                            <td>
                                <form method="POST" class="d-flex">
                                    <input type="hidden" name="task_id" value="<?= $task['task_id'] ?>">
                                    <select name="status" class="form-select me-2" required>
                                        <option value="Pending" <?= $task['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="In Progress" <?= $task['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                        <option value="Completed" <?= $task['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>
