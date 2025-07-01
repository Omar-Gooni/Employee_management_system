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
    // Add Admin
    if (isset($_POST['add_admin'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $gender = $_POST['gender'];
        $password = $_POST['password'];  // plain text as requested

        // Upload image
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = '../uploads/' . $image_name;
        move_uploaded_file($image_tmp, $image_path);

        // Insert admin (role defaults to 'admin')
        $conn->query("INSERT INTO admin (name, email, phone, gender, password, image) 
                  VALUES ('$name', '$email', '$phone', '$gender', '$password', '$image_name')");
    }



    // Update Admin
    if (isset($_POST['update_admin'])) {
        $id = $_POST['admin_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $gender = $_POST['gender'];

        $query = "UPDATE admin SET name='$name', email='$email', phone='$phone', gender='$gender'";

        if (!empty($_FILES['image']['name'])) {
            $image_name = $_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_path = '../uploads/' . $image_name;
            move_uploaded_file($image_tmp, $image_path);
            $query .= ", image='$image_name'";
        }

        $query .= " WHERE admin_id=$id";
        $conn->query($query);
    }



    // Delete Admin
    if (isset($_POST['delete_admin'])) {
        $id = $_POST['admin_id'];
        $conn->query("DELETE FROM admin WHERE admin_id=$id");
    }


    header("Location: admin.php");
    exit();
}

// Fetch all admins
$result = $conn->query("SELECT * FROM admin");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Admin</title>
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
        #adminTable th,
        #adminTable td {
            white-space: nowrap !important;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: middle;
            color: #000 !important;
        }

        #adminTable thead th {
            background-color: #f8f9fa;
            font-weight: bold;
        }



        /* ----- ACTIONS BUTTONS STYLING ----- */
        /* Ensures buttons stay in one line */
        #adminTable td:last-child {
            white-space: nowrap;
        }

        /* Button container spacing */
        #adminTable .d-flex.gap-1 {
            gap: 0.5rem !important;
            /* Better spacing between buttons */
        }

        /* Base button styling */
        #adminTable .btn {
            min-width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px !important;
        }

        /* Icon sizing */
        #adminTable .btn i {
            font-size: 14px;
            margin: 0 !important;
        }

        /* Specific button colors */


        /* Hover effects */
        #adminTable .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            #adminTable .btn {
                min-width: 28px;
                height: 28px;
            }

            #adminTable .btn i {
                font-size: 12px;
            }
        }


        .admin_img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
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
                            <h4 class="page-title">Admin</h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- php -->






            <!-- Add Admin Button -->
            <div style="position: relative; margin-top: 20px;">
                <!-- Add Admin Button -->
                <button class="btn btn-success ml-responsive" data-bs-toggle="modal" data-bs-target="#addAdminModal"> <i class="fas fa-plus"></i>Add Admin</button>

                <!-- Admin Table -->

                <div class="table-responsive">
                    <table id="adminTable" class="table table-bordered mt-3 table-striped display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Status</th>
                                <th>Date Joined</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['admin_id'] ?></td>
                                    <td><?= $row['name'] ?></td>
                                    <td><?= $row['email'] ?></td>
                                    <td><?= $row['phone'] ?></td>
                                    <td><?= $row['gender'] ?></td>
                                    <td><span class="badge bg-success"><?= $row['status'] ?></span></td>
                                    <td><?= date('M d, Y', strtotime($row['date_joined'])) ?></td>
                                    <td>
                                        <img class="admin_img" src="../uploads/<?= $row['image'] ?>" alt="Admin Image" style="width:40px; height:40px; border-radius: 50%;">
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <!-- Edit Button -->
                                            <button class="btn btn-primary editBtn"
                                                data-id="<?= $row['admin_id'] ?>"
                                                data-name="<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>"
                                                data-email="<?= htmlspecialchars($row['email'], ENT_QUOTES) ?>"
                                                data-phone="<?= htmlspecialchars($row['phone'], ENT_QUOTES) ?>"
                                                data-gender="<?= $row['gender'] ?>"
                                                data-image="<?= $row['image'] ?>"
                                                title="Edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editAdminModal">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Delete Button -->
                                            <button class="btn btn-danger deleteBtn"
                                                data-id="<?= $row['admin_id'] ?>"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                            <!-- Print Button -->
                                            <form action="id_card_admin.php" method="POST" target="_blank" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $row['admin_id'] ?>">
                                                <button type="submit" class="btn btn-success" title="Print ID">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>



                <!-- Add Admin Modal -->
                <div class="modal fade" id="addAdminModal" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" enctype="multipart/form-data" class="modal-content needs-validation" id="addAdminForm" novalidate>
                            <div class="modal-header">
                                <h5 class="modal-title">Add Admin</h5>
                            </div>
                            <div class="modal-body">
                                <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
                                <div class="invalid-feedback">Name is required.</div>

                                <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                                <div class="invalid-feedback">Please enter a valid email.</div>

                                <input type="text" name="phone" class="form-control mb-2" placeholder="Phone" required>
                                <div class="invalid-feedback">Phone is required.</div>

                                <select name="gender" class="form-control mb-2" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <div class="invalid-feedback">Gender is required.</div>

                                <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
                                <div class="invalid-feedback">Password is required.</div>

                                <input type="file" name="image" class="form-control mb-2" accept="image/*" required>
                                <div class="invalid-feedback">Image is required.</div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="add_admin" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                    </div>
                </div>




                <!-- Edit Admin Modal -->
                <div class="modal fade" id="editAdminModal" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" enctype="multipart/form-data" class="modal-content">
                            <div class="modal-header">
                                <h5>Edit Admin</h5>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="admin_id" id="edit-id">
                                <input type="text" name="name" id="edit-name" class="form-control mb-2" required>
                                <input type="email" name="email" id="edit-email" class="form-control mb-2" required>
                                <input type="text" name="phone" id="edit-phone" class="form-control mb-2" required>
                                <select name="gender" id="edit-gender" class="form-control mb-2" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <img id="current-image" src="" alt="Current Image" class="mb-2" style="width:40px; height:40px; border-radius: 50%;">
                                <input type="file" name="image" class="form-control mb-2" accept="image/*">
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
                    <input type="hidden" name="admin_id" id="deleteAdminId">
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

        <!-- JS Includes -->
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>




        <script>
            // DataTable Initialization
            $(document).ready(function() {
                $('#adminTable').DataTable({
                    scrollX: true
                });
            });

            // Edit Admin Button
            document.querySelectorAll(".editBtn").forEach(button => {
                button.addEventListener("click", () => {
                    document.getElementById("edit-id").value = button.dataset.id;
                    document.getElementById("edit-name").value = button.dataset.name;
                    document.getElementById("edit-email").value = button.dataset.email;
                    document.getElementById("edit-phone").value = button.dataset.phone;
                    document.getElementById("edit-gender").value = button.dataset.gender;
                    document.getElementById("current-image").src = "../uploads/" + button.dataset.image;
                });
            });



            // Delete Admin Button
            document.querySelectorAll(".deleteBtn").forEach(button => {
                button.addEventListener("click", (e) => {
                    e.preventDefault();
                    const adminId = button.dataset.id;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won’t be able to undo this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("deleteAdminId").value = adminId;
                            document.getElementById("deleteForm").submit();
                        }
                    });
                });
            });
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.querySelector("#addAdminForm");

                form.addEventListener("submit", function(e) {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    form.classList.add('was-validated');
                });
            });
        </script>
</body>

</html>