<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>




<?php
include 'db_connect.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add Attendance
    if (isset($_POST['add_attendance'])) {
        $emp_id = $_POST['emp_id'];
        $check_in = $_POST['check_in'];
        $check_out = $_POST['check_out'];
        $status = $_POST['status'];

        $query = "INSERT INTO attendance (emp_id,  check_in, check_out, status) 
                  VALUES ($emp_id,  '$check_in', '$check_out', '$status')";
        $conn->query($query);
    }

    // Update Attendance
    if (isset($_POST['update_attendance'])) {
        $id = $_POST['attendance_id'];
        $emp_id = $_POST['emp_id'];
        $check_in = $_POST['check_in'];
        $check_out = $_POST['check_out'];
        $status = $_POST['status'];

        $query = "UPDATE attendance SET 
                  emp_id=$emp_id,  
                  check_in='$check_in', 
                  check_out='$check_out', 
                  status='$status' 
                  WHERE id=$id";
        $conn->query($query);
    }

    // Delete Attendance
    if (isset($_POST['delete_attendance'])) {
        $id = $_POST['attendance_id'];
        $conn->query("DELETE FROM attendance WHERE id=$id");
    }

    header("Location: attendance.php");
    exit();
}

// Fetch all attendance records with employee names
// Current query:
$attendance = $conn->query("SELECT a.*, e.name as employee_name 
                           FROM attendance a 
                           JOIN employees e ON a.emp_id = e.emp_id");

// Add error checking:
if (!$attendance) {
    die("Query failed: " . $conn->error);
}

// Fetch employees for dropdown
$employees = $conn->query("SELECT * FROM employees");
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
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <link rel="stylesheet" href="assets/css/app.min.css">

    <!-- DataTable CSS -->
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- third party css -->
    <link href="assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- App css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="light-style" />
    <link href="assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />


    <style>
        /* Attendance Table Font Styling */
        #attendanceTable thead th {
            font-size: 18px;
            /* Larger font for headers */
            font-weight:bolder !important;
            /* Bold headers */
            color: #000000;
        }

        #attendanceTable tbody td {
            font-size: 16px;
            /* Slightly smaller for body */
           
            /* Bold body text */
            color:rgb(19, 19, 19);
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
                    <img src="assets/images/logo.png" alt="" height="16">
                </span>
                <span class="logo-sm">
                    <img src="assets/images/logo_sm.png" alt="" height="16">
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
                        <i class="fa-solid fa-tasks text-white"></i>
                        <span class="text-white">Employee_Task</span>
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
                            <span class="account-user-avatar">
                                <img src="assets/images/users/avatar-1.jpg" alt="user-image" class="rounded-circle">
                            </span>
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
                                    <img class="d-flex me-2 rounded-circle" src="assets/images/users/avatar-2.jpg" alt="Generic placeholder image" height="32">
                                    <div class="w-100">
                                        <h5 class="m-0 font-14">Erwin Brown</h5>
                                        <span class="font-12 mb-0">UI Designer</span>
                                    </div>
                                </div>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <div class="d-flex">
                                    <img class="d-flex me-2 rounded-circle" src="assets/images/users/avatar-5.jpg" alt="Generic placeholder image" height="32">
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
                    <?php while ($row = $attendance->fetch_assoc()): ?>
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
                                <button class="btn btn-primary btn-sm editAttendanceBtn"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn btn-danger btn-sm deleteAttendanceBtn"
                                    data-id="<?= $row['id'] ?>">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>


            <!-- Add Attendance Modal -->
            <div class="modal fade" id="addAttendanceModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <form method="POST" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Attendance Record</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Employee</label>
                                    <select name="emp_id" class="form-select" required>
                                        <option value="">-- Select Employee --</option>
                                        <?php while ($emp = $employees->fetch_assoc()): ?>
                                            <option value="<?= $emp['emp_id'] ?>"><?= $emp['name'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>Check In Time</label>
                                    <input type="time" name="check_in" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Check Out Time</label>
                                    <input type="time" name="check_out" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="Present">Present</option>
                                        <option value="Absent">Absent</option>
                                        <option value="Late">Late</option>
                                        <option value="Half Day">Half Day</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add_attendance" class="btn btn-primary">Add Record</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Attendance Modal -->
            <div class="modal fade" id="editAttendanceModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <form method="POST" class="modal-content">
                        <input type="hidden" name="attendance_id" id="edit_attendance_id">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Attendance Record</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Employee</label>
                                    <select name="emp_id" id="edit_emp_id" class="form-select" required>
                                        <option value="">-- Select Employee --</option>
                                        <?php
                                        // Reset pointer and fetch employees again for the dropdown
                                        $employees->data_seek(0);
                                        while ($emp = $employees->fetch_assoc()): ?>
                                            <option value="<?= $emp['emp_id'] ?>"><?= $emp['name'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>Check In Time</label>
                                    <input type="time" name="check_in" id="edit_check_in" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Check Out Time</label>
                                    <input type="time" name="check_out" id="edit_check_out" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Status</label>
                                    <select name="status" id="edit_status" class="form-select" required>
                                        <option value="Present">Present</option>
                                        <option value="Absent">Absent</option>
                                        <option value="Late">Late</option>
                                        <option value="Half Day">Half Day</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="update_attendance" class="btn btn-primary">Update Record</button>
                        </div>
                    </form>
                </div>
            </div>

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
        <script src="assets/js/vendor.min.js"></script>
        <script src="assets/js/app.min.js"></script>

        <!-- third party js -->
        <script src="assets/js/vendor/apexcharts.min.js"></script>
        <script src="assets/js/vendor/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="assets/js/vendor/jquery-jvectormap-world-mill-en.js"></script>
        <!-- third party js ends -->

        <!-- demo app -->
        <script src="assets/js/pages/demo.dashboard.js"></script>
        <!-- end demo js-->



        <script>
            $(document).ready(function() {
                $('#attendanceTable').DataTable({
                    responsive: true,
                    order: [
                        [2, 'desc']
                    ] // Default sort by date descending
                });
            });

            // Delete Attendance function
            document.querySelectorAll(".deleteAttendanceBtn").forEach(button => {
                button.addEventListener("click", (e) => {
                    e.preventDefault();
                    const attendanceId = button.dataset.id;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('deleteAttendanceId').value = attendanceId;
                            document.getElementById('deleteAttendanceForm').submit();
                        }
                    });
                });
            });

            // Edit Attendance Button Click Handler
            document.querySelectorAll('.editAttendanceBtn').forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const attendanceId = row.cells[0].textContent;
                    const employeeName = row.cells[1].textContent;
                    const date = row.cells[2].textContent;
                    const checkIn = row.cells[3].textContent;
                    const checkOut = row.cells[4].textContent;
                    const status = row.cells[5].textContent.trim();

                    // Find the employee ID (this assumes employee name is unique)
                    let empId = '';
                    const employeeOptions = document.querySelectorAll('#edit_emp_id option');
                    employeeOptions.forEach(option => {
                        if (option.textContent === employeeName) {
                            empId = option.value;
                        }
                    });

                    // Populate the edit modal
                    document.getElementById('edit_attendance_id').value = attendanceId;
                    document.getElementById('edit_emp_id').value = empId;
                    document.getElementById('edit_date').value = date;
                    document.getElementById('edit_check_in').value = checkIn !== '--' ? checkIn : '';
                    document.getElementById('edit_check_out').value = checkOut !== '--' ? checkOut : '';
                    document.getElementById('edit_status').value = status;

                    // Show the modal
                    const editModal = new bootstrap.Modal(document.getElementById('editAttendanceModal'));
                    editModal.show();
                });
            });
        </script>
</body>

</html>