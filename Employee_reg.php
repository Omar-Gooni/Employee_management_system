
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
<div id="creat_emp_popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
      <div class="relative p-6 bg-white rounded-md shadow-lg max-w-lg w-full">
        
          
          <!-- Form  -->
          <form action="Employee.php" method="post" class="space-y-4 mt-16" enctype="multipart/form-data">

              <div class="grid grid-cols-2 gap-4">
                  <!-- emp name -->
                  <div>
                      <input 
                          name="emp_name"
                          type="text" 
                          placeholder="Emp_Name" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      />
                  </div>
                  <!-- emp email -->
                  <div>
                      <input 
                          name="emp_email"
                          type="text" 
                          placeholder="Emp_Email" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      />
                  </div>
                  <!-- emp password -->
                  <div>
                      <input 
                          name="emp_password"
                          type="text" 
                          placeholder="Emp_password" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      />
                  </div>
                  <!-- job_tital -->
                  <div>
                      <input 
                          name="job_tital"
                          type="text" 
                          placeholder="job_tital" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      />
                  </div>
                  <!-- salary -->
                  <div>
                      <input 
                          name="emp_Salary"
                          type="text" 
                          placeholder="emp_Salary" 
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
                  name="create_emp"
                      type="submit" 
                      class="w-full px-6 py-3 bg-cyan-500 text-white rounded-md hover:bg-cyan-600 font-semibold"
                  >
                      Create Emplooyee
                  </button>
              </div>
          </form>
      </div>
  </div>

  <script src="app.js" ></script>
</body>
</html>