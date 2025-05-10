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
        $password = $_POST['password'];

        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = '../uploads/' . $image_name;
        move_uploaded_file($image_tmp, $image_path);

        $conn->query("INSERT INTO admin (name, email, password, image) 
                      VALUES ('$name', '$email', '$password', '$image_name')");
    }


    // Update Admin
    if (isset($_POST['update_admin'])) {
        $id = $_POST['admin_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = "UPDATE admin SET name='$name', email='$email', password='$password'";

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

        .admin_img {
            width: 10px;
            height: 0px;
            border-radius: 50%;
            padding: auto;

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
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAdminModal" style="position: absolute; top: 0; right: 0;"> <i class="fas fa-plus"></i>Add Admin</button>

                <!-- Admin Table -->
                <table id="adminTable" class="table table-bordered mt-3" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Password</th>
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
                                <td><?= $row['password'] ?></td>
                                <td>
                                    <img class="admin_img" src="../uploads/<?= $row['image'] ?>" alt="Admin Image" style="width:40px; height:40px;">
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm editBtn"
                                        data-id="<?= $row['admin_id'] ?>"
                                        data-name="<?= $row['name'] ?>"
                                        data-email="<?= $row['email'] ?>"
                                        data-password="<?= $row['password'] ?>"
                                        data-image="<?= $row['image'] ?>"
                                        data-bs-toggle="modal" data-bs-target="#editAdminModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm deleteBtn"
                                        data-id="<?= $row['admin_id'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form action="id_card_admin.php" method="POST" target="_blank" style="display: inline;">
                                        <input type="hidden" name="id" value="<?= $row['admin_id'] ?>">
                                        <button type="submit" class="btn btn-success btn-sm" title="Print ID Card">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </form>



                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>


                <!-- Add Admin Modal -->
                <div class="modal fade" id="addAdminModal" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" enctype="multipart/form-data" class="modal-content">
                            <div class="modal-header">
                                <h5>Add Admin</h5>
                            </div>
                            <div class="modal-body">
                                <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
                                <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                                <input type="file" name="image" class="form-control mb-2" accept="image/*" required>

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
                                <input type="password" name="password" id="edit-password" class="form-control" required>
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

                <script>
                    // DataTable Initialization
                    $(document).ready(function() {
                        $('#adminTable').DataTable();
                    });

                    // Edit Admin Button
                    document.querySelectorAll(".editBtn").forEach(button => {
                        button.addEventListener("click", () => {
                            document.getElementById("edit-id").value = button.dataset.id;
                            document.getElementById("edit-name").value = button.dataset.name;
                            document.getElementById("edit-email").value = button.dataset.email;
                            document.getElementById("edit-password").value = button.dataset.password;
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