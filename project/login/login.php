<?php
session_start();
include '../connection/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $is_admin = isset($_POST['is_admin']);

    if ($is_admin) {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ? AND password = ?");
    } else {
        $stmt = $conn->prepare("SELECT * FROM employees WHERE email = ? AND password = ?");
    }

    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if ($is_admin) {
            $_SESSION['admin_id'] = $user['admin_id'];
            $_SESSION['admin_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['admin_email'] = $user['email'];
            $_SESSION['image'] = $user['image'];
            header("Location: ../admin/dashboard.php");
        } else {
            $_SESSION['emp_id'] = $user['emp_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['position'] = $user['position'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['image'] = $user['image'];
            header("Location: ../employee/employee_dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid email or password";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }

        .gradient-custom {
            background: linear-gradient(to right, rgb(46, 52, 62), rgb(30, 35, 43));
            height: 100vh;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .form-control {
            height: 40px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: rgb(19, 79, 221);
        }

        .btn-primary {
            background-color: rgb(19, 79, 221);
            border: none;
            height: 45px;
            font-size: 1rem;
        }

        .btn-primary:hover {
            background-color: rgb(15, 65, 185);
        }

        .card-body {
            padding: 1.5rem 1.5rem;
        }

        .form-check-label {
            font-size: 0.9rem;
        }

        .login-icon {
            font-size: 2.5rem;
            color: rgb(19, 79, 221);
        }

        .alert {
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="gradient-custom">
    <div class="container h-100 d-flex align-items-center justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card bg-white">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-shield login-icon"></i>
                    </div>
                    <h4 class="mb-3">Login</h4>
                    <p class="text-muted mb-3">Enter your credentials below</p>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                            <label for="email">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <label for="password">Password</label>
                        </div>
                        <div class="form-check mb-3 text-start">
                            <input class="form-check-input" type="checkbox" value="1" id="is_admin" name="is_admin">
                            <label class="form-check-label" for="is_admin">
                                Login as Admin
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
