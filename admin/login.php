<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>HeadStaff Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="assets/logo1.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-r from-indigo-100 via-white to-indigo-100">

  <?php include "../function/PageSpinner.php"; ?>

  <!-- Card -->
<div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 relative transition-all duration-300 hover:shadow-2xl">

  <!-- Spinner Overlay -->
  <div id="spinner" class="absolute inset-0 bg-white bg-opacity-70 flex items-center justify-center z-50" style="display:none;">
    <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
  </div>

  <!-- Logo and Label -->
  <div class="flex flex-col items-center mb-6">
    <img src="../assets/logo/logo1.jpg" alt="HeadStaff Logo" class="h-24 w-24 object-contain rounded-full shadow mb-2">
    <h2 class="text-xl font-bold text-gray-700 tracking-wide">Administrator</h2>
  </div>

  <!-- Login Form -->
  <form id="frmLogin" class="space-y-5">
    <div>
      <label for="username" class="block text-sm font-semibold text-gray-700">Username</label>
      <input type="text" id="username" name="username" required
             class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
    </div>

    <div>
      <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
      <input type="password" id="password" name="password" required
             class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
    </div>

    <div class="flex items-center justify-between text-sm">
      <label class="flex items-center">
        <input id="remember_me" name="remember_me" type="checkbox"
               class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
        <span class="ml-2 text-gray-600">Remember me</span>
      </label>
      <!-- <a href="#" class="text-blue-600 hover:underline">Forgot password?</a> -->
    </div>

    <div>
      <button type="submit" id="btnLogin"
              class="w-full py-2 px-4 text-white font-semibold bg-blue-600 rounded-lg hover:bg-blue-700 transition-all focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1">
        Sign in
      </button>
    </div>
  </form>
</div>


  <script src="js/app.js"></script>
</body>
</html>
