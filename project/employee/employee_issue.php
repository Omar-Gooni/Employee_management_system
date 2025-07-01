<?php
session_start();
if (!isset($_SESSION['emp_id'])) {
    header("Location: ../login/login.php");
    exit();
}

include '../connection/db_connect.php';

$employee_id = $_SESSION['emp_id'];

// ✅ Mark issues as seen when employee opens this page
$conn->query("
    UPDATE issues 
    SET is_seen_employee = TRUE 
    WHERE employee_id = $employee_id 
      AND status IN ('In Progress', 'Resolved', 'Rejected') 
      AND is_seen_employee = FALSE
");

// ✅ Handle new issue submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_issue'])) {
    $issue_type = $_POST['issue_type'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("
        INSERT INTO issues (employee_id, issue_type, description, status, is_seen_admin, submitted_at) 
        VALUES (?, ?, ?, 'Pending', FALSE, NOW())
    ");
    $stmt->bind_param("iss", $employee_id, $issue_type, $description);
    $stmt->execute();

    header("Location: employee_issue.php");
    exit();
}

// ✅ Fetch employee's issues
$issues = $conn->query("
    SELECT * FROM issues 
    WHERE employee_id = $employee_id 
    ORDER BY submitted_at DESC
");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>My Issue Requests</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/images/favicon.ico">
    <link rel="stylesheet" href="../assets/css/app.min.css">
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/icons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
        /* ✅ Apply scroll on small screens */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* ✅ Keep table full width and collapse borders */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* ✅ Prevent table columns from wrapping badly */
        table th,
        table td {
            white-space: nowrap;
        }

        /* ✅ Optional mobile adjustments */
        @media screen and (max-width: 600px) {

            table th,
            table td {
                font-size: 13px;
                padding: 6px 8px;
            }
        }

        /* Base alert styling */
        #leaveBlockAlert {
            padding: 15px 20px;
            border-radius: 5px;
            font-size: 16px;
            line-height: 1.4;
            margin-bottom: 15px;
        }

        /* ✅ Responsive adjustments */
        @media screen and (max-width: 768px) {
            #leaveBlockAlert {
                font-size: 14px;
                padding: 12px 16px;
            }
        }

        @media screen and (max-width: 480px) {
            #leaveBlockAlert {
                font-size: 13px;
                padding: 10px 14px;
            }
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
                    <a href="employee_issue.php" class="side-nav-link">
                        <i class="fa-solid fa-clipboard-list text-white"></i>
                        <span class="text-white">Issue</span>
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
                        <h5 class="page-title">Report Issue</h5>
                    </div>

                    <!-- Request Issue Button -->
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-success" id="requestIssueBtn">
                            <i class="fas fa-plus"></i> Report Issue
                        </button>
                    </div>

                    <!-- Leave Table -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <!-- <div id="leaveBlockAlert" class="alert alert-danger d-none">
                                    You already submitted a leave request in the last 30 days.
                                </div> -->

                                <table id="leaveTable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Issue Type</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Admin Comment</th>
                                            <th>Submitted At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $issues->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['issue_type']) ?></td>
                                                <td><?= htmlspecialchars($row['description']) ?></td>
                                                <td><?= htmlspecialchars($row['status']) ?></td>
                                                <td><?= htmlspecialchars($row['admin_comment']) ?></td>
                                                <td><?= $row['submitted_at'] ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Issue Request Modal -->
                    <div class="modal fade" id="issueModal" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Report Issue</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Issue Type</label>
                                        <select name="issue_type" class="form-select" required>
                                            <option value="" disabled selected hidden>-- Select Type of Issue --</option>
                                            <option value="Mushaar La’aan Ama Dib-u-Dhac">Mushaar La’aan Ama Dib-u-Dhac (Salary Issue)</option>
                                            <option value="Cabasho Maamul">Cabasho Maamul (Management Complaint)</option>
                                            <option value="Cabasho Shaqaalaha Kale">Cabasho Shaqaalaha Kale (Complaint About Other Employees)</option>
                                            <option value="Cabasho Qalab">Cabasho Qalab (Equipment Problem)</option>
                                            <option value="Cabasho Nidaam">Cabasho Nidaam (System Error)</option>
                                            <option value="Cabasho Kale">Cabasho Kale (Other)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" rows="4" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="submit_issue" class="btn btn-primary">Submit</button>
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
        // Open Modal
        document.getElementById('requestIssueBtn').addEventListener('click', function() {
            let modal = new bootstrap.Modal(document.getElementById('issueModal'));
            modal.show();
        });
    </script>

</body>

</html>