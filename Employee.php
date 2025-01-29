<?php
session_start();
if (!isset($_SESSION['Admin_id'])) {
    header("Location: login.php");
    exit();
}
?>



<?php

include "conn.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_emp'])) {
    extract($_POST);
    if (
        empty($_POST['emp_name']) || 
        empty($_POST['emp_email']) || 
        empty($_POST['emp_password']) || 
        empty($_POST['job_tital']) || 
        empty($_POST['emp_Salary']) || 
        empty($_POST['Department_ID'])
    ) {
        // Redirect to Employee.php if any of the fields are empty
        header('Location: Employee.php');
        exit();
    }
    
    // Ensure that data is sanitized to prevent SQL injection
    $emp_name = mysqli_real_escape_string($conn, $emp_name);
    $emp_email = mysqli_real_escape_string($conn, $emp_email);
    $emp_password = mysqli_real_escape_string($conn, $emp_password);
    $job_tital = mysqli_real_escape_string($conn, $job_tital);
    $emp_Salary = mysqli_real_escape_string($conn, $emp_Salary);
    $Department_ID = mysqli_real_escape_string($conn, $Department_ID);

    // SQL Insert query
    $sql = "INSERT INTO employees (Name, Email, Password, Job_title, Salary, Dep_ID)
            VALUES ('$emp_name', '$emp_email', '$emp_password', '$job_tital', '$emp_Salary', '$Department_ID')";

    $query = mysqli_query($conn, $sql);

    if ($query) {
        echo "<script>alert('Employee added successfully')</script>";
    } else {
        echo "<script>alert('Failed to add Employee')</script>";
    }
}



// update employee

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_emp'])) {
    extract($_POST);
    
    // Check if any fields are empty
    if (
        empty($_POST['emp_id']) ||
        empty($_POST['emp_name']) || 
        empty($_POST['emp_email']) || 
        empty($_POST['emp_password']) || 
        empty($_POST['job_tital']) || 
        empty($_POST['emp_Salary']) || 
        empty($_POST['Department_ID'])
    ) {
        // Redirect to Employee.php if any fields are empty
        header('Location: Employee.php');
        exit();
    }
    
    // Ensure that data is sanitized to prevent SQL injection
    $emp_id = mysqli_real_escape_string($conn, $emp_id);
    $emp_name = mysqli_real_escape_string($conn, $emp_name);
    $emp_email = mysqli_real_escape_string($conn, $emp_email);
    $emp_password = mysqli_real_escape_string($conn, $emp_password);
    $job_tital = mysqli_real_escape_string($conn, $job_tital);
    $emp_Salary = mysqli_real_escape_string($conn, $emp_Salary);
    $Department_ID = mysqli_real_escape_string($conn, $Department_ID);

    // SQL Update query
    $sql = "UPDATE employees 
            SET 
                Name = '$emp_name',
                Email = '$emp_email',
                Password = '$emp_password',
                Job_title = '$job_tital',
                Salary = '$emp_Salary',
                Dep_ID = '$Department_ID'
            WHERE id = '$emp_id'";

    $query = mysqli_query($conn, $sql);

    if ($query) {
        echo "<script>alert('Employee updated successfully')</script>";
    } else {
        echo "<script>alert('Failed to update Employee')</script>";
    }
}

// Delete employee

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_emp_id'])) {
    // Extract employee ID from the POST data
    $emp_id = $_POST['delete_emp_id'];
    
    // Check if the employee ID is provided
    if (empty($emp_id)) {
        // If the emp_id is empty, redirect to Employee.php
        header('Location: Employee.php');
        exit();
    }
    
    // Ensure the emp_id is sanitized to prevent SQL injection
    $emp_id = mysqli_real_escape_string($conn, $emp_id);

    // SQL Delete query
    $sql = "DELETE FROM employees WHERE ID = '$emp_id'";

    // Execute the query
    $query = mysqli_query($conn, $sql);

    if ($query) {
        // If deletion is successful, alert the user
        echo "<script>alert('Employee deleted successfully');</script>";
    } else {
        // If there is an error during deletion
        echo "<script>alert('Failed to delete Employee');</script>";
    }

    // Redirect back to Employee.php after deletion
    header('Location: Employee.php');
    exit();
}




// Fetch employee data
$select_query = "SELECT * FROM employees"; 
$employee_result = mysqli_query($conn, $select_query);




