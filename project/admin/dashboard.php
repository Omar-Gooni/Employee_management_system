<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login/login.php");
    exit();
}
?>




<?php
include '../connection/db_connect.php';
// Count new leave requests not seen by admin
// ✅ Count only unseen leave requests
$new_requests = $conn->query("
    SELECT COUNT(*) AS count 
    FROM leave_requests 
    WHERE is_seen_admin = FALSE
")->fetch_assoc()['count'];

// Reactivate employees whose leave has ended
$conn->query("
    UPDATE employees 
    SET status = 'Active' 
    WHERE emp_id IN (
        SELECT employee_id 
        FROM leave_requests 
        WHERE end_date < CURDATE() 
          AND status = 'Approved'
    ) 
    AND status = 'Inactive'
");




// Fetch counts for dashboard
$admin_count = $conn->query("SELECT COUNT(*) as count FROM admin")->fetch_assoc()['count'];
$employee_count = $conn->query("SELECT COUNT(*) as count FROM employees")->fetch_assoc()['count'];
$department_count = $conn->query("SELECT COUNT(*) as count FROM departments")->fetch_assoc()['count'];
$active_tasks = $conn->query("SELECT COUNT(*) as count FROM tasks WHERE status != 'Completed'")->fetch_assoc()['count'];

// Fetch recent employees
$recent_employees = $conn->query("SELECT * FROM employees ORDER BY date_joined DESC LIMIT 5");

// Fetch upcoming tasks
$upcoming_tasks = $conn->query("
    SELECT t.*, e.name AS employee_name, et.assigned_date, et.status AS assignment_status
    FROM tasks t
    JOIN employee_task et ON t.task_id = et.task_id
    JOIN employees e ON et.employee_task_id = e.emp_id
    WHERE t.end_date >= CURDATE()
    ORDER BY t.end_date ASC
    LIMIT 5
");


// Fetch attendance summary
$attendance_summary = $conn->query("SELECT 
                                   SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present,
                                   SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent,
                                   SUM(CASE WHEN status = 'Late' THEN 1 ELSE 0 END) as late
                                   FROM attendance 
                                   WHERE date = CURDATE()")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico">

    <link rel="stylesheet" href="../assets/css/app.min.css">

    <!-- DataTable CSS -->
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">

    <script src="../assets/js/vendor.min.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- third party css -->
    <link href="../assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- App css -->
    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="light-style" />
    <link href="../assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />

    <style>
        body {
            font-size: 20px;
            /* Increase this value to make all text larger */
        }

        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 20px;
        }

        .stat-card {
            text-align: center;
        }

        .stat-card i {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .stat-card h3 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .stat-card p {
            color: #6c757d;
            margin-bottom: 0;
        }

        .table th,
        .table td {
            padding: 12px;
        }

        /* Wrap everything in a flex column */
        .leftside-menu {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            /* prevent outer scroll */
        }

        /* Keep logo fixed at the top */
        .leftside-menu .logo {
            padding: 12px 0;
            flex-shrink: 0;
            background-color: #2c3e50;
            /* optional: adjust your theme */
            text-align: center;
            z-index: 2;
        }

        /* Make side menu scrollable */
        .leftside-menu .side-nav {
            flex: 1 1 auto;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 10px 0;
        }

        /* Optional: customize scrollbar */
        .leftside-menu ul.side-nav::-webkit-scrollbar {
            width: 1px;
        }

        .leftside-menu ul.side-nav::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 4px;
        }
    </style>
</head>

<body class="loading" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <!-- Begin page -->
    <div class="wrapper">
        <!-- ========== Left Sidebar Start ========== -->
        <div class="leftside-menu">

            <!-- LOGO -->
            <a href="dashboard.php" class="logo text-center logo-light">
                <span class="logo-lg">
                    <img src="../assets/images/logo.png" alt="" height="16">
                </span>
                <span class="logo-sm">
                    <img src="../assets/images/logo_sm.png" alt="" height="16">
                </span>
            </a>

            <!--- Sidemenu -->
            <ul class="side-nav">
                <li class="side-nav-item">
                    <a href="dashboard.php" class="side-nav-link">
                        <i class="fa-solid fa-house text-white"></i>
                        <span class="text-white">Dashboard</span>
                    </a>
                </li>
                <br>
                <li class="side-nav-item">
                    <a href="admin.php" class="side-nav-link">
                        <i class="fa-solid fa-user-shield text-white"></i>
                        <span class="text-white">Admin</span>
                    </a>
                </li>
                <br>
                <li class="side-nav-item">
                    <a href="employee.php" class="side-nav-link">
                        <i class="fa-solid fa-users text-white"></i>
                        <span class="text-white">Employee</span>
                    </a>
                </li>
                <br>
                <li class="side-nav-item">
                    <a href="department.php" class="side-nav-link">
                        <i class="fa-solid fa-building text-white"></i>
                        <span class="text-white">Department</span>
                    </a>
                </li>
                <br>
                <li class="side-nav-item">
                    <a href="tasks.php" class="side-nav-link">
                        <i class="fa-solid fa-tasks text-white"></i>
                        <span class="text-white">Tasks</span>
                    </a>
                </li>
                <br>
                <li class="side-nav-item">
                    <a href="employee_task.php" class="side-nav-link">
                        <i class="fa-solid fa-clipboard-check text-white"></i>
                        <span class="text-white">Employee Tasks</span>
                    </a>
                </li>
                <br>
                <li class="side-nav-item">
                    <a href="attendance.php" class="side-nav-link">
                        <i class="fa-solid fa-clipboard-user text-white"></i>
                        <span class="text-white">Attendance</span>
                    </a>
                </li>
                <br>
                <li class="side-nav-item">
                    <a href="admin_leave.php" class="side-nav-link">
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
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <!-- Start Page Content here -->
        <div class="content-page">
            <div class="content">
                <!-- Topbar Start -->
                <div class="navbar-custom">
                    <ul class="list-unstyled topbar-menu float-end mb-0">




                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                                aria-expanded="false">
                                <?php if (isset($_SESSION['image']) && $_SESSION['image']): ?>
                                    <span class="account-user-avatar">
                                        <img src="../uploads/<?= $_SESSION['image'] ?>" alt="user-image" class="rounded-circle">
                                    </span>
                                <?php endif; ?>
                                <span>
                                    <span>
                                        <span class="account-user-name"><?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                                        <span class="account-position"><?php echo htmlspecialchars($_SESSION['role']); ?></span>
                                    </span>
                                </span>
                            </a>

                        </li>
                    </ul>
                    <button class="button-menu-mobile open-left">
                        <i class="mdi mdi-menu"></i>
                    </button>
                    <div class="app-search dropdown d-none d-lg-block">


                        <div class="dropdown-menu dropdown-menu-animated dropdown-lg" id="search-dropdown">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h5 class="text-overflow mb-2">Found <span class="text-danger">17</span> results</h5>
                            </div>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="uil-notes font-16 me-1"></i>
                                <span>Analytics Report</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="uil-life-ring font-16 me-1"></i>
                                <span>How can I help you?</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="uil-cog font-16 me-1"></i>
                                <span>User profile settings</span>
                            </a>

                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow mb-2 text-uppercase">Users</h6>
                            </div>

                            <div class="notification-list">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="d-flex">
                                        <img class="d-flex me-2 rounded-circle" src="../assets/images/users/avatar-2.jpg" alt="Generic placeholder image" height="32">
                                        <div class="w-100">
                                            <h5 class="m-0 font-14">Erwin Brown</h5>
                                            <span class="font-12 mb-0">UI Designer</span>
                                        </div>
                                    </div>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="d-flex">
                                        <img class="d-flex me-2 rounded-circle" src="../assets/images/users/avatar-5.jpg" alt="Generic placeholder image" height="32">
                                        <div class="w-100">
                                            <h5 class="m-0 font-14">Jacob Deo</h5>
                                            <span class="font-12 mb-0">Developer</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end Topbar -->

                <!-- Start Content-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <form class="d-flex">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-light" id="dash-daterange">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="mdi mdi-calendar-range font-13"></i>
                                            </span>
                                        </div>
                                        <a href="javascript: void(0);" class="btn btn-primary ms-2">
                                            <i class="mdi mdi-autorenew"></i>
                                        </a>
                                        <a href="javascript: void(0);" class="btn btn-primary ms-1">
                                            <i class="mdi mdi-filter-variant"></i>
                                        </a>
                                    </form>
                                </div>
                                <h4 class="page-title">Dashboard</h4>
                                <?php if ($new_requests > 0): ?>
                                    <div class="alert alert-warning alert-dismissible fade show mt-2" role="alert">
                                        <strong>🔔 New Alert:</strong> You have <?= $new_requests ?> new leave request<?= $new_requests > 1 ? 's' : '' ?>.
                                        <a href="admin_leave.php" class="btn btn-sm btn-primary ms-2">Review Now</a>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="row">
                        <div class="col-md-6 col-xl-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <i class="fas fa-user-shield text-primary"></i>
                                    <h3><?= $admin_count ?></h3>
                                    <p>Admins</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <i class="fas fa-users text-success"></i>
                                    <h3><?= $employee_count ?></h3>
                                    <p>Employees</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <i class="fas fa-building text-info"></i>
                                    <h3><?= $department_count ?></h3>
                                    <p>Departments</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <i class="fas fa-tasks text-warning"></i>
                                    <h3><?= $active_tasks ?></h3>
                                    <p>Active Tasks</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Summary -->
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title mb-3">Today's Attendance</h4>
                                    <div class="table-responsive">
                                        <table class="table table-centered table-hover mb-0">
                                            <tbody>
                                                <tr>
                                                    <td><i class="fas fa-check-circle text-success"></i> Present</td>
                                                    <td class="text-end"><?= $attendance_summary['present'] ?? 0 ?></td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fas fa-times-circle text-danger"></i> Absent</td>
                                                    <td class="text-end"><?= $attendance_summary['absent'] ?? 0 ?></td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fas fa-clock text-warning"></i> Late</td>
                                                    <td class="text-end"><?= $attendance_summary['late'] ?? 0 ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Employees -->
                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title mb-3">Recent Employees</h4>
                                    <div class="table-responsive">
                                        <table class="table table-centered table-hover mb-0">
                                            <tbody>
                                                <?php while ($emp = $recent_employees->fetch_assoc()): ?>
                                                    <tr>
                                                        <td>
                                                            <h5 class="font-14 mb-1"><?= $emp['name'] ?></h5>
                                                            <span class="text-muted font-13"><?= $emp['position'] ?></span>
                                                        </td>
                                                        <td class="text-end">
                                                            <span class="badge bg-soft-success text-success"><?= $emp['status'] ?></span>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming Tasks -->
                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title mb-3">Upcoming Tasks</h4>
                                    <div class="table-responsive">
                                        <table class="table table-centered table-hover mb-0">
                                            <tbody>
                                                <?php while ($task = $upcoming_tasks->fetch_assoc()): ?>
                                                    <tr>
                                                        <td>
                                                            <h5 class="font-14 mb-1"><?= $task['title'] ?></h5>
                                                            <span class="text-muted font-13">Due: <?= date('M d, Y', strtotime($task['end_date'])) ?></span>
                                                        </td>
                                                        <td class="text-end">
                                                            <span class="badge bg-soft-primary text-primary"><?= $task['status'] ?></span>
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
                <!-- container -->
            </div>
            <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © Employee Management System
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end footer-links d-none d-md-block">
                                <a href="javascript: void(0);">About</a>
                                <a href="javascript: void(0);">Support</a>
                                <a href="javascript: void(0);">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->
        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

        <!-- bundle -->
        <script src="../assets/js/vendor.min.js"></script>
        <script src="../assets/js/app.min.js"></script>

        <!-- third party js -->
        <script src="../assets/js/vendor/apexcharts.min.js"></script>
        <script src="../assets/js/vendor/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="../assets/js/vendor/jquery-jvectormap-world-mill-en.js"></script>
        <!-- third party js ends -->

        <!-- demo app -->
        <script src="../assets/js/pages/demo.dashboard.js"></script>
        <!-- end demo js-->
</body>

</html>