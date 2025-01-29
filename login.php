<?php
session_start();
if (isset($_SESSION['Admin_id'])) {
    header("Location: Dashboard.php");
    exit();
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>



<body class="bg-gray-300 ">
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="relative  flex flex-col flex-col-reverse md:flex-row items-center bg-white p-6 rounded-lg shadow-md">
      <!-- Left side (Form) -->
      <div class="relative flex-1 w-full md:w-1/2 p-4">
        <h2 class="text-3xl font-bold mb-6 text-center">Login</h2>
        

    <div class="mb-4">
    <form action="login1.php" method="post" enctype="multipart/form-data">
        <input
            type="email"
            name="email"
            placeholder="Email"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
    </div>
    <div class="mb-4">
        <input
            type="password"
            name="password"
            placeholder="Password"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
    </div>
    <div class="mb-6 flex justify-end">
        <label for="role">Admin</label>
        <input type="checkbox" name="role" value="admin" id="role">
    </div>
    <div class="flex justify-center">
        <button
            type="submit"
            class="w-full bg-cyan-500 text-white font-semibold py-2 rounded-md hover:bg-cyan-600 transition"
        >
            Login
        </button>
    </div>
</form>

      </div>

      <!-- Right side (Image) -->
      <div class=" md:block md:w-1/2 flex justify-center p-4">
    <img src="images/admin lock.png" class="w-[200px] h-[200px]" alt="">
      </div>
    </div>
  </div>
</body>
</html>
