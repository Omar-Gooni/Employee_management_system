<?php
session_start();
if (!isset($_SESSION['Admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<?php

include "conn.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_project'])) {
    // Extract values from the form
    $Project_name = $_POST['Project_name'] ?? null;
    $start_Date = $_POST['start_Date'] ?? null;
    $End_Date = $_POST['End_Date'] ?? null;
    $Budget = $_POST['Budget'] ?? null;
    $Department_ID = $_POST['Department_ID'] ?? null;

    // Validate input fields
    if ($Project_name && $start_Date && $End_Date && $Budget && $Department_ID) {
        // Prepare the SQL query
        $sql = "INSERT INTO projects (Project_name, start_Date, End_Date, Budget, Department_ID) 
                VALUES ('$Project_name', '$start_Date', '$End_Date', '$Budget', '$Department_ID')";

        // Execute the query
        $query = mysqli_query($conn, $sql);

        // Check if the query was successful
        if ($query) {
            echo "<script>alert('Project added successfully')</script>";
        } else {
            echo "<script>alert('Failed to add Project')</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields')</script>";
    }
}

// update project
if (isset($_POST['Update_project'])) {
    // Extract form data
    $project_id = $_POST['Project_ID']; // Ensure this matches the hidden input field
    $project_name = $_POST['Project_name'];
    $start_date = $_POST['start_Date'];
    $end_date = $_POST['End_Date'];
    $budget = $_POST['Budget'];
    $department_id = $_POST['Department_ID'];

    // Update the project in the database
    $query = "UPDATE projects 
              SET Project_name = '$project_name', 
                  start_Date = '$start_date', 
                  End_Date = '$end_date', 
                  Budget = '$budget', 
                  Department_ID = '$department_id' 
              WHERE Project_ID = '$project_id'";
    
    $result = mysqli_query($conn, $query);

    // Handle the result
    if ($result) {
        echo "<script>alert('Project updated successfully');</script>";
    } else {
        echo "<script>alert('Failed to update project');</script>";
    }
}


// deleting project
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_project'])) {
    // Extract the project ID from the form
    $delete_project_id = $_POST['delete_project_id'];

    // Check if the project ID is provided
    if ($delete_project_id) {
        // SQL query to delete the project from the database
        $delete_query = "DELETE FROM projects WHERE project_id = '$delete_project_id'";

        // Execute the query
        $delete_result = mysqli_query($conn, $delete_query);

        // Check if the deletion was successful
        if ($delete_result) {
            echo "<script>alert('Project deleted successfully');</script>";
            echo "<script>window.location.href='Projects.php';</script>"; // Redirect to refresh the page
        } else {
            echo "<script>alert('Failed to delete the project');</script>";
        }
    } else {
        echo "<script>alert('Invalid project ID');</script>";
    }
}





// Fetch employee data
$select_query = "SELECT * FROM projects"; 
$employee_result = mysqli_query($conn, $select_query);

$Counter = 1;


?>









<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project</title>
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
          <div class="ml-0">
              <h2 class="font-bold text-3xl  mt-[40px] pl-10">Projects</h2>
        <div class="flex mx-10 my-5 flex  justify-end">
            <button onclick="window.location.href='project_reg.php';" class="px-16 justify-end   py-3 bg-cyan-400 rounded hover:bg-cyan-500 text-black font-semibold text-xl ml-[500px]">Add Project</button>
        </div >
        <div class="ml-5" >
         <table class="  text-sm text-left   w-[1000px]  " >
            <tr class="">
               <th class="text-left  text-[20px] p-[20px]">ID</th>
               <th class="text-left  text-[20px] p-[20px]">Project_Name</th>
               <th class="text-left  text-[20px] p-[20px]">Start_Date</th>
               <th class="text-left  text-[20px] p-[20px]">End_Date</th>
               <th class="text-left  text-[20px] p-[20px]">Budget</th>
               <th class="text-left  text-[20px] p-[20px]">Department_ID</th>
               <th class="text-left  text-[20px] p-[20px] ">Action</th>
           </tr>
           <?php
           while ($row = mysqli_fetch_assoc($employee_result)) {
            $project_id = $row['project_id'];
            $project_name = $row['project_name'];
            $start_date = $row['start_date'];
            $end_date = $row['end_date'];
            $budget = $row['budget'];
            $Department_ID = $row['department_id'];
          

           ?>
           <tr class="ml-[20px]">
           <td class="px-4 text-left"><?php echo  $project_id ?></td>
            <td class="px-4 text-left"><?php echo  $project_name ?></td>
            <td class="px-4 text-left"><?php echo  $start_date ?></td>
            <td class="px-4 text-left" ><?php echo  $end_date ?></td>
            <td class="px-4 text-left"><?php echo  $budget ?></td>
            <td class="px-4 text-left"><?php echo  $Department_ID ?></td>

            <td class="flex mx-6 p-[15px]">
                <!-- update btn -->
                <button 
            onclick="updateProject(<?php echo $project_id; ?>, '<?php echo addslashes($project_name); ?>', '<?php echo $start_date; ?>', '<?php echo $end_date; ?>', '<?php echo $budget; ?>', '<?php echo $Department_ID; ?>')" 
            class="bg-cyan-400 text-white font-bold p-[5px] rounded-md">
            <span><i class="fa fa-pencil-square" aria-hidden="true"></i></span>
        </button>


        <form class="mx-4" action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="delete_project_id" value="<?php echo $project_id; ?>">
    <button type="submit" name="delete_project" class="bg-red-400 text-white font-bold p-[5px] rounded-md">
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

    <!--update project -->
    <div id="update_pro_popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="relative p-6 bg-white rounded-md shadow-lg max-w-lg w-full">
        <button onclick="document.getElementById('update_pro_popup').classList.add('hidden')" class="absolute top-2 right-2 bg-gray-300 p-2 rounded-lg hover:bg-gray-400">
            <i class="fa-solid fa-x"></i>
        </button>
        <form action="" method="post" class="space-y-4">
            <input type="hidden" name="Project_ID" id="update_project_id" />
            <div>
                <input name="Project_name" id="update_project_name" type="text" placeholder="Project Name" class="w-full px-4 py-2 border border-gray-300 rounded-md" />
            </div>
            <div>
                <input name="start_Date" id="update_start_date" type="date" class="w-full px-4 py-2 border border-gray-300 rounded-md" />
            </div>
            <div>
                <input name="End_Date" id="update_end_date" type="date" class="w-full px-4 py-2 border border-gray-300 rounded-md" />
            </div>
            <div>
                <input name="Budget" id="update_budget" type="text" placeholder="Budget" class="w-full px-4 py-2 border border-gray-300 rounded-md" />
            </div>
            <div>
                <input name="Department_ID" id="update_department_id" type="text" placeholder="Department ID" class="w-full px-4 py-2 border border-gray-300 rounded-md" />
            </div>
            <button type="submit"
            name="Update_project"
             class="w-full px-6 py-3 bg-cyan-500 text-white rounded-md hover:bg-cyan-600 font-semibold">
                Update Project
            </button>
        </form>
    </div>
</div>











  <script src="app.js"></script>
</body>
</html>