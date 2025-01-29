<?php
session_start();
if (!isset($_SESSION['Admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<?php

include "conn.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_Attendance'])) {
    // Fetch form data
    $Emp_ID = $_POST['Emp_ID'];
    $Status = $_POST['Status']; // Fetch status
    $Check_in = !empty($_POST['Check_in']) ? $_POST['Check_in'] : null;
    $Check_out = !empty($_POST['Check_out']) ? $_POST['Check_out'] : null;

    // Insert query
    $sql = "INSERT INTO attendance (employee_id, status, check_in_time, check_out_time, created_date)
            VALUES ('$Emp_ID', '$Status', " . ($Check_in ? "'$Check_in'" : "NULL") . ", " . ($Check_out ? "'$Check_out'" : "NULL") . ", CURRENT_TIMESTAMP())";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Attendance added successfully');</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// update the attendance
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_Attendance'])) {
    // Fetch form data
    $Emp_ID = $_POST['Emp_ID'];
    $Status = $_POST['Status'];
    $Check_in = !empty($_POST['Check_in']) ? $_POST['Check_in'] : null;
    $Check_out = !empty($_POST['Check_out']) ? $_POST['Check_out'] : null;

    // Update query
    $sql = "UPDATE attendance
            SET status = '$Status',
                check_in_time = " . ($Check_in ? "'$Check_in'" : "NULL") . ",
                check_out_time = " . ($Check_out ? "'$Check_out'" : "NULL") . "
            WHERE employee_id = '$Emp_ID'";

    // Execute query
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Attendance updating successfully');</script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}


// delete the attendance
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_emp_id'])) {
    // Get the employee ID to delete
    $emp_id_to_delete = $_POST['delete_emp_id'];

    // SQL query to delete the record
    $sql = "DELETE FROM attendance WHERE employee_id = '$emp_id_to_delete'";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Attendance deleting successfully');</script>";
        header("Location: Attendance.php"); // Redirect after delete
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

// Fetch employee data
$select_query = "SELECT * FROM attendance"; 
$employee_result = mysqli_query($conn, $select_query);

$Counter = 1;
?>













<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-300 font-semibold">
    <section class="flex flex-row">
        <!-- left side  -->
         <div class="flex flex-col bg-slate-300">
            <!-- dashboard -->
             <div class="py-[25px] w-[200px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
                <a href="Dashboard.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-landmark"></i>
                <span>Dashboard</span>
                </a>
             </div>
             <!-- Employee -->
             <div class="py-[25px] w-[200px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
                <a href="Employee.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-people-group"></i>
                <span>Employee</span>
                </a>
             </div>
             <!-- Department -->
             <div class="py-[25px] w-[200px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
                <a href="Departments.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-house"></i>
                <span>Department</span>
                </a>
             </div>
             <!-- projects -->
             <div class="py-[25px] w-[200px h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
               <a href="Projects.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-hands-holding-circle"></i>
                <span>Projects</span>
               </a>
             </div>
             <!-- emp_project -->
             <div class="py-[25px] w-[200px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
               <a href="Emp_project.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-user-shield"></i>
                <span>Emp_project</span>
               </a>
             </div>
             <!-- Attendance -->
             <div class="py-[25px] w-[200px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
               <a href="Attendance.php" class="  ml-[30px] absolute bottom-[15px] ">
                <i class="fa-solid fa-clipboard-user"></i>
                <span >Attendance</span>
               </a>
             </div>
             <!-- Admins -->
             <div class="py-[25px] w-[200px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative">
                <a href="Admin.php" class=" ml-[30px] absolute bottom-[15px]">
                <i class="fa-solid fa-user-tie"></i>
                <span>Admins</span>
                </a>
             </div>
            
            

              <!-- Log Out -->
              <div class="py-[25px] w-[200px] h-[30px] mx-1 rounded-md bg-cyan-400 text-black mt-[20px] hover:bg-cyan-500 hover:text-black font-semibold relative  mb-[20px]">
               <a href="logout.php" class="ml-[30px] absolute bottom-[15px]">
               <i class="fa-solid fa-right-from-bracket"></i>
                <span>Log Out</span>
               </a>
               </div>

         </div>

         
         <!-- right side -->
          <div class="ml-48">
              <h2 class="font-bold text-3xl ml-[-100px] mt-[40px]">Attendance</h2>
        <div class="flex mx-10 my-5">
            <button onclick="window.location.href='Attandance_reg.php';" class="px-16 justify-end   py-3 bg-cyan-400 rounded hover:bg-cyan-500 text-black font-semibold text-xl ml-[500px]">Add Attendance</button>
        </div >
        <div class="ml-4" >
         <table class="  text-sm text-left   ml-[-160px] " >
            <tr class="">
               <th class="text-left  text-[20px] p-[20px] px-[50px]">Emp_ID</th>
               <th class="text-left  text-[20px] p-[20px] px-[50px]">Status</th>
               <th class="text-left  text-[20px] p-[20px] px-[50px]">Check_in</th>
               <th class="text-left  text-[20px] p-[20px] px-[50px]">Check_out</th>
               <th class="text-left  text-[20px] p-[20px]  px-[50px]">Action</th>
           </tr>
   
           <?php
           while ($row = mysqli_fetch_assoc($employee_result)) {
            $Emp_ID = $row['employee_id'];
            $Status = $row['status'];
            $Check_in = $row['check_in_time'] ?: "NULL"; 
            $Check_out = $row['check_out_time'] ?: "NULL";
         
    

           ?>
           <tr class="ml-[20px]">
           <td class="px-4 text-left px-[50px]"><?php echo  $Emp_ID ?></td>
            <td class="px-4 text-left px-[50px]"><?php echo  $Status ?></td>
            <td class="px-4 text-left px-[50px]"><?php echo  $Check_in ?></td>
            <td class="px-4 text-left px-[50px]" ><?php echo  $Check_out ?></td>

            <td class="flex mx-6 p-[15px] px-[50px]">
                <!-- update btn -->
                <button
            onclick="showUpdatePopup('<?php echo $Emp_ID; ?>', '<?php echo $Status; ?>', '<?php echo $Check_in; ?>', '<?php echo $Check_out; ?>')"
            class="bg-cyan-400 text-white font-bold p-[5px] rounded-md">
            <span><i class="fa fa-pencil-square" aria-hidden="true"></i></span>
        </button>
             <!-- Delete Button -->
        <form action="attendance.php" method="post" class="mx-4" enctype="multipart/form-data">
            <input type="hidden" name="delete_emp_id" value="<?php echo $Emp_ID; ?>">
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

  




  <!-- Popup-upadte Form -->

<div id="update_popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="relative p-6 bg-white rounded-md shadow-lg max-w-lg w-full">
        <!-- Close Button -->
        <button onclick="closePopup()" class="absolute top-2 right-2 bg-gray-300 p-2 rounded-lg hover:bg-gray-400">
            <i class="fa-solid fa-x"></i>
        </button>
        
        <!-- Form -->
        <form action="" method="post" class="space-y-4 mt-16" enctype="multipart/form-data">
            <div class="grid grid-cols-2 gap-4">
                <!-- Emp_ID -->
                <div>
                    <input
                        name="Emp_ID"
                        type="text"
                        placeholder="Emp_ID"
                        readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
                <!-- Status -->
                <div>
                    <select
                        name="Status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="" disabled selected>Status</option>
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                    </select>
                </div>
                <!-- Check_in -->
                <div>
                    <input
                        name="Check_in"
                        type="datetime-local"
                        placeholder="Check_in"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
                <!-- Check_out -->
                <div>
                    <input
                        name="Check_out"
                        type="datetime-local"
                        placeholder="Check_out"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="text-center">
                <button
                    name="update_Attendance"
                    type="submit"
                    class="w-full px-6 py-3 bg-cyan-500 text-white rounded-md hover:bg-cyan-600 font-semibold"
                >
                    Update Attendance
                </button>
            </div>
        </form>
    </div>
</div>










  <script src="app.js"></script>
</body>
</html>