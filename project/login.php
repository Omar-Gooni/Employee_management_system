<?php
session_start();
include 'db_connect.php';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        
        // Login successful
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_email'] = $admin['email'];
        
        // Redirect to dashboard
        header("Location: dashboard.php");
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
    <title>Admin Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .gradient-custom {
            background: rgb(46, 52, 62);
            background: -webkit-linear-gradient(to right, rgb(46, 52, 62), rgb(30, 35, 43));
            background: linear-gradient(to right, rgb(46, 52, 62), rgb(30, 35, 43));
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.2);
            overflow: hidden;
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
    <div class="container py-5">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-white">
                    <div class="card-body p-5 text-center">
                        <div class="mb-4">
                            <i class="fas fa-user-shield fa-4x" style="color: rgb(19, 79, 221);"></i>
                        </div>
                        <h2 class="mb-4">Admin Login</h2>
                        <p class="mb-4 text-muted">Please enter your credentials</p>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger mb-4"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                                <label for="email">Email address</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <label for="password">Password</label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>