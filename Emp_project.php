<?php
session_start();
if (!isset($_SESSION['Admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<?php

include "conn.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_emp_project'])) {
    extract($_POST);

    // Ensure that Employee_Name and Project_Name are provided
    if (!empty($Employee_ID) && !empty($Project_ID)) {
        // Prepare the SQL statement to insert the data
        $sql = "INSERT INTO employee_projects (employee_id, project_id) 
                VALUES ('$Employee_ID', '$Project_ID')";

        // Execute the query
        $query = mysqli_query($conn, $sql);

        // Handle the result
        if ($query) {
            echo "<script>alert('Employee_Project added successfully')</script>";
        } else {
            echo "<script>alert('Failed to add Employee_Project')</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields')</script>";
    }
}


// update employee_project
if (isset($_POST['update_emp_project'])) {
    $Emp_pr_ID = $_POST['Employee_Projects_ID'];
    $empId = $_POST['Employee_ID'];
    $projectId = $_POST['Project_ID'];

    // Perform your database update here
    $query = "UPDATE employee_projects SET employee_id = '$empId' , project_id = '$projectId' WHERE 	Employee_Projects_ID  = '$Emp_pr_ID'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Employee_Project updating successfully')</script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}


// deletin employee_project
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_emp_project'])) {
    // Extract the project ID from the form
    $delete_emp_project = $_POST['delete_emp_pro'];

    // Check if the project ID is provided
    if ($delete_emp_project) {
        // SQL query to delete the project from the database
        $delete_query = "DELETE FROM employee_projects WHERE Employee_Projects_ID = '$delete_emp_project'";

        // Execute the query
        $delete_result = mysqli_query($conn, $delete_query);

        // Check if the deletion was successful
        if ($delete_result) {
            
            echo "<script>window.location.href='Emp_project.php';</script>"; // Redirect to refresh the page
        } else {
            echo "<script>alert('Failed to delete the project');</script>";
        }
    } else {
        echo "<script>alert('Invalid project ID');</script>";
    }
}

// Fetch employee data

$Counter = 1;
$select_query = "SELECT * FROM employee_projects"; 
$employee_result = mysqli_query($conn, $select_query);


?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emp_project</title>
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
              <h2 class="font-bold text-3xl  mt-[40px] pl-10">Emp_project</h2>
        <div class="flex mx-10 my-5 flex  justify-end">
            <button onclick="window.location.href='emp_project_reg.php';" class="px-16 justify-end   py-3 bg-cyan-400 rounded hover:bg-cyan-500 text-black font-semibold text-xl ml-[500px]">Create Emp_project</button>
        </div >
        <div class="ml-5" >
         <table class="  text-sm text-left   w-[1000px]" >
            <tr class="">
               <th class="text-left  text-[20px] p-[20px]">Employee_ID</th>
               <th class="text-left  text-[20px] p-[20px]">Project_ID</th>
               <th class="text-left  text-[20px] p-[20px] ">Action</th>
               <?php
           while ($row = mysqli_fetch_assoc($employee_result)) {
            $Emp_pr_ID = $row['Employee_Projects_ID'];
            $Emp_ID = $row['employee_id'];
            $Project_ID = $row['project_id'];

          

           ?>
           <tr class="ml-[20px]">
            <td class="px-4 text-left"><?php echo    $Emp_ID ?></td>
            <td class="px-4 text-left"><?php echo  $Project_ID?></td>
            <td class="flex mx-6 p-[15px]">

            <!-- update btn -->
            <button onclick="updateEmpProject('<?php echo $Emp_pr_ID; ?>' ,'<?php echo $Emp_ID; ?>', '<?php echo $Project_ID; ?>')"
    class="bg-cyan-400 text-white font-bold p-[5px] rounded-md">
    <span><i class="fa fa-pencil-square" aria-hidden="true"></i></span>
</button>


         <form class="mx-4" action="" method="post" enctype="multipart/form-data">
             <input type="hidden" name="delete_emp_pro" value="<?php echo $Emp_pr_ID; ?>">
             <button type="submit" name="delete_emp_project" class="bg-red-400 text-white font-bold  p-[5px] rounded-md" ><span><i class="fa fa-trash-o" aria-hidden="true"></i></span></button>
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

   
   <!-- Update Employee Project Popup -->
   <div id="update_em_pro_popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
      <div class="relative p-6 bg-white rounded-md shadow-lg max-w-lg w-full">
          <!-- Close icon -->
          <button onclick="close_pop()" class="absolute top-[2px] right-2 bg-gray-300 p-2 rounded-lg hover:bg-gray-400">
            <i class="fa-solid fa-x"></i>
          </button>
          
          <!-- Form  -->
          <form action="Emp_project.php" method="post" class="space-y-4 mt-10" enctype="multipart/form-data">

              <div class="grid grid-cols-1 gap-4">
                
                <input type="hidden" name="Employee_Projects_ID" id="Employee_Projects_ID" />
                
                  
                  <!-- Emp_ID -->
                  <div>
                      <input 
                          name="Employee_ID"
                          type="text" 
                          placeholder="Emp_ID" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      />
                  </div>
                  <br>
                  <!-- Project_ID -->
                  <div>
                      <input 
                          name="Project_ID"
                          type="text" 
                          placeholder="Project_ID" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      />
                  </div>
                  <br>
              
               
              
              <!-- Submit Button -->
              <div class="text-center">
                  <button 
                  name="update_emp_project"
                      type="submit" 
                      class="w-full px-6 py-3 bg-cyan-500 text-black bg-cyan-400 rounded hover:bg-cyan-500 font-semibold"
                  >
                      Update Emp_project
                  </button>
              </div>
          </form>
      </div>
  </div>










  <script src="app.js"></script>
</body>
</html>