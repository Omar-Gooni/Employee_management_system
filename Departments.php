<?php
session_start();
if (!isset($_SESSION['Admin_id'])) {
    header("Location: login.php");
    exit();
}
?>


<?php

include "conn.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_dept'])){
extract($_POST);

$sql = "call InsertDepartment('$Department_Name' ,$Manger_id)";
$query = mysqli_query($conn , $sql);

if($query){
    echo "<script>alert('Departments added successfully')</script>";
     }
     else{
    echo "<script>alert('Failed to add Departments')</script>";
     }
}

// updating the department
if (isset($_POST['Update_deprt'])) {
    $dep_id = $_POST['dep_id'];
    $department_name = $_POST['Department_Name'];
    $manager_id = $_POST['Manger_id'];

    // Update the department in the database
    $query = "UPDATE departments SET Department_Name = '$department_name', Manager_ID = '$manager_id' WHERE ID = '$dep_id'";
    $result = mysqli_query($conn, $query);

    if($result){
        echo "<script>alert('Departments Update successfully')</script>";
         }
         else{
        echo "<script>alert('Failed to Update Departments')</script>";
         }
}

// delete department

if (isset($_POST['delete_dept_id'])) {
    $dept_id = $_POST['delete_dept_id'];

    // Delete the department from the database
    $query = "DELETE FROM departments WHERE ID = '$dept_id'";
    $result = mysqli_query($conn, $query);

    if($result){
        echo "<script>alert('Departments delete successfully')</script>";
         }
         else{
        echo "<script>alert('Failed to delete Departments')</script>";
         }
}





// Fetch employee data
$select_query = "SELECT * FROM departments"; 
$employee_result = mysqli_query($conn, $select_query);




?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department</title>
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
              <h2 class="font-bold text-3xl  mt-[40px] pl-10">Department</h2>
        <div class="flex mx-10 my-5 flex  justify-end">
            <button onclick="window.location.href='department_reg.php';" class="px-16 justify-end   py-3 bg-cyan-400 rounded hover:bg-cyan-500 text-black font-semibold text-xl ml-[500px]">Add Department</button>
        </div >
        <div class="ml-5" >
         <table class="  text-sm text-left   w-[1000px] " >
            <tr class="">

               <th class="text-left  text-[20px] p-[20px]">Department_Name</th>
               <th class="text-left  text-[20px] p-[20px]">Manger_ID</th>
               <th class="text-left  text-[20px] p-[20px] ">Action</th>
           </tr>
       
           <?php
           while ($row = mysqli_fetch_assoc($employee_result)) {
            $Id = $row['ID'];
            $Name = $row['Department_Name'];
            $Manger_ID = $row['Manager_ID'];
           ?>
           <tr class="ml-[20px]">
           
            <td class="px-4 text-left"><?php echo  $Name ?></td>
            <td class="px-4 text-left"><?php echo  $Manger_ID ?></td>
            <td class="flex mx-6 p-[15px]">
                <!-- update btn -->
                <button onclick="update_department(<?php echo $Id; ?>, '<?php echo $Name; ?>', '<?php echo     $Manger_ID; ?>')"
                  class="bg-cyan-400 text-white font-bold p-[5px] rounded-md">
                  <span><i class="fa fa-pencil-square" aria-hidden="true"></i></span>
               </button>
            <!-- delet form -->
               <form class="mx-4" action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="delete_dept_id" value="<?php echo $Id; ?>">
    <button type="submit" class="bg-red-400 text-white h-[20px] font-bold p-[5px] h-[30px] rounded-md">
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

<div id="update_dep_popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
      <div class="relative p-6 bg-white rounded-md shadow-lg max-w-lg w-full">
          <!-- Close icon -->
          <button onclick="closePopup()"  class="absolute top-[2px] right-2 bg-gray-300 p-2 rounded-lg hover:bg-gray-400">
            <i class="fa-solid fa-x"></i>
          </button>
          
          <!-- Form  -->
          <form action="" method="post" class="space-y-4 mt-10" enctype="multipart/form-data">

              <div class="grid grid-cols-1 gap-4">
                   <!-- Hidden field for dep_id -->
            <input type="hidden" id="dep_id" name="dep_id" value="">
                  <br>
                  <!-- Department_Name -->
                  <div>
                      <input 
                          name="Department_Name"
                          type="text" 
                          placeholder="Department_Name" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      />
                  </div>
                  <br>
                  <!-- Manger_id -->
                  <div>
                      <input 
                          name="Manger_id"
                          type="text" 
                          placeholder="Manger_id" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      />
                  </div>
                  <br>
              
               
              
              <!-- Submit Button -->
              <div class="text-center">
                  <button 
                  name="Update_deprt"
                      type="submit" 
                      class="w-full px-6 py-3 bg-cyan-500 text-black bg-cyan-400 rounded hover:bg-cyan-500 font-semibold"
                  >
                      Update Department
                  </button>
              </div>
          </form>
      </div>
  </div>




<script src="app.js"></script>

</body>
</html>