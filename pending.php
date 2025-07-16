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
            Please check your email and click the verification link to activate your account.
        </p>
        
        <p class="text-gray-500 text-sm">
            Once verified, you will be able to log in using your credentials.
        </p>
    </div>
</div>

<?php include "components/footer.php"; ?>
