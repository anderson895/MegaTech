<?php 
$fetch_all_students = $db->fetch_all_reservation();

if ($fetch_all_students): ?>
    <?php foreach ($fetch_all_students as $student):

        $status_color = match ($student['order_status']) {
            "decline" => "text-red-500",
            "pending" => "text-yellow-500",
            default => "text-green-500"
        };

        // Check if order is not pending
        $isPending = strtolower($student['order_status']) === 'pending';
    ?>
    <tr>
        <td class="p-2"><?php echo htmlspecialchars($student['order_code']); ?></td>
        <td class="p-2"><?php echo htmlspecialchars(ucfirst($student['Fullname'])); ?></td>
        <td class="p-2"><?php echo htmlspecialchars($student['order_date']); ?></td>
        <td class="p-2"><?php echo htmlspecialchars($student['order_payment_method']); ?></td>
        <td class="p-2">₱ <?php echo htmlspecialchars(number_format($student['order_total'],2)); ?></td>
        <td class="p-2 <?= $status_color; ?>"><strong><?php echo ucfirst($student['order_status']); ?></strong></td>
        <td class="p-2 text-center">
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-1 sm:space-y-0 sm:space-x-2">

                <a href="view_order?order_id=<?= $student['order_id']?>" 
                    class="bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-md text-sm font-medium transition inline-flex items-center">
                    <span class="material-icons align-middle text-sm mr-1">visibility</span> View
                </a>

                <button 
                    class="bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded-md text-sm font-medium transition setScheduleToggler disabled:opacity-50 disabled:cursor-not-allowed"
                    data-user_id="<?= $student['user_id'] ?>"
                    data-order_id="<?= $student['order_id'] ?>"
                    data-order_code="<?= $student['order_code'] ?>"
                    data-fullname="<?= ucfirst($student['Fullname']) ?>"
                    <?= $isPending ? 'disabled' : '' ?>
                >
                    <span class="material-icons align-middle text-sm">check_circle</span> Set Schedule
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





<div id="setScheduleModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden" style="display: none;">
  <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl relative">
    <!-- Close Button -->
    <button id="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Title -->
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Set Schedule</h2>

    <!-- Modal Details -->
    <div id="modalDetails" class="mb-4 text-sm text-gray-700"></div>

    <!-- Scheduling Form -->
    <form id="setScheduleForm">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <input type="text" name="orderId" id="orderId" hidden>
        <input type="text" name="userId" id="userId" hidden>
        <input type="text" name="orderCode" id="orderCode" hidden>
        <!-- Schedule Date -->
        <div>
          <label for="scheduleDate" class="block text-sm font-medium text-gray-700 mb-1">Schedule Date</label>
          <input type="date" id="scheduleDate" name="scheduleDate" required
            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500" />
        </div>

        <!-- Schedule Time -->
        <div>
          <label for="scheduleTime" class="block text-sm font-medium text-gray-700 mb-1">Schedule Time</label>
          <input type="time" id="scheduleTime" name="scheduleTime" required
            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500" />
        </div>
      </div>

      <!-- Submit Button -->
      <div class="mt-6 text-right">
        <button type="submit"
          class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium transition">
          Save Schedule
        </button>
      </div>
    </form>
  </div>
</div>







<div id="spinnerOverlay" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
  <svg class="animate-spin h-12 w-12 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
  </svg>
</div>



