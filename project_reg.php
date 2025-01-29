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
<div id="popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
      <div class="relative p-6 bg-white rounded-md shadow-lg max-w-lg w-full">
         
          
          <!-- Form  -->
          <form action="Projects.php" method="post" class="space-y-4 mt-16" enctype="multipart/form-data">
            <H1 class="text-center font-bold">Create Project</H1>
              <div class="grid grid-cols-2 gap-4">
                  <!-- Project_name -->
                  <div>
                      <input 
                          name="Project_name"
                          type="text" 
                          placeholder="Project_name" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      />
                  </div>
                  <!-- start_Date-->
                  <div>
                      <input 
                          name="start_Date"
                          type="date" 
                          placeholder="start_Date" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      />
                  </div>
                  <!-- End_Date -->
                  <div>
                      <input 
                          name="End_Date"
                          type="date" 
                          placeholder="End_Date" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      />
                  </div>
                  <!-- Budget -->
                  <div>
                      <input 
                          name="Budget"
                          type="text" 
                          placeholder="Budget" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      />
                  </div>
                  <!-- Department_ID -->
                  <div>
                      <input 
                          name="Department_ID"
                          type="text" 
                          placeholder="Department_ID" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      />
                  </div>
                  <br>
              
               
              
              <!-- Submit Button -->
              <div class="text-center">
                  <button 
                  name="create_project"
                      type="submit" 
                      class="w-full px-6 py-3 bg-cyan-500 text-white rounded-md hover:bg-cyan-600 font-semibold"
                  >
                      Create Project
                  </button>
              </div>
          </form>
      </div>
  </div>

  <script src="app.js" ></script>
</body>
</html>