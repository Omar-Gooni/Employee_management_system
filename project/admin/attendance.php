<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login/login.php");
    exit();
}
?>


<?php
include '../connection/db_connect.php';

// ============================
// 📅 Set Today's Date
// ============================
$today = date('Y-m-d');
$current_datetime = date('Y-m-d H:i:s');

// ============================
// 🔍 Check if attendance exists for today
// ============================
$checkAttendance = $conn->query("SELECT COUNT(*) as total FROM attendance WHERE attendance_date = '$today'");
$attendanceExists = ($checkAttendance->fetch_assoc()['total'] > 0);

// ============================
// 👥 Fetch Employees
// ============================
$employees = $conn->query("SELECT * FROM employees");

// ============================
// 📑 Fetch Today's Attendance
// ============================
$attendanceResult = $conn->query("SELECT a.*, e.name AS employee_name 
                                  FROM attendance a 
                                  JOIN employees e ON a.emp_id = e.emp_id 
                                  WHERE attendance_date = '$today'");

// ============================
// ➕ Handle Add Attendance
// ============================
if (isset($_POST['save_attendance'])) {
    $emp_ids = $_POST['emp_id'];

    foreach ($emp_ids as $emp_id) {
        $present = isset($_POST['present_' . $emp_id]);
        $absent = isset($_POST['absent_' . $emp_id]);

        if ($present) {
            $status = 'Present';
        } elseif ($absent) {
            $status = 'Absent';
        } else {
            continue;
        }

        $check_in = !empty($_POST['check_in_' . $emp_id]) ? $_POST['check_in_' . $emp_id] : null;
        $check_out = !empty($_POST['check_out_' . $emp_id]) ? $_POST['check_out_' . $emp_id] : null;

        // Check if attendance already exists
        $check = $conn->query("SELECT id FROM attendance WHERE emp_id = $emp_id AND attendance_date = '$today'");
        if ($check->num_rows > 0) {
            continue; // Skip if already exists
        }

        // Insert Attendance
        $stmt = $conn->prepare("INSERT INTO attendance (emp_id, date, attendance_date, check_in, check_out, status) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $emp_id, $current_datetime, $today, $check_in, $check_out, $status);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: attendance.php");
    exit();
}

// ============================
// ✏️ Handle Edit Attendance
// ============================
if (isset($_POST['update_attendance'])) {
    $attendance_id = $_POST['attendance_id'];
    $check_out = !empty($_POST['check_out']) ? $_POST['check_out'] : null;

    $stmt = $conn->prepare("UPDATE attendance SET check_out = ? WHERE id = ?");
    $stmt->bind_param("si", $check_out, $attendance_id);
    $stmt->execute();
    $stmt->close();

    header("Location: attendance.php");
    exit();
}


?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Attendance</title>
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
        /* Attendance Table Font Styling */
        #attendanceTable th,
        #attendanceTable td {
            white-space: nowrap !important;
            color: #000000;
            /* Keep all content on one line */
        }

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

        .side-nav-item {
            margin-bottom: 8px;
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

                <li class="side-nav-item">
                    <a href="admin.php" class="side-nav-link">
                        <i class="fa-solid fa-user-shield text-white"></i>
                        <span class="text-white">Admin</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="employee.php" class="side-nav-link">
                        <i class="fa-solid fa-users text-white"></i>
                        <span class="text-white">Employee</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="department.php" class="side-nav-link">
                        <i class="fa-solid fa-building text-white"></i>
                        <span class="text-white">Department</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="tasks.php" class="side-nav-link">
                        <i class="fa-solid fa-tasks text-white"></i>
                        <span class="text-white">Tasks</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="employee_task.php" class="side-nav-link">
                        <i class="fa-solid fa-clipboard-check text-white"></i>
                        <span class="text-white">Employee Tasks</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="attendance.php" class="side-nav-link">
                        <i class="fa-solid fa-clipboard-user text-white"></i>
                        <span class="text-white">Attendance</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="admin_leave.php" class="side-nav-link">
                        <i class="fa-solid fa-file-lines text-white"></i>
                        <span class="text-white">Leave Request</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="admin_issue.php" class="side-nav-link">
                        <i class="fa-solid fa-clipboard-list text-white"></i>
                        <span class="text-white">Issue</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="admin_report.php" class="side-nav-link">
                        <i class="fa-solid fa-chart-line text-white"></i>
                        <span class="text-white">Reports</span>
                    </a>
                </li>

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
                            <h4 class="page-title">Attendance Management</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Attendance Button -->
            <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">
                    <i class="fas fa-plus"></i> Add Attendance
                </button>
            </div>

            <!-- Attendance Table -->
            <!-- Attendance Table -->
            <div class="table-responsive">
                <table id="attendanceTable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $attendanceResult->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['employee_name'] ?></td>
                                <td><?= $row['date'] ?></td>
                                <td><?= $row['check_in'] ?: '--' ?></td>
                                <td><?= $row['check_out'] ?: '--' ?></td>
                                <td class="status-<?= strtolower(str_replace(' ', '-', $row['status'])) ?>">
                                    <?= $row['status'] ?>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editAttendanceModal<?= $row['id'] ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm deleteAttendanceBtn"
                                        data-id="<?= $row['id'] ?>">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>



            <!-- Add Attendance Modal -->
            <div class="modal fade" id="addAttendanceModal" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <form method="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Attendance for <?= date('Y-m-d') ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Employee</th>
                                                <th>Present</th>
                                                <th>Absent</th>
                                                <th>Check-In</th>
                                                <th>Check-Out</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $counter = 1;
                                            $employeeList = $conn->query("SELECT * FROM employees");
                                            while ($emp = $employeeList->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= $counter ?></td>
                                                    <td>
                                                        <?= htmlspecialchars($emp['name']) ?>
                                                        <input type="hidden" name="emp_id[]" value="<?= $emp['emp_id'] ?>">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" class="present" name="present_<?= $emp['emp_id'] ?>">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" class="absent" name="absent_<?= $emp['emp_id'] ?>">
                                                    </td>
                                                    <td>
                                                        <input type="time" name="check_in_<?= $emp['emp_id'] ?>" class="form-control check-in">
                                                    </td>
                                                    <td>
                                                        <input type="time" name="check_out_<?= $emp['emp_id'] ?>" class="form-control check-out">
                                                    </td>
                                                </tr>
                                            <?php
                                                $counter++;
                                            endwhile;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="save_attendance" class="btn btn-primary">Save Attendance</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>



            <!-- Edit Attendance Modal -->
            <?php
            $attendanceEdit = $conn->query("SELECT a.*, e.name AS employee_name 
                FROM attendance a 
                JOIN employees e ON a.emp_id = e.emp_id 
                WHERE attendance_date = '$today'");

            while ($row = $attendanceEdit->fetch_assoc()):
            ?>
                <!-- Edit Modal for ID <?= $row['id'] ?> -->
                <div class="modal fade" id="editAttendanceModal<?= $row['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Attendance (<?= htmlspecialchars($row['employee_name']) ?>)</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="attendance_id" value="<?= $row['id'] ?>">
                                    <div class="mb-3">
                                        <label>Date</label>
                                        <input type="text" class="form-control" value="<?= $row['attendance_date'] ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label>Status</label>
                                        <input type="text" class="form-control" value="<?= $row['status'] ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label>Check In</label>
                                        <input type="text" class="form-control" value="<?= $row['check_in'] ?: '--' ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label>Check Out (Edit)</label>
                                        <input type="time" name="check_out" class="form-control"
                                            value="<?= $row['check_out'] ?>">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="update_attendance" class="btn btn-primary">Save Changes</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>




            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->
            <!-- Hidden Delete Form -->
            <form id="deleteAttendanceForm" method="POST" style="display: none;">
                <input type="hidden" name="attendance_id" id="deleteAttendanceId">
                <input type="hidden" name="delete_attendance" value="1">
            </form>

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

        <script>
            document.getElementById('addAttendanceModal').addEventListener('shown.bs.modal', function() {
                document.querySelectorAll('#addAttendanceModal tbody tr').forEach(row => {
                    const present = row.querySelector('.present');
                    const absent = row.querySelector('.absent');
                    const checkIn = row.querySelector('.check-in');
                    const checkOut = row.querySelector('.check-out');

                    function updateInputs() {
                        if (present.checked) {
                            absent.checked = false;
                            checkIn.disabled = false;
                            checkOut.disabled = false;
                        } else if (absent.checked) {
                            present.checked = false;
                            checkIn.disabled = true;
                            checkOut.disabled = true;
                            checkIn.value = '';
                            checkOut.value = '';
                        } else {
                            checkIn.disabled = true;
                            checkOut.disabled = true;
                            checkIn.value = '';
                            checkOut.value = '';
                        }
                    }

                    present.addEventListener('change', updateInputs);
                    absent.addEventListener('change', updateInputs);

                    updateInputs();
                });
            });



            document.addEventListener('DOMContentLoaded', function() {
                const editForm = document.querySelector('#editAttendanceModal form');

                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You are about to update check-out times.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, update it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });
        </script>







</body>

</html>