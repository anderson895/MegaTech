<?php 
$fetch_all_return = $db->fetch_all_return();

if ($fetch_all_return): ?>
    <?php foreach ($fetch_all_return as $return):

        // Kulay ng status
        $status = $return['return_status']; // e.g. '1', '2', etc.
        $status_color = match ($status) {
            1 => 'text-yellow-500', // Return Request
            2 => 'text-red-500',    // Cancelled by Admin
            3 => 'text-red-500',    // Cancelled by Customer
            4 => 'text-green-500',  // Approved
            default => 'text-gray-500'
        };

        // Enable or disable buttons based on status
        $approveDisabled = ($status !== 1) ? 'disabled' : '';
        $cancelDisabled  = ($status !== 1) ? 'disabled' : '';

        // Status label
        $statusText = match ($status) {
            1 => 'Return Request',
            2 => 'Cancelled by Admin',
            3 => 'Cancelled by Customer',
            4 => 'Approved',
            default => 'Unknown'
        };
    ?>
    <tr>
        <td class="p-2"><?php echo htmlspecialchars($return['order_code']); ?></td>
        <td class="p-2"><?php echo htmlspecialchars(ucfirst($return['Fullname'])); ?></td>
        <td class="p-2"><?php echo htmlspecialchars($return['return_date']); ?></td>
        <td class="p-2"><?php echo htmlspecialchars($return['return_reason']); ?></td>
        <td class="p-2 <?= $status_color; ?>"><strong><?= $statusText ?></strong></td>

        <td class="p-2 text-center">
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-1 sm:space-y-0 sm:space-x-2">
                <a href="view_order?order_id=<?= $return['order_id']?>" 
                    class="bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-md text-sm font-medium transition inline-flex items-center">
                    <span class="material-icons align-middle text-sm mr-1">visibility</span> View
                </a>

                <button 
                    class="bg-green-600 btnReturnToggler hover:bg-green-700 text-white py-1 px-3 rounded-md text-sm font-medium transition disabled:opacity-50 disabled:cursor-not-allowed"
                    data-return_id="<?= $return['return_id'] ?>"
                    data-order_user_id="<?= $return['order_user_id'] ?>"
                    data-action="approve"
                    <?= $approveDisabled ?>
                >
                    <span class="material-icons align-middle text-sm">check_circle</span> Approve
                </button>

                <button 
                    class="bg-red-600 btnReturnToggler hover:bg-red-700 text-white py-1 px-3 rounded-md text-sm font-medium transition disabled:opacity-50 disabled:cursor-not-allowed"
                    data-return_id="<?= $return['return_id'] ?>"
                    data-order_user_id="<?= $return['order_user_id'] ?>"
                    data-action="cancel"
                    <?= $cancelDisabled ?>
                >
                    <span class="material-icons align-middle text-sm">cancel</span> Cancel
                </button>
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



