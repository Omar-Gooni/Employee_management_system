
<?php
 session_start();
include "conn.php";

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Determine the role based on the checkbox
    $role = isset($_POST['role']) && $_POST['role'] === 'admin' ? 'admin' : 'user';

    // Check if user exists
    $sql = "SELECT * FROM admin WHERE Email = '$email' AND password = '$password' AND role = '$role'";
    $result = $conn->query($sql);


   
    if ($result->num_rows === 1) {
        // User authenticated successfully
        $user = $result->fetch_assoc();
        $_SESSION['Admin_id'] = $user['id'];
        header('Location: http://localhost:3000/Dashboard.php');
    } else {
        // Invalid email, password, or role
        echo "<script>
            alert('Invalid email, password.');
            window.location.href = 'login.php'; // Replace 'login.php' with your actual login page
        </script>";
    }
}

$conn->close();
?>



<?php
// include "Dashboard.php";


?>