?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee</title>
    <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-300 font-semibold">
    <section class="flex flex-row">
        <!-- left side  -->
         <div class="flex flex-col bg-slate-300">
            <!-- dashboard -->
             <div class="py-[25px] w-[180px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
                <a href="Dashboard.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-landmark"></i>
                <span>Dashboard</span>
                </a>
             </div>
             <!-- Employee -->
             <div class="py-[25px] w-[180px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
                <a href="Employee.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-people-group"></i>
                <span>Employee</span>
                </a>
             </div>
             <!-- Department -->
             <div class="py-[25px] w-[180px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
                <a href="Departments.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-house"></i>
                <span>Department</span>
                </a>
             </div>
             <!-- projects -->
             <div class="py-[25px] w-[180px h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
               <a href="Projects.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-hands-holding-circle"></i>
                <span>Projects</span>
               </a>
             </div>
             <!-- emp_project -->
             <div class="py-[25px] w-[180px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
               <a href="Emp_project.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-user-shield"></i>
                <span>Emp_project</span>
               </a>
             </div>
             <!-- Attendance -->
             <div class="py-[25px] w-[180px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
               <a href="Attendance.php" class="  ml-[30px] absolute bottom-[15px] ">
                <i class="fa-solid fa-clipboard-user"></i>
                <span >Attendance</span>
               </a>
             </div>
             <!-- Admins -->
             <div class="py-[25px] w-[180px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
                <a href="Admin.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-user-tie"></i>
                <span>Admins</span>
                </a>
             </div>
        

              <!-- Log Out -->
              <div class="py-[25px] w-[180px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative  mb-[20px]">
               <a href="logout.php" class="ml-[30px] absolute bottom-[15px]">
               <i class="fa-solid fa-right-from-bracket"></i>
                <span>Log Out</span>
               </a>
               </div>
         </div>

         
         <!-- right side -->
          <div class="ml-0 ">
              <div class="">
              <h2 class="font-bold text-3xl  mt-[40px] pl-10">Emplooyee</h2>
              </div>
              <div class="flex mx-10 my-5 flex  justify-end">
            <button onclick="window.location.href='Employee_reg.php';"
             class="px-16 justify-end   py-3 bg-cyan-400 rounded hover:bg-cyan-500 text-black font-semibold text-xl ml-[500px]"
            >Add Employee</button>
        </div >
        <div class="ml-5" >
         <table class="  text-sm text-left   w-[1000px]  " >
            <tr class="">
              
               <th class="text-left px-4  text-[20px] p-[20px]">Name</th>
               <th class="text-left px-4  text-[20px] p-[20px]">Email</th>
               <th class="text-left px-4  text-[20px] p-[20px]">Password</th>
               <th class="text-left px-4  text-[20px] p-[20px]">Job_tital</th>
               <th class="text-left px-4  text-[20px] p-[20px]">Salary</th>
               <th class="text-left  px-4 text-[20px] p-[20px]">Dep_ID</th>
               <th class="text-left  px-4 text-[20px] p-[20px] ">Action</th>
           </tr>
           <?php
           while ($row = mysqli_fetch_assoc($employee_result)) {
            $ID = $row['ID'];
            $Name = $row['Name'];
            $Email = $row['Email'];
            $Password = "*******";
            $Job_tital = $row['Job_title'];
            $Salary = $row['Salary'];
            $Dep_ID = $row['Dep_ID'];

           ?>
           <tr class="ml-[20px]">
        
            <td class="px-4 text-left"><?php echo  $Name ?></td>
            <td class="px-4 text-left"><?php echo  $Email ?></td>
            <td class="px-4 text-left" ><?php echo  $Password ?></td>
            <td class="px-4 text-left"><?php echo  $Job_tital ?></td>
            <td class="px-4 text-left"><?php echo  $Salary ?></td>
            <td class="px-4 text-left"><?php echo  $Dep_ID ?></td>

            <td class="flex mx-6 p-[15px]">
                      <!-- update btn -->
                      <button 
    onclick="update_employee(
        '<?php echo addslashes($ID ); ?>',
        '<?php echo addslashes($Name); ?>',
        '<?php echo addslashes($Email); ?>',
        '<?php echo addslashes($row['Password']); ?>',  // Pass real password here
        '<?php echo addslashes($Job_tital); ?>',
        '<?php echo addslashes($Salary); ?>',
        '<?php echo addslashes($Dep_ID); ?>'
    )"
    class="bg-cyan-400 text-white font-bold  p-[5px] rounded-md"
>
    <span><i class="fa fa-pencil-square" aria-hidden="true"></i></span>
</button>
    



<form class="mx-4" action="Employee.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="delete_emp_id" value="<?php echo $ID; ?>"> <!-- Pass the employee ID -->
    <button type="submit" class="bg-red-400 text-white font-bold p-[5px] rounded-md">
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

   <!-- Update Popup -->
 
   <div id="update_emp_popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="relative p-6 bg-white rounded-md shadow-lg max-w-lg w-full">
        <!-- Close icon -->
        <button onclick="closePopup()" class="absolute top-[2px] right-2 bg-gray-300 p-2 rounded-lg hover:bg-gray-400">
            <i class="fa-solid fa-x"></i>
        </button>
        <!-- Form -->
        <form action="Employee.php" method="post" class="space-y-4 mt-16" enctype="multipart/form-data">
            <!-- Hidden field for emp_id -->
            <input type="hidden" id="emp_id" name="emp_id" value="">

            <div class="grid grid-cols-2 gap-4">
                <!-- Emp name -->
                <div>
                    <input 
                        id="emp_name"
                        name="emp_name"
                        type="text" 
                        placeholder="Emp_Name" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
                <!-- Emp email -->
                <div>
                    <input 
                        id="emp_email"
                        name="emp_email"
                        type="text" 
                        placeholder="Emp_Email" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
                <!-- Emp password -->
                <div>
                    <input 
                        id="emp_password"
                        name="emp_password"
                        type="text" 
                        placeholder="Emp_password" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
                <!-- Job title -->
                <div>
                    <input 
                        id="job_tital"
                        name="job_tital"
                        type="text" 
                        placeholder="Job_title" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
                <!-- Salary -->
                <div>
                    <input 
                        id="emp_Salary"
                        name="emp_Salary"
                        type="text" 
                        placeholder="Emp_Salary" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
                <!-- Department ID -->
                <div>
                    <input 
                        id="Department_ID"
                        name="Department_ID"
                        type="text" 
                        placeholder="Department_ID" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="text-center">
                <button 
                    name="update_emp"
                    type="submit" 
                    class="w-full px-6 py-3 bg-cyan-500 text-white rounded-md hover:bg-cyan-600 font-semibold"
                >
                    Update Employee
                </button>
            </div>
        </form>
    </div>
</div>

<script src="app.js"></script>

 












 
</body>
</html>