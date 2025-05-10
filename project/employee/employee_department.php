<?php
session_start();
if (!isset($_SESSION['emp_id'])) {
    header("Location: ../login/login.php");
    exit();
}
?>


<?php
include '../connection/db_connect.php';

$employee_id = $_SESSION['emp_id'];
$employee = $conn->query("SELECT * FROM employees WHERE emp_id = $employee_id")->fetch_assoc();

// Fetch all admins
$result = $conn->query("SELECT * FROM departments");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Department</title>
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
        /* Updated Table Styling */
        #adminTable {
            font-size: 16px;
            /* Increased from default (you can adjust this value) */
            color: #000000;
            /* Black text */
        }

        #adminTable thead th {
            font-weight: bold !important;
            /* Bold headers */
            background-color: rgb(233, 235, 236);
            /* Light gray background for headers (optional) */
        }

        #adminTable td,
        #adminTable th {
            padding: 8px 12px;
            /* Better spacing */
        }
    </style>

</head>

<body class="loading" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <!-- Begin page -->
    <div class="wrapper">
        <!-- ========== Left Sidebar Start ========== -->
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
                    <a href="logout.php" class="side-nav-link">
                        <i class="mdi mdi-logout me-1 text-white"></i>
                        <span class="text-white">Logout</span>
                    </a>
                </li>
            </ul>
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
            <!-- end Topbar -->

            <!-- Start Content-->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                          
                            <h4 class="page-title">Department</h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- php -->






            <!-- Add Admin Button -->
            <div style="position: relative; margin-top: 20px;">
                <!-- Add Admin Button -->
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAdminModal" style="position: absolute; top: 0; right: 0;"> <i class="fas fa-plus"></i>Add Department</button>

                <!-- Admin Table -->
                <table id="adminTable" class="table table-bordered mt-3" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['department_id'] ?></td>
                                <td><?= $row['department_name'] ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm editBtn"
                                        data-id="<?= $row['department_id'] ?>"
                                        data-name="<?= $row['department_name'] ?>"
                                        data-bs-toggle="modal" data-bs-target="#editAdminModal">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm deleteBtn"
                                        data-id="<?= $row['department_id'] ?>">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <!-- Add Admin Modal -->
              

                <script>
                    // DataTable Initialization
                    $(document).ready(function() {
                        $('#adminTable').DataTable();
                    });

                    // Edit Admin Button
                 
                </script>

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
</body>

</html>