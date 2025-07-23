<?php
include "components/header.php";

$fetch_user_info = $db->fetch_admin_info($admin_id); 
foreach ($fetch_user_info as $user):
endforeach;
?>

<div class="max-w-4xl mx-auto p-8 bg-white shadow-lg rounded-lg mt-8">
  <h2 class="text-4xl font-semibold mb-6 text-gray-900">Password Settings</h2>
  
  <form id="userPasswordFrm" class="space-y-6">
    <input type="hidden" name="requestType" value="UpdateAdminPassword">
    <input type="hidden" name="admin_id" value="<?=$admin_id?>">
    
    <!-- Old Password -->
    <div>
      <label for="old-password" class="block text-sm font-medium text-gray-700">Old Password</label>
      <input 
        type="password" 
        id="old-password" 
        name="user_CurrentPassword" 
        class="mt-1 w-full p-3  border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500"
        required
      />
    </div>

    <!-- New Password -->
    <div>
      <label for="new-password" class="block text-sm font-medium text-gray-700">New Password</label>
      <input 
        type="password" 
        id="newpassword" 
        name="user_NewPassword" 
        class="mt-1 w-full p-3 border border-gray-300 rounded-md shadow-sm"
        required
      />
    </div>

    <!-- Confirm Password -->
    <div>
      <label for="confirm-password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
      <input 
        type="password" 
        id="confirmpassword" 
        name="user_ConfirmPassword" 
        class="mt-1 w-full p-3 border border-gray-300 rounded-md shadow-sm"
        required
      />
    </div>

    <!-- Save Button -->
    <div class="flex justify-end">
      <button 
        type="submit" 
        class="bg-blue-500 text-white py-3 px-6 rounded-lg text-lg font-medium hover:bg-orange-600e"
      >
        Save
      </button>
    </div>
  </form>
</div>

<?php include "components/footer.php"; ?>