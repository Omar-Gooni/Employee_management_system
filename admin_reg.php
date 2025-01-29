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
<div id="admin_popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="relative p-6 bg-white rounded-md shadow-lg max-w-lg w-full">
            
            <!-- Close Button -->
            <!-- <button onclick="closePopup()" class="absolute top-2 right-2 bg-gray-300 p-2 rounded-lg hover:bg-gray-400">
                <i class="fa-solid fa-x"></i>
            </button> -->
            
            <h2 class="text-xl font-bold text-center mb-4">Create Admin</h2>

            <!-- Form -->
            <form action="Admin.php" method="post" class="space-y-4" enctype="multipart/form-data">
                
                <!-- Name -->
                <div>
                    <label class="block text-gray-600">Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border rounded-md">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-gray-600">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 border rounded-md">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-gray-600">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border rounded-md">
                </div>

                <!-- Hidden Role (Default 'admin') -->
                <input type="hidden" name="role" value="admin">

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" name="create_admin" class="px-6 py-3 bg-cyan-500 text-white font-semibold rounded-md hover:bg-cyan-600">Create Admin</button>
                </div>
            </form>
        </div>
    </div>


  <script src="app.js" ></script>
</body>
</html>