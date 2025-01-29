<?php
session_start();
if (!isset($_SESSION['Admin_id'])) {
    header("Location: login.php");
    exit();
}
?>


<?php
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_admin'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = 'admin'; // Default role;

    $sql = "INSERT INTO admin (Name, Email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        echo "<script>alert('Admin added successfully'); window.location.href='Admin.php';</script>";
    } else {
        echo "<script>alert('Failed to add admin'); window.history.back();</script>";
    }
}


// updating admin
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_admin'])) {
    $admin_id = $_POST['admin_id']; // Get Admin ID
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Get actual password

    // Update query
    $sql = "UPDATE admin SET Name='$name', Email='$email', password='$password' WHERE id='$admin_id'";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        echo "<script>alert('Admin updated successfully'); window.location.href='Admin.php';</script>";
    } else {
        echo "<script>alert('Failed to update admin'); window.history.back();</script>";
    }
}


// delete the admin
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_admin'])) {
    // Get the admin ID to delete
    $emp_id_to_delete = $_POST['delete_admin_id'];

    // SQL query to delete the record
    $sql = "DELETE FROM admin WHERE id = '$emp_id_to_delete'";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Admin deleting successfully');</script>";
        header("Location: Admin.php"); // Redirect after delete
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

// Fetch admin data
$select_query = "SELECT * FROM admin"; 
$admin_result = mysqli_query($conn, $select_query);

?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-300 font-semibold">
    <section class="flex flex-row">
        <!-- left side  -->
         <div class="flex flex-col bg-slate-300">
            <!-- dashboard -->
             <div class="py-[25px] w-[190px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
                <a href="Dashboard.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-landmark"></i>
                <span>Dashboard</span>
                </a>
             </div>
             <!-- Employee -->
             <div class="py-[25px] w-[190px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
                <a href="Employee.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-people-group"></i>
                <span>Employee</span>
                </a>
             </div>
             <!-- Department -->
             <div class="py-[25px] w-[190px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
                <a href="Departments.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-house"></i>
                <span>Department</span>
                </a>
             </div>
             <!-- projects -->
             <div class="py-[25px] w-[190px h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
               <a href="Projects.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-hands-holding-circle"></i>
                <span>Projects</span>
               </a>
             </div>
             <!-- emp_project -->
             <div class="py-[25px] w-[190px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
               <a href="Emp_project.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-user-shield"></i>
                <span>Emp_project</span>
               </a>
             </div>
             <!-- Attendance -->
             <div class="py-[25px] w-[190px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
               <a href="Attendance.php" class="  ml-[30px] absolute bottom-[15px] ">
                <i class="fa-solid fa-clipboard-user"></i>
                <span >Attendance</span>
               </a>
             </div>
             <!-- Admins -->
             <div class="py-[25px] w-[190px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
                <a href="Admin.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-user-tie"></i>
                <span>Admins</span>
                </a>
             </div>
          
             <!-- Log Out -->
             <div class="py-[25px] w-[190px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative  mb-[20px]">
               <a href="logout.php" class="ml-[30px] absolute bottom-[15px]">
               <i class="fa-solid fa-right-from-bracket"></i>
                <span>Log Out</span>
               </a>
               </div>
         </div>

         
         <!-- right side -->
          <div class="ml-0">
              <h2 class="font-bold text-3xl  mt-[40px] pl-10">Admins</h2>
        <div class="flex mx-10 my-5 flex  justify-end">
            <button  onclick="window.location.href='admin_reg.php';" class="px-16 justify-end   py-3 bg-cyan-400 rounded hover:bg-cyan-500 text-black font-semibold text-xl ml-[500px]">Add Admin</button>
        </div >
        <div class="ml-5" >
         <table class="  text-sm text-left   w-[1000px] " >
            <tr class="">
               <th class="text-left  text-[20px] p-[20px]">Name</th>
               <th class="text-left  text-[20px] p-[20px]">Email</th>
               <th class="text-left  text-[20px] p-[20px]">Password</th>
               <th class="text-left  text-[20px] p-[20px] ">Action</th>
               </tr>
               </tr>
       
           <?php
           while ($row = mysqli_fetch_assoc($admin_result)) {
            $Admin_Id = $row['id'];
            $Admin_Name = $row['Name'];
            $Admin_Email = $row['Email'];
            $Admin_password = '*********';
            $Admin_role = $row['role'];
           ?>
           <tr class="ml-[20px]">
           <td class="px-4 text-left"><?php echo  $Admin_Name  ?></td>
            <td class="px-4 text-left"><?php echo  $Admin_Email ?></td>
            <td class="px-4 text-left"><?php echo  $Admin_password?></td>
            <td class="flex mx-6 p-[15px]">
                <!-- update btn -->
               <button onclick="update_admin_popup(
               '<?php echo addslashes($Admin_Id); ?>', 
               '<?php echo addslashes($Admin_Name); ?>', 
               '<?php echo addslashes($Admin_Email); ?>', 
               '<?php echo addslashes($row['password']); ?>')"
                class="bg-cyan-400 text-white font-bold p-[5px] rounded-md">
                <span><i class="fa fa-pencil-square" aria-hidden="true"></i></span>
               </button>


            <!-- delet form -->
               <form class="mx-4" action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="delete_admin_id" value="<?php echo $Admin_Id; ?>">
    <button type="submit" name="delete_admin" class="bg-red-400 text-white font-bold p-[5px] h-[30px] rounded-md">
        <span><i class="fa fa-trash-o" aria-hidden="true"></i></span>
    </button>
</form>

        </td>
        <?php

        

         }
         ?>
           </tr>
       </table>
        </div>
      
       
      </div>
         
    </section>

   

<!-- update popup -->

<div id="update_admin_popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="relative p-6 bg-white rounded-md shadow-lg max-w-lg w-full">
        
        <!-- Close Button -->
        <button onclick="close_update_Popup()" class="absolute top-2 right-2 bg-gray-300 p-2 rounded-lg hover:bg-gray-400">
            <i class="fa-solid fa-x"></i>
        </button>
        
        <h2 class="text-xl font-bold text-center mb-4">Update Admin</h2>

        <!-- Form -->
        <form action="Admin.php" method="post" class="space-y-4">
            
            <!-- Hidden Input for Admin ID -->
            <input type="hidden" name="admin_id">

            <!-- Name -->
            <div>
                <label class="block text-gray-600">Name</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border rounded-md">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-600">Email</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border rounded-md">
            </div>

            <!-- Password -->
            
            
                <input type="hidden" name="password" required class="w-full px-4 py-2 border rounded-md">
            

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" name="update_admin" class="px-6 py-3 bg-cyan-500 text-white font-semibold rounded-md hover:bg-cyan-600">
                    Update Admin
                </button>
            </div>
        </form>
    </div>
</div>






<script src="app.js"></script>

</body>
</html>