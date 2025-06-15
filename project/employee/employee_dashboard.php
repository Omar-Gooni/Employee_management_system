<?php
session_start();
if (!isset($_SESSION['emp_id'])) {
    header("Location: ../login/login.php");
    exit();
}

include '../connection/db_connect.php';

// Get employee data
$employee_id = $_SESSION['emp_id'];
// Check if admin approved/rejected any leave request the employee hasn't seen yet
$pending_feedback = $conn->query("
    SELECT COUNT(*) as count 
    FROM leave_requests 
    WHERE employee_id = $employee_id 
      AND status IN ('Approved', 'Rejected') 
      AND is_seen_employee = FALSE
")->fetch_assoc()['count'];

$employee = $conn->query("SELECT * FROM employees WHERE emp_id = $employee_id")->fetch_assoc();

// Get employee task stats via employee_task join table

// Active (not completed) tasks
$active_tasks = $conn->query("
    SELECT COUNT(*) as count 
    FROM tasks t
    JOIN employee_task et ON t.task_id = et.task_id
    WHERE et.employee_task_id = $employee_id AND t.status != 'Completed'
")->fetch_assoc();

// Completed tasks
$completed_tasks = $conn->query("
    SELECT COUNT(*) as count 
    FROM tasks t
    JOIN employee_task et ON t.task_id = et.task_id
    WHERE et.employee_task_id = $employee_id AND t.status = 'Completed'
")->fetch_assoc();

// Attendance summary
$attendance = $conn->query("
    SELECT 
        SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present,
        SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent,
        SUM(CASE WHEN status = 'Late' THEN 1 ELSE 0 END) as late
    FROM attendance 
    WHERE emp_id = $employee_id
")->fetch_assoc();

// Recent tasks assigned to this employee
$recent_tasks = $conn->query("
    SELECT t.*, et.assigned_date, et.status AS assignment_status
    FROM tasks t
    JOIN employee_task et ON t.task_id = et.task_id
    WHERE et.employee_task_id = $employee_id
    ORDER BY t.end_date ASC 
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Employee Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Employee Dashboard" name="description" />
    <meta content="Your Company" name="author" />
    <link rel="shortcut icon" href="../assets/images/favicon.ico">
    <link rel="stylesheet" href="../assets/css/app.min.css">
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="../assets/js/vendor.min.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="light-style" />

    <style>
        .stat-card {
            text-align: center;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .stat-card i {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .stat-card h3 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .task-item {
            border-left: 3px solid #0d6efd;
            padding-left: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body class="loading" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <div class="wrapper">
        <!-- Left Sidebar - Same as admin but simplified -->
        <div class="leftside-menu">
            <a href="employee_dashboard.php" class="logo text-center logo-light">
                <span class="logo-lg">
                    <img src="../assets/images/logo.png" alt="" height="16">
                </span>
            </a>

            <ul class="side-nav">
                <li class="side-nav-item">
                    <a href="employee_dashboard.php" class="side-nav-link">
                        <i class="fa-solid fa-house text-white"></i>
                        <span class="text-white">Dashboard</span>
                    </a>
                </li>
                <br>
                <li class="side-nav-item">
                    <a href="employee_own_tasks.php" class="side-nav-link">
                        <i class="fa-solid fa-tasks text-white"></i>
                        <span class="text-white">My Tasks</span>
                    </a>
                </li>
                <br>
                <li class="side-nav-item">
                    <a href="employee_attendance.php" class="side-nav-link">
                        <i class="fa-solid fa-clipboard-user text-white"></i>
                        <span class="text-white">My Attendance</span>
                    </a>
                </li>
                <br>
                <li class="side-nav-item">
                    <a href="employee_department.php" class="side-nav-link">
                        <i class="fa-solid fa-building text-white"></i>
                        <span class="text-white">Departments</span>
                    </a>
                </li>
                <br>

                <li class="side-nav-item">
                    <a href="employee_leave.php" class="side-nav-link">
                        <i class="fa-solid fa-file-lines text-white"></i>
                        <span class="text-white">Leave Request</span>
                    </a>
                </li>
                <br>
                <li class="side-nav-item">
                    <a href="logout.php" class="side-nav-link">
                        <i class="mdi mdi-logout me-1 text-white"></i>
                        <span class="text-white">Logout</span>
                    </a>
                </li>

            </ul>
        </div>

        <div class="content-page">
            <div class="content">
                <!-- Topbar - Simplified for employee -->
                <div class="navbar-custom">
                    <ul class="list-unstyled topbar-menu float-end mb-0">
                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <?php if (isset($_SESSION['image']) && $_SESSION['image']): ?>
                                    <span class="account-user-avatar">
                                        <img src="../uploads/<?= $_SESSION['image'] ?>" alt="user-image" class="rounded-circle">
                                    </span>
                                <?php endif; ?>
                                <span>
                                    <span class="account-user-name"><?= $employee['name'] ?></span>
                                    <span class="account-position"><?= $employee['position'] ?></span>
                                </span>
                            </a>
                        </li>
                    </ul>
                    <button class="button-menu-mobile open-left">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </div>

                <!-- Start Content-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Employee Dashboard</h4>
                                <?php if ($pending_feedback > 0): ?>
                                    <div class="alert alert-info alert-dismissible fade show mt-2" role="alert">
                                        You have <?= $pending_feedback ?> new leave response<?= $pending_feedback > 1 ? 's' : '' ?> from admin.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card stat-card bg-primary text-white">
                                <div class="card-body">
                                    <i class="fas fa-tasks"></i>
                                    <h3><?= $active_tasks['count'] ?? 0 ?></h3>
                                    <p>Active Tasks</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card stat-card bg-success text-white">
                                <div class="card-body">
                                    <i class="fas fa-check-circle"></i>
                                    <h3><?= $completed_tasks['count'] ?? 0 ?></h3>
                                    <p>Completed Tasks</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card stat-card bg-info text-white">
                                <div class="card-body">
                                    <i class="fas fa-calendar-check"></i>
                                    <h3><?= $attendance['present'] ?? 0 ?></h3>
                                    <p>Days Present</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Summary -->
                    <div class="row mt-3">
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title mb-3">My Attendance Summary</h4>
                                    <div class="table-responsive">
                                        <table class="table table-centered table-hover mb-0">
                                            <tbody>
                                                <tr>
                                                    <td><i class="fas fa-check-circle text-success"></i> Present</td>
                                                    <td class="text-end"><?= $attendance['present'] ?? 0 ?></td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fas fa-times-circle text-danger"></i> Absent</td>
                                                    <td class="text-end"><?= $attendance['absent'] ?? 0 ?></td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fas fa-clock text-warning"></i> Late</td>
                                                    <td class="text-end"><?= $attendance['late'] ?? 0 ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Tasks -->
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title mb-3">My Recent Tasks</h4>
                                    <div class="table-responsive">
                                        <table class="table table-centered table-hover mb-0">
                                            <tbody>
                                                <?php while ($task = $recent_tasks->fetch_assoc()): ?>
                                                    <tr>
                                                        <td>
                                                            <h5 class="font-14 mb-1"><?= $task['title'] ?></h5>
                                                            <span class="text-muted font-13">Due: <?= date('M d, Y', strtotime($task['end_date'])) ?></span>
                                                        </td>
                                                        <td class="text-end">
                                                            <span class="badge bg-soft-<?=
                                                                                        $task['status'] == 'Completed' ? 'success' : ($task['status'] == 'In Progress' ? 'primary' : 'warning')
                                                                                        ?> text-<?=
                                                                                                $task['status'] == 'Completed' ? 'success' : ($task['status'] == 'In Progress' ? 'primary' : 'warning')
                                                                                                ?>">
                                                                <?= $task['status'] ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- bundle -->
    <script src="../assets/js/vendor.min.js"></script>
    <script src="../assets/js/app.min.js"></script>
</body>

</html>