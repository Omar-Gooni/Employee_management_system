<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login/login.php");
    exit();
}
?>


<?php
include '../connection/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start(); // make sure session is started
    include '../connection/db_connect.php';

    // Add Department
    if (isset($_POST['add_admin'])) {
        $name = $_POST['department_name'];
        $description = $_POST['description'];
        $location = $_POST['location'];
        $head = $_POST['head_of_department'] ?: 'NULL';

        if ($conn->query("INSERT INTO departments (department_name, description, location, head_of_department) 
                          VALUES ('$name', '$description', '$location', $head)")) {
            $_SESSION['feedback'] = [
                'icon' => 'success',
                'title' => 'Department Added',
                'text' => 'The department has been added successfully!'
            ];
        } else {
            $_SESSION['feedback'] = [
                'icon' => 'error',
                'title' => 'Insert Failed',
                'text' => 'Failed to add department.'
            ];
        }
    }

    // Update Department
    if (isset($_POST['update_admin'])) {
        $id = $_POST['department_id'];
        $name = $_POST['department_name'];
        $description = $_POST['description'];
        $location = $_POST['location'];
        $head = $_POST['head_of_department'] ?: 'NULL';

        if ($conn->query("UPDATE departments SET 
                            department_name='$name', 
                            description='$description', 
                            location='$location', 
                            head_of_department=$head 
                          WHERE department_id=$id")) {
            $_SESSION['feedback'] = [
                'icon' => 'success',
                'title' => 'Department Updated',
                'text' => 'The department has been updated successfully!'
            ];
        } else {
            $_SESSION['feedback'] = [
                'icon' => 'error',
                'title' => 'Update Failed',
                'text' => 'Could not update department.'
            ];
        }
    }

    // Delete Department
    if (isset($_POST['delete_admin'])) {
        $id = $_POST['department_id'];
        if ($conn->query("DELETE FROM departments WHERE department_id=$id")) {
            $_SESSION['feedback'] = [
                'icon' => 'success',
                'title' => 'Department Deleted',
                'text' => 'The department has been deleted successfully!'
            ];
        } else {
            $_SESSION['feedback'] = [
                'icon' => 'error',
                'title' => 'Delete Failed',
                'text' => 'Failed to delete department.'
            ];
        }
    }

    header("Location: department.php");
    exit();
}


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
        /* Add to your <style> section */
        .btn-success {


            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-success:hover {


            transform: translateY(-1px);
        }

        .btn-success:active {
            transform: translateY(0);
        }

        /* ✅ Make any table inside .table-responsive scroll horizontally if needed */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* ✅ General table styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* ✅ Make table cells stay in one line */
        table th,
        table td {
            white-space: nowrap;
        }

        /* ✅ Optional: Adjust font size and padding on small devices */
        @media screen and (max-width: 600px) {

            table th,
            table td {
                font-size: 13px;
                padding: 6px 8px;
            }

            .btn {
                padding: 4px 6px;
                font-size: 12px;
            }
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
                            <h4 class="mb-0">Departments</h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- php -->






            <!-- Add Admin Button -->
            <div style="position: relative; margin-top: 20px;">
                <!-- Add Admin Button -->
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <button class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                        <i class="fas fa-plus me-1"></i> Add Department
                    </button>
                </div>

                <!-- department Table -->
                <div class="table-responsive">
                    <table id="departmentTable" class="table table-bordered mt-3" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Location</th>
                                <th>Head of Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['department_id'] ?></td>
                                    <td><?= $row['department_name'] ?></td>
                                    <td><?= $row['description'] ?></td>
                                    <td><?= $row['location'] ?></td>
                                    <td>
                                        <?php
                                        // Optional: Display head name instead of ID
                                        if (!empty($row['head_of_department'])) {
                                            $head_id = $row['head_of_department'];
                                            $head_result = $conn->query("SELECT name FROM admin WHERE admin_id = $head_id");
                                            $head_name = ($head_result && $head_result->num_rows > 0)
                                                ? $head_result->fetch_assoc()['name']
                                                : 'Unknown';
                                            echo $head_name;
                                        } else {
                                            echo 'None';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm editBtn"
                                            data-id="<?= $row['department_id'] ?>"
                                            data-name="<?= $row['department_name'] ?>"
                                            data-description="<?= $row['description'] ?>"
                                            data-location="<?= $row['location'] ?>"
                                            data-head="<?= $row['head_of_department'] ?>"
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
                </div>


                <!-- Add Department Modal -->
                <div class="modal fade" id="addAdminModal" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5>Add Department</h5>
                            </div>
                            <div class="modal-body">
                                <input type="text" name="department_name" class="form-control mb-2" placeholder="Name" required>
                                <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
                                <input type="text" name="location" class="form-control mb-2" placeholder="Location">
                                <select name="head_of_department" class="form-select mb-2">
                                    <option value="">-- Select Head of Department --</option>
                                    <?php
                                    $admins = $conn->query("SELECT admin_id, name FROM admin");
                                    while ($admin = $admins->fetch_assoc()):
                                    ?>
                                        <option value="<?= $admin['admin_id'] ?>"><?= $admin['name'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="add_admin" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- Edit Department Modal -->
                <div class="modal fade" id="editAdminModal" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5>Edit Department</h5>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="department_id" id="edit-department_id">
                                <input type="text" name="department_name" id="edit-department_name" class="form-control mb-2" required>
                                <textarea name="description" id="edit-description" class="form-control mb-2" placeholder="Description"></textarea>
                                <input type="text" name="location" id="edit-location" class="form-control mb-2" placeholder="Location">
                                <select name="head_of_department" id="edit-head" class="form-select mb-2">
                                    <option value="">-- Select Head of Department --</option>
                                    <?php
                                    $admins->data_seek(0); // Reset pointer
                                    while ($admin = $admins->fetch_assoc()):
                                    ?>
                                        <option value="<?= $admin['admin_id'] ?>"><?= $admin['name'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="update_admin" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- Hidden Delete Form -->
                <form id="deleteForm" method="POST" style="display: none;">
                    <input type="hidden" name="department_id" id="deleteAdminId">
                    <input type="hidden" name="delete_admin" value="1">
                </form>

                <script>
                    // DataTable Initialization

                    $(document).ready(function() {
                        $('#departmentTable').DataTable({
                            scrollX: true
                        });
                    });
                    // Edit Admin Button
                    document.querySelectorAll(".editBtn").forEach(button => {
                        button.addEventListener("click", () => {
                            document.getElementById("edit-department_id").value = button.dataset.id;
                            document.getElementById("edit-department_name").value = button.dataset.name;
                            document.getElementById("edit-description").value = button.dataset.description;
                            document.getElementById("edit-location").value = button.dataset.location;
                            document.getElementById("edit-head").value = button.dataset.head;
                        });
                    });


                    // Delete Admin Button
                    document.querySelectorAll(".deleteBtn").forEach(button => {
                        button.addEventListener("click", (e) => {
                            e.preventDefault();
                            const department_id = button.dataset.id;

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
                                    document.getElementById('deleteAdminId').value = department_id;
                                    document.getElementById('deleteForm').submit();
                                }
                            });
                        });
                    });
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


        <?php if (isset($_SESSION['feedback'])): ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: '<?= $_SESSION['feedback']['icon'] ?>',
                    title: '<?= $_SESSION['feedback']['title'] ?>',
                    text: '<?= $_SESSION['feedback']['text'] ?>'
                });
            </script>
            <?php unset($_SESSION['feedback']); ?>
        <?php endif; ?>

</body>

</html>