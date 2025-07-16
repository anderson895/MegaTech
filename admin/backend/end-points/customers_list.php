<?php 
$fetch_all_students = $db->fetch_all_customers();

if ($fetch_all_students): ?>
    <?php foreach ($fetch_all_students as $student):
        $status_text = match ($student['status']) {
            1 => "Verified",
            2 => "Restricted",
            default => "Not Verified"
        };

        $status_color = match ($student['status']) {
            1 => "text-green-500",
            2 => "text-red-500",
            default => "text-yellow-500"
        };
    ?>
    <tr>
        <td class="p-2"><?php echo htmlspecialchars($student['user_id']); ?></td>
        <td class="p-2"><?php echo htmlspecialchars(ucfirst($student['Fullname'])); ?></td>
        <td class="p-2"><?php echo htmlspecialchars($student['Email']); ?></td>
        <td class="p-2"><?php echo htmlspecialchars($student['Phone']); ?></td>
        <td class="p-2"><?php echo htmlspecialchars(ucfirst($student['user_type'])); ?></td>
        <td class="p-2 <?= $status_color; ?>"><strong><?php echo $status_text; ?></strong></td>
        <td class="p-2 text-center">
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-1 sm:space-y-0 sm:space-x-2">
                <?php if ($student['status'] == 1): ?>
                    <!-- Restrict Button -->
                    <button 
                        class="bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded-md text-sm font-medium transition userActionToggler"
                        data-user_id="<?= $student['user_id'] ?>"
                        data-fullname="<?= htmlspecialchars($student['Fullname'], ENT_QUOTES) ?>"
                        data-action="restrict"
                    >
                        <span class="material-icons align-middle text-sm">block</span> Restrict
                    </button>

                <?php elseif ($student['status'] == 2): ?>
                    <!-- Set Active Button -->
                    <button 
                        class="bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded-md text-sm font-medium transition userActionToggler"
                        data-user_id="<?= $student['user_id'] ?>"
                        data-fullname="<?= htmlspecialchars($student['Fullname'], ENT_QUOTES) ?>"
                        data-action="activate"
                    >
                        <span class="material-icons align-middle text-sm">check_circle</span> Activate
                    </button>

                <?php else: ?>
                    <!-- Accept Button -->
                    <button 
                        class="bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded-md text-sm font-medium transition userActionToggler"
                        data-user_id="<?= $student['user_id'] ?>"
                        data-fullname="<?= htmlspecialchars($student['Fullname'], ENT_QUOTES) ?>"
                        data-action="accept"
                    >
                        <span class="material-icons align-middle text-sm">check_circle</span> Accept
                    </button>

                    <!-- Decline Button -->
                    <button 
                        class="bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded-md text-sm font-medium transition userActionToggler"
                        data-user_id="<?= $student['user_id'] ?>"
                        data-fullname="<?= htmlspecialchars($student['Fullname'], ENT_QUOTES) ?>"
                        data-action="decline"
                    >
                        <span class="material-icons align-middle text-sm">cancel</span> Decline
                    </button>
                <?php endif; ?>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7" class="p-2 text-center">No record found.</td>
    </tr>
<?php endif; ?>


<div id="spinnerOverlay" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
  <svg class="animate-spin h-12 w-12 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
  </svg>
</div>
