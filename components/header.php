<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MEGATECH</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="assets/logo/logo1.jpg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.css" />
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>
</head>




<body class="bg-gray-50">

<?php include "function/PageSpinner.php"; ?>

<!-- Header -->
<header class="bg-white shadow">
  <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
    
    <!-- Logo -->
    <div class="flex items-center space-x-3">
      <img src="assets/logo/logo1.jpg" alt="Logo" class="w-12 h-12 hidden sm:block rounded-full">
      <a href="index.php" class="text-2xl font-bold text-gray-800 hover:text-blue-600 transition">MEGATECH</a>
    </div>

    <!-- Center Navigation -->
    <nav class="hidden md:flex space-x-8 text-gray-700 font-medium text-sm">
      <a href="index" class="hover:text-blue-600 transition">Home</a>
     <a href="index#about" class="hover:text-blue-600 transition">About</a>

      <a href="products" class="hover:text-blue-600 transition">Products</a>
    </nav>

    <!-- Auth and Cart -->
    <div class="flex items-center space-x-4 text-sm">
      <a href="login" class="text-gray-700 hover:text-blue-600 transition">Login</a>
      <span class="text-gray-500">/</span>
      <a href="signup" class="text-gray-700 hover:text-blue-600 transition">Register</a>
      <a href="login" class="text-gray-700 hover:text-blue-600 transition text-xl">🛒</a>
    </div>
  </div>
</header>

