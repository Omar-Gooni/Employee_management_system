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
    <title>Attandance_reg</title>
    <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-cyan-400">
<div id="popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
      <div class="relative p-6 bg-white rounded-md shadow-lg max-w-lg w-full">
          <!-- Close icon -->
          <!-- <button onclick="Attendance_PopUp()" class="absolute top-[2px] right-2 bg-gray-300 p-2 rounded-lg hover:bg-gray-400">
            <i class="fa-solid fa-x"></i>
          </button> -->
          
          <!-- Form  -->
          <form action="Attendance.php" method="post" class="space-y-4 mt-16" enctype="multipart/form-data">
                      <h1 class="font-bold" >Attendance</h1>  
              <div class="grid grid-cols-2 gap-4">
               
                  <!-- Emp_ID-->
                  <div>
                      <input 
                          name="Emp_ID"
                          type="text" 
                          placeholder="Emp_ID" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      />
                  </div>
                  <!-- Status -->
                  <div>
                  <select 
                    name="Status" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    required>
                    <option value="" disabled selected>Select Status</option>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                </select>

                </div>
                  <!-- Check_in -->
                  <div>
                      <input 
                          name="Check_in"
                          type="datetime" 
                          placeholder="Check_in" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      />
                  </div>
                  <!-- Check_out -->
                  <div>
                      <input 
                          name="Check_out"
                          type="datetime" 
                          placeholder="Check_out" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      />
                  </div>
                  <br>
              
               
              
              <!-- Submit Button -->
              <div class="text-center">
                  <button 
                  name="create_Attendance"
                      type="submit" 
                      class="w-full px-6 py-3 bg-cyan-500 text-white rounded-md hover:bg-cyan-600 font-semibold"
                  >
                      Create Attendance
                  </button>
              </div>
          </form>
      </div>
  </div>


  <script src="app.js" ></script>
</body>
</html>