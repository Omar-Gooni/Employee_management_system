<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login/login.php");
    exit();
}
?>




<?php
include '../connection/db_connect.php';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Reports</title>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


    <!-- DataTables & Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="../assets/css/app.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />


    <!-- third party css -->
    <link href="../assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- App css -->
    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="light-style" />
    <link href="../assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />


    <style>
        #employeeTable {
            font-size: 14px;
            color: #000 !important;
        }

        #employeeTable thead th {
            font-weight: 700 !important;
            background-color: #f8f9fa;
        }

        #employeeTable td {
            color: #000 !important;
            vertical-align: middle;
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
                    <a href="admin_report.php" class="side-nav-link">
                        <i class="fa-solid fa-chart-line text-white"></i>
                        <span class="text-white">Reports</span>
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
            <!-- End Sidebar -->

            <div class="clearfix"></div>

        </div>
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
                            <h4 class="page-title">Reports</h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- php -->

            <div class="container-fluid mt-4">
                <div class="row justify-content-center">
                    <!-- Employee Report -->
                    <div class="col-md-5">
                        <div class="card p-3">
                            <div class="card-header text-center font-bold-400">Employee Report</div>
                            <div class="card-body">
                                <!-- Add ID to Employee Report Form -->
                                 <div id="employeeNotification" style="display:none; color: red;  padding: 8px 12px; border-radius: 4px; margin-bottom: 10px;"></div>

                                <form id="employeeForm" method="GET" action="">
                                    <label>Search by:</label>
                                    <select name="filter_by" class="form-control mb-2">
                                        <option value="emp_id">ID</option>
                                        <option value="email">Email</option>
                                        <option value="phone">Phone</option>
                                    </select>
                                    <input type="text" name="query" id="queryInput" class="form-control mb-2" placeholder="Enter search keyword">
                                    <button type="submit" class="btn btn-primary w-100">Search</button>
                                </form>


                            </div>
                        </div>
                    </div>

                    <!-- Task Report -->
                    <div class="col-md-5">
                        <div class="card p-2">
                            <div class="card-header text-center">Task Report (By Date Range)</div>
                            <div class="card-body">
                                <div id="taskNotification" style="display:none; color:red;  padding: 8px 12px; border-radius: 4px; margin-bottom: 10px;"></div>

                                <!-- Add ID to Task Report Form -->
                                <form id="taskForm" method="GET" action="">
                                    <div class="row">
                                        <div class="col">
                                            <label>From:</label>
                                            <input type="date" name="from_date" id="fromDate" class="form-control">
                                        </div>
                                        <div class="col">
                                            <label>To:</label>
                                            <input type="date" name="to_date" id="toDate" class="form-control">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3 w-100">Filter</button>
                                </form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Results Section -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <?php
                    if (isset($_GET['filter_by']) && isset($_GET['query'])) {
                        $filter = $_GET['filter_by'];
                        $query = $_GET['query'];
                        $sql = "SELECT * FROM employees WHERE $filter = '$query'";
                        $res = $conn->query($sql);
                        if ($res->num_rows > 0) {
                            $emp = $res->fetch_assoc();
                            $emp_id = $emp['emp_id'];
                            $present = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE emp_id=$emp_id AND status='Present'")->fetch_assoc()['count'];
                            $absent = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE emp_id=$emp_id AND status='Absent'")->fetch_assoc()['count'];
                            $assigned = $conn->query("SELECT COUNT(*) as count FROM employee_task WHERE employee_task_id=$emp_id")->fetch_assoc()['count'];
                            $completed = $conn->query("SELECT COUNT(*) as count FROM employee_task WHERE employee_task_id=$emp_id AND status='Completed'")->fetch_assoc()['count'];

                            echo "<h5 class='mt-3'>Employee Report Result</h5>";
                            echo "<button class='btn btn-success mb-2' onclick=\"exportTableToExcel('employeeTable', 'employee_report')\">Export to Excel</button>";
                            echo "<table class='table table-bordered' id='employeeTable'>
<thead class='table-light'>
<tr><th>Name</th><th>ID</th><th>Email</th><th>Present</th><th>Absent</th><th>Assigned</th><th>Completed</th></tr>
</thead>
<tbody>
<tr>
<td>{$emp['name']}</td><td>{$emp['emp_id']}</td><td>{$emp['email']}</td><td>$present</td><td>$absent</td><td>$assigned</td><td>$completed</td>
</tr>
</tbody></table>";
                        } else {
                            echo "<p class='text-danger mt-3'>No employee found.</p>";
                        }
                        echo "<script>
                            setTimeout(function() {
                                window.location.href = 'admin_report.php';
                            }, 5000);
                        </script>";
                    }

                    if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
                        $from = $_GET['from_date'];
                        $to = $_GET['to_date'];
                        $query = "SELECT t.title, et.status, e.name FROM tasks t
JOIN employee_task et ON t.task_id = et.task_id
JOIN employees e ON et.employee_task_id = e.emp_id
WHERE t.start_date BETWEEN '$from' AND '$to'";
                        $res = $conn->query($query);
                        if ($res->num_rows > 0) {
                            echo "<h5 class='mt-5'>Task Report Result</h5>";
                            echo "<button class='btn btn-success mb-2' onclick=\"exportTableToExcel('taskTable', 'task_report')\">Export to Excel</button>";
                            echo "<table class='table table-bordered' id='taskTable'>
<thead class='table-light'><tr><th>Task</th><th>Employee</th><th>Status</th></tr></thead>
<tbody>";
                            while ($row = $res->fetch_assoc()) {
                                echo "<tr><td>{$row['title']}</td><td>{$row['name']}</td><td>{$row['status']}</td></tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "<p class='text-warning mt-3'>No tasks found in this range.</p>";
                        }
                        echo "<script>
                    setTimeout(function() {
                        window.location.href = 'admin_report.php';
                    }, 5000);
                </script>";
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>
    </div>





    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->



    </div>
    <!-- END wrapper -->

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

    <!-- JS Includes -->
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        function exportTableToExcel(tableID, filename = '') {
            var table = document.getElementById(tableID);
            var wb = XLSX.utils.table_to_book(table, {
                sheet: "Sheet 1"
            });
            XLSX.writeFile(wb, filename ? filename + ".xlsx" : "export.xlsx");
        }




     function showFormNotification(id, message) {
        const el = document.getElementById(id);
        el.textContent = message;
        el.style.display = 'block';

        // Hide after 3 seconds
        setTimeout(() => {
            el.style.display = 'none';
        }, 3000);
    }

    // Employee Form Validation
    document.getElementById('employeeForm').addEventListener('submit', function(e) {
        const query = document.getElementById('queryInput').value.trim();
        if (query === '') {
            e.preventDefault();
            showFormNotification('employeeNotification', 'Please enter a search keyword.');
        }
    });

    // Task Form Validation
    document.getElementById('taskForm').addEventListener('submit', function(e) {
        const from = document.getElementById('fromDate').value;
        const to = document.getElementById('toDate').value;
        if (from === '' || to === '') {
            e.preventDefault();
            showFormNotification('taskNotification', 'Please select both From and To dates.');
        }
    });
    </script>


</body>

</html>