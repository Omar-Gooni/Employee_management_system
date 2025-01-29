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
    <title>Dashboard</title>
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
          <div class="ml-64 mt-[-30px]">
            <!-- header -->
                <h1 class="ml-[-200px] mt-[35px] font-bold text-[25px]">Dashboard</h1>
                <!-- cards -->
            <div class="flex justify-between ml-[-100px] mt-[30px] ">
            <div class="">
                    <!-- employee -->
            <div class="w-[170px] bg-cyan-400 h-[100px] rounded-lg text-center text-[20px] mt-[20px] hover:bg-cyan-500 font-semibold">
                <h1 >Employee</h1>
                <i class="fa-solid fa-people-group mt-[10px]"></i>
                <h1 class="" >10</h1>
            </div>
            <!-- department -->
            <div class="w-[170px] bg-cyan-400 h-[100px] rounded-lg text-center text-[20px] mt-[20px] hover:bg-cyan-500 font-semibold mt-[50px]">
                <h1>Department</h1>
                <i class="fa-solid fa-house"></i>
                <h1>5</h1>
            </div>
            </div>
           <div class="ml-[300px]">
             <!-- projects -->
             <div class="w-[170px] bg-cyan-400 h-[100px] rounded-lg text-center text-[20px] mt-[20px] hover:bg-cyan-500 font-semibold">
                <h1>projects</h1>
                <i class="fa-solid fa-hands-holding-circle"></i>
                <h1>4</h1>
            </div>
            <!-- admins -->
            <div class="w-[170px] bg-cyan-400 h-[100px] rounded-lg text-center text-[20px] mt-[20px] hover:bg-cyan-500 font-semibold mt-[50px]">
                <h1>Admins</h1>
                <i class="fa-solid fa-user-tie"></i>
                <h1>5</h1>
            </div>
           </div>
          </div>
          <!-- content -->
          <div class="mt-[50px] ml-[-160px]">
            <!-- top 10 attendance  -->
            <h2 class="text-xl font-bold my-4 text-center">Top  Attendance</h2>
            <table class="border-collapse  w-full  leading-8 px-3">
                <tr class="border-b-2 border-gray-300 ">
                    <th class="text-start px-9">Attand_ID</th>
                    <th class="text-start px-9">Emp_ID</th>
                    <th class="text-start px-9">Status</th>
                    <th class="text-start px-9">Check_in</th>
                    <th class="text-start px-9">Check_out</th>
                </tr>
                <tr>
                    <td class="px-9">1</td>
                    <td class="px-9">1</td>
                    <td class="px-9">Present</td>
                    <td class="px-9">8:00Am 1-12-2024</td>
                    <td class="px-9">12:00pm 1-12-2024</td>
                </tr>
                <tr>
                    <td class="px-9">2</td>
                    <td class="px-9">2</td>
                    <td class="px-9">Absent</td>
                    <td class="px-9">____________</td>
                    <td class="px-9">_____________</td>
                </tr>
                <tr>
                    <td class="px-9">3</td>
                    <td class="px-9">3</td>
                    <td class="px-9">Present</td>
                    <td class="px-9">8:00Am 1-12-2024</td>
                    <td class="px-9">12:00pm 1-12-2024</td>
                </tr>
                
            </table>
        </div>
          </div>
    </section>
</body>
</html>