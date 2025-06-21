<?php
session_start();
if (!isset($_SESSION['emp_id'])) {
    header("Location: ../login/login.php");
    exit();
}

include '../connection/db_connect.php';

$employee_id = $_SESSION['emp_id'];
// Mark admin responses as seen when employee opens this page
$conn->query("
    UPDATE leave_requests 
    SET is_seen_employee = TRUE 
    WHERE employee_id = $employee_id 
      AND status IN ('Approved', 'Rejected') 
      AND is_seen_employee = FALSE
");


// Handle new leave request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_leave'])) {
    $type = $_POST['leave_type'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $reason = $_POST['reason'];

    // ✅ STEP: Check if employee has submitted any request in last 30 days
    $check = $conn->prepare("
        SELECT COUNT(*) AS count 
        FROM leave_requests 
        WHERE employee_id = ? 
          AND request_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    ");
    $check->bind_param("i", $employee_id);
    $check->execute();
    $result = $check->get_result();
    $data = $result->fetch_assoc();

    // ❌ If a request was found in the last 30 days
    if ($data['count'] > 0) {
        echo "<script>
            alert('You already submitted a leave request in the last 30 days.');
            window.location.href = 'employee_leave.php';
        </script>";
        exit();
    }

    // ✅ Otherwise: Insert new request
    $stmt = $conn->prepare("INSERT INTO leave_requests (employee_id, leave_type, start_date, end_date, reason, status, is_seen_admin, request_date) VALUES (?, ?, ?, ?, ?, 'Pending', FALSE, NOW())");
    $stmt->bind_param("issss", $employee_id, $type, $start, $end, $reason);
    $stmt->execute();

    header("Location: employee_leave.php");
    exit();
}


$leaves = $conn->query("SELECT * FROM leave_requests WHERE employee_id = $employee_id ORDER BY start_date DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>My Leave Requests</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/images/favicon.ico">
    <link rel="stylesheet" href="../assets/css/app.min.css">
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/icons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
        #leaveTable {
            font-size: 14px;
            color: #000 !important;
        }

        #leaveTable thead th {
            font-weight: 700 !important;
            background-color: #f8f9fa;
        }

        #leaveTable td {
            vertical-align: middle;
            color: #000 !important;
        }
    </style>
</head>

<body class="loading" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false,"darkMode":false}'>

    <div class="wrapper">

        <!-- Sidebar -->
        <div class="leftside-menu">
            <a href="dashboard.php" class="logo text-center logo-light">
                <span class="logo-lg"><img src="../assets/images/logo.png" height="16"></span>
                <span class="logo-sm"><img src="../assets/images/logo_sm.png" height="16"></span>
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

        <!-- Content -->
        <div class="content-page">
            <div class="content">

                <!-- Topbar -->
                <div class="navbar-custom">
                    <ul class="list-unstyled topbar-menu float-end mb-0">
                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user" data-bs-toggle="dropdown" href="#">
                                <span class="account-user-avatar">
                                    <img src="../uploads/<?= $_SESSION['image'] ?? 'default.png' ?>" class="rounded-circle" height="32">
                                </span>
                                <span>
                                    <span class="account-user-name"><?= htmlspecialchars($_SESSION['name']) ?></span>
                                    <span class="account-position">Employee</span>
                                </span>
                            </a>
                        </li>
                    </ul>
                    <button class="button-menu-mobile open-left"><i class="mdi mdi-menu"></i></button>
                </div>

                <!-- Page Content -->
                <div class="container-fluid">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <form class="d-flex">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-light" id="dash-daterange">
                                    <span class="input-group-text bg-primary border-primary text-white"><i class="mdi mdi-calendar-range font-13"></i></span>
                                </div>
                                <a href="employee_leave.php" class="btn btn-primary ms-2"><i class="mdi mdi-autorenew"></i></a>
                                <a href="#" class="btn btn-primary ms-1"><i class="mdi mdi-filter-variant"></i></a>
                            </form>
                        </div>
                        <h4 class="page-title">My Leave Requests</h4>
                    </div>

                    <!-- Request Leave Button -->
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-success" id="requestLeaveBtn">
                            <i class="fas fa-plus"></i> Request Leave
                        </button>

                    </div>

                    <!-- Leave Table -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="leaveBlockAlert" class="alert alert-danger d-none">
                                    You already submitted a leave request in the last 30 days.
                                </div>

                                <table id="leaveTable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Dates</th>
                                            <th>Reason</th>
                                            <th>Status</th>
                                            <th>HR Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $leaves->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= $row['leave_type'] ?></td>
                                                <td><?= $row['start_date'] ?> to <?= $row['end_date'] ?></td>
                                                <td><?= $row['reason'] ?></td>
                                                <td><?= $row['status'] ?></td>
                                                <td><?= $row['hr_comment'] ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Request Modal -->
                    <div class="modal fade" id="leaveModal" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Request Leave</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label>Leave Type</label>
                                        <select name="leave_type" class="form-select" required>
                                            <option value="Sick">Sick</option>
                                            <option value="Vacation">Vacation</option>
                                            <option value="Personal">Personal</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Start Date</label>
                                        <input type="date" name="start_date" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>End Date</label>
                                        <input type="date" name="end_date" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Reason</label>
                                        <textarea name="reason" class="form-control" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="submit_leave" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div> <!-- container -->
            </div> <!-- content -->
        </div> <!-- content-page -->
    </div> <!-- wrapper -->

    <!-- Scripts -->
    <script src="../assets/js/vendor.min.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#leaveTable').DataTable({
                scrollX: true
            });
        });
        document.getElementById('requestLeaveBtn').addEventListener('click', function () {
        fetch('check_leave_limit.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'employee_id=<?= $employee_id ?>'
        })
        .then(response => response.json())
        .then(data => {
            if (data.allowed) {
                // Show the modal
                let modal = new bootstrap.Modal(document.getElementById('leaveModal'));
                modal.show();
            } else {
                // Show red alert on the page
                const alertBox = document.getElementById('leaveBlockAlert');
                alertBox.textContent = data.message;
                alertBox.classList.remove('d-none');

                setTimeout(() => {
                    alertBox.classList.add('d-none');
                }, 4000);
            }
        });
    });
    </script>

</body>

</html>