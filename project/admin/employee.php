<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login/login.php");
    exit();
}
?>





<?php
include '../connection/db_connect.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add Employee
    if (isset($_POST['add_employee'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $gender = $_POST['gender'];
        $department_id = $_POST['department_id'] ?: 'NULL'; // Handle NULL case

        $status = $_POST['status'];
        $password = $_POST['password'];
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = '../uploads/' . $image_name;
        move_uploaded_file($image_tmp, $image_path);


        $query = "INSERT INTO employees (name, email, phone, gender, department_id, status, password , image) 
                  VALUES ('$name', '$email', '$phone', '$gender', $department_id, '$status' , '$password' , '$image_name')";
        $conn->query($query);
    }


    // Update Employee
    if (isset($_POST['update_employee'])) {
        $id = $_POST['emp_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $gender = $_POST['gender'];
        $department_id = $_POST['department_id'] ?: 'NULL';

        $status = $_POST['status'];
        $password = $_POST['password'];
     


        $query = "UPDATE employees SET 
              name='$name', 
              email='$email', 
              phone='$phone', 
              gender='$gender', 
              department_id=$department_id, 
           
              status='$status'";
             

        if (!empty($_FILES['image']['name'])) {
            $image_name = $_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_path = '../uploads/' . $image_name;
            move_uploaded_file($image_tmp, $image_path);
            $query .= ", image='$image_name'";
        }

        $query .= " WHERE emp_id=$id";
        $conn->query($query);
    }

    // Delete Admin
    if (isset($_POST['delete_admin'])) {
        $id = $_POST['emp_id'];
        $conn->query("DELETE FROM employees WHERE emp_id=$id");
    }

    header("Location: employee.php");
    exit();
}

// Fetch all employees
$employees = $conn->query("SELECT e.*, d.department_name as department_name 
                          FROM employees e 
                          LEFT JOIN departments d ON e.department_id = d.department_id");

// Fetch departments for dropdown
$departments = $conn->query("SELECT * FROM departments");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Employee</title>
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
                                <br> <br>
                            </div>
                            <h4 class="page-title">Employee</h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- php -->






            <!-- Add Admin Button -->
            <div class="d-flex justify-content-end mb-3"> <!-- Changed this line -->
                <!-- Add Employee Button -->
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    <i class="fas fa-plus"></i> Add Employee
                </button>
            </div>

            <!-- employeee Table -->
            <div class="table-responsive">
                <table id="employeeTable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>

                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $employees->fetch_assoc()): ?>
                            <tr>

                                <td><?= $row['name'] ?></td>
                                <td><?= $row['email'] ?></td>
                                
                                <td><?= $row['phone'] ?></td>
                                <td><?= $row['gender'] ?></td>
                                <td><?= $row['department_name'] ?? '' ?></td>


                                <td><?= $row['status'] ?></td>
                                <td>
                                    <img class="emp_img" src="../uploads/<?= $row['image'] ?>" alt="emp Image" style="width:30px; height:30px; border-radius: 50%;">
                                </td>
                                <td style="white-space: nowrap;">
                                    <button
                                        class="btn btn-primary btn-sm editBtn"
                                        data-emp_id="<?= $row['emp_id'] ?>"
                                        data-name="<?= $row['name'] ?>"
                                        data-email="<?= $row['email'] ?>"
                                       
                                        data-phone="<?= $row['phone'] ?>"
                                        data-department_id="<?= $row['department_id'] ?>"
                                        data-status="<?= $row['status'] ?>"
                                        data-gender="<?= $row['gender'] ?>"
                                        data-image="<?= $row['image'] ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editEmployeeModal">
                                        <i class="fas fa-edit"></i>
                                    </button>



                                    <button class="btn btn-danger btn-sm deleteBtn d-inline-block" data-id="<?= $row['emp_id'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form action="id_card_emp.php" method="POST" target="_blank" style="display: inline;">
                                        <input type="hidden" name="id" value="<?= $row['emp_id'] ?>">
                                        <button type="submit" class="btn btn-success btn-sm" title="Print ID Card">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            </div>
            <!-- Add employee Modal -->
            <div class="modal fade" id="addEmployeeModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <form method="POST" class="modal-content" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Employee</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label d-block">Gender</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Male" required>
                                        <label class="form-check-label" for="genderMale">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Female">
                                        <label class="form-check-label" for="genderFemale">Female</label>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Department</label>
                                    <select name="department_id" class="form-select">
                                        <option value="">-- Select Department --</option>
                                        <?php while ($dept = $departments->fetch_assoc()): ?>
                                            <option value="<?= $dept['department_id'] ?>"><?= $dept['department_name'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label>Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                       
                                    </select>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label>image</label>
                                    <input type="file" name="image" class="form-control mb-2" accept="image/*" required>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add_employee" class="btn btn-primary">Add Employee</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Employee Modal -->
            <div class="modal fade" id="editEmployeeModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <form method="POST" class="modal-content" enctype="multipart/form-data">
                        <input type="hidden" name="emp_id" id="edit_emp_id">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Employee</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Name</label>
                                    <input type="text" name="name" id="edit_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" id="edit_email" class="form-control" required>
                                </div>
                         
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Phone</label>
                                    <input type="text" name="phone" id="edit_phone" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label d-block">Gender</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Male" required>
                                        <label class="form-check-label" for="genderMale">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Female" required>
                                        <label class="form-check-label" for="genderFemale">Female</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Department</label>
                                    <select name="department_id" id="edit_department_id" class="form-select">
                                        <option value="">-- Select Department --</option>
                                        <?php
                                        $departments->data_seek(0);
                                        while ($dept = $departments->fetch_assoc()): ?>
                                            <option value="<?= $dept['department_id'] ?>"><?= $dept['department_name'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Status</label>
                                    <select name="status" id="edit_status" class="form-select" required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                      
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Image</label>
                                    <img id="current-image" src="" alt="Current Image" class="mb-2" style="width:30px; height:30px; border-radius: 50%;">
                                    <input type="file" name="image" id="edit_image" class="form-control mb-2" accept="image/*">
                                  
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="update_employee" class="btn btn-primary">Update Employee</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Hidden Delete Form -->
            <form id="deleteForm" method="POST" style="display: none;">
                <input type="hidden" name="emp_id" id="deleteAdminId">
                <input type="hidden" name="delete_admin" value="1">
            </form>






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



    <script>
        $(document).ready(function() {
            $('#employeeTable').DataTable({
                scrollX: true
            });
        });
        // delete function
        document.querySelectorAll(".deleteBtn").forEach(button => {
            button.addEventListener("click", (e) => {
                e.preventDefault();
                const adminId = button.dataset.id;

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
                        document.getElementById('deleteAdminId').value = adminId;
                        document.getElementById('deleteForm').submit();
                    }
                });
            });
        });


        // Edit Button Click Handler
        document.querySelectorAll(".editBtn").forEach(button => {
            button.addEventListener("click", () => {
            
                console.log(button.dataset);
                document.getElementById("edit_emp_id").value = button.dataset.emp_id;
                document.getElementById("edit_name").value = button.dataset.name;
                document.getElementById("edit_email").value = button.dataset.email;
               
                document.getElementById("edit_phone").value = button.dataset.phone;
                document.getElementById("edit_department_id").value = button.dataset.department_id;
                document.getElementById("edit_status").value = button.dataset.status;
                document.getElementById("current-image").src = "../uploads/" + button.dataset.image;

                // Optional: Pre-select gender
                if (button.dataset.gender === 'Male') {
                    document.getElementById("genderMale").checked = true;
                } else if (button.dataset.gender === 'Female') {
                    document.getElementById("genderFemale").checked = true;
                }
            });
        });
    </script>
</body>

</html>