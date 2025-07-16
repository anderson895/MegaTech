<?php
include "components/header.php";
include('backend/class.php');
$db = new global_class();
?>

<!-- Pending Verification Page -->
<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="max-w-md w-full bg-white shadow-md rounded-lg p-8 text-center">
        <span class="material-icons text-yellow-500 text-6xl mb-4">mail</span>
        
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Verify Your Email</h2>
        
        <p class="text-gray-600 mb-4">
            Thank you for registering!<br>
            <span class="font-semibold text-gray-800">We are currently verifying your account.</span>
        </p>

        <p class="text-gray-600 mb-4">
            Once your account is verified, your login credentials (email and password) will be sent to your email address.
        </p>
        
        <p class="text-gray-500 text-sm">
            You will then be able to log in using the credentials provided.
        </p>
    </div>
</div>

<?php include "components/footer.php"; ?>
