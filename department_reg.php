
<?php
session_start();
if (!isset($_SESSION['Admin_id'])) {
    header("Location: login.php");
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>employee_reg</title>
    <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-cyan-400">

       <!-- create deparment popup -->
    <div id="creat_dep" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
      <div class="relative p-6 bg-white rounded-md shadow-lg max-w-lg w-full">
        
          
          <!-- Form  -->
          <form action="Departments.php" method="post" class="space-y-4 mt-10" enctype="multipart/form-data">
    <h1 class="font-bold">Create Department</h1>
              <div class="grid grid-cols-1 gap-4">
                  
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
                  name="create_dept"
                      type="submit" 
                      class="w-full px-6 py-3 bg-cyan-500 text-black bg-cyan-400 rounded hover:bg-cyan-500 font-semibold"
                  >
                      Create Department
                  </button>
              </div>
          </form>
      </div>
  </div>

  <script src="app.js" ></script>
</body>
</html>