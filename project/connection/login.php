<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../connection/db_connect.php';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $is_admin = isset($_POST['is_admin']);

    if ($is_admin) {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    } else {
        $stmt = $conn->prepare("SELECT * FROM employees WHERE email = ?");
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
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
            $error = "Incorrect password.";
        }
    } else {
        $error = "Email not found.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <style>
    html, body {
      height: 100%;
      margin: 0;
    }
    .gradient-custom {
      background: linear-gradient(to right, rgb(46, 52, 62), rgb(30, 35, 43));
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.2);
    }
    .form-control {
      height: 50px;
      border: 2px solid #eee;
      border-radius: 10px;
      transition: all 0.3s;
    }
    .form-control:focus {
      box-shadow: none;
      border-color: rgb(19, 79, 221);
    }
    .btn-primary {
      background-color: rgb(19, 79, 221);
      border: none;
      height: 50px;
      font-size: 1.1rem;
      transition: all 0.3s;
    }
    .btn-primary:hover {
      background-color: rgb(15, 65, 185);
      transform: translateY(-2px);
    }
  </style>
</head>
<body class="gradient-custom">
  <div class="container">
    <div class="row justify-content-center align-items-center" style="height: 100%;">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-white">
          <div class="card-body p-5 text-center">
            <div class="mb-4">
              <i class="fas fa-user-shield fa-4x" style="color: rgb(19, 79, 221);"></i>
            </div>
            <h2 class="mb-4">Login</h2>
            <p class="mb-4 text-muted">Please enter your credentials</p>

            <?php if (isset($error)): ?>
              <div class="alert alert-danger mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
              <input type="hidden" name="login" value="1">
              <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required />
                <label for="email">Email address</label>
              </div>
              <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
                <label for="password">Password</label>
              </div>
              <div class="form-check mb-3 text-start">
                <input class="form-check-input" type="checkbox" value="1" id="is_admin" name="is_admin" />
                <label class="form-check-label" for="is_admin">Login as Admin</label>
              </div>
              <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                <i class="fas fa-sign-in-alt me-2"></i> Login
              </button>
            </form>

            <p><a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot Password?</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Forgot Password Modal -->
  <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="forgot_password.php">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="forgotPasswordLabel">Reset Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="reset_email" class="form-label">Enter your email</label>
              <input type="email" class="form-control" id="reset_email" name="email" required />
            </div>
            <div class="mb-3">
              <label for="new_password" class="form-label">New Password</label>
              <input type="password" class="form-control" id="new_password" name="new_password" required />
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update Password</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
