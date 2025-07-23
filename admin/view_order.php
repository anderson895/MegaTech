<?php
include "components/header.php";

$fetch_order = $db->fetch_order($_GET['order_id']);
$fetch_order_item = $db->fetch_order_item($_GET['order_id']);
$order = mysqli_fetch_assoc($fetch_order);

$pickupDate = $order['order_pickup_date'];
$pickupTime = $order['order_pickup_time'];

if (!empty($pickupDate) && !empty($pickupTime)) {
    $pickupDateTime = new DateTime("$pickupDate $pickupTime");
    $formattedPickup = $pickupDateTime->format('l, F j, Y - g:i A');
} else {
    $formattedPickup = 'No Schedule Date';
}
?>

<div class="max-w-5xl mx-auto mt-12 p-8 bg-white rounded-2xl shadow-lg border border-gray-200 font-sans text-gray-800">
  <!-- Header -->
  <div class="text-center border-b pb-6 mb-10">
    <div class="flex justify-center mb-2">
      <img src="../assets/logo/logo1.jpg" alt="MegaTech Logo" class="h-16 w-16 rounded-full border shadow object-cover">
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Reservation Details</h1>
    <p class="text-blue-600 font-semibold uppercase tracking-wide text-sm">MegaTech</p>
  </div>

  <!-- Order Items -->
  <div class="mb-10">
    <h2 class="text-xl font-semibold text-gray-700 mb-4">Ordered Items</h2>
    <div class="overflow-x-auto rounded-lg ">
      <table class="w-full text-sm">
        <thead class="text-gray-600 uppercase text-xs">
          <tr>
            <th class="px-4 py-2 text-left">Product</th>
            <th class="px-4 py-2 text-left">Qty</th>
            <th class="px-4 py-2 text-left">Price</th>
            <th class="px-4 py-2 text-left">Subtotal</th>
          </tr>
        </thead>
        <tbody class="text-gray-800">
          <?php while ($item = mysqli_fetch_assoc($fetch_order_item)) : ?>
            <tr>
              <td class="px-4 py-2"><?= htmlspecialchars($item['prod_name']) ?></td>
              <td class="px-4 py-2"><?= $item['item_qty'] ?></td>
              <td class="px-4 py-2">₱<?= number_format($item['item_product_price'], 2) ?></td>
              <td class="px-4 py-2">₱<?= number_format($item['item_qty'] * $item['item_product_price'], 2) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <div class="mt-4 text-right">
      <span class="text-base font-semibold">Total:</span>
      <span class="text-xl text-green-600 font-bold ml-2">₱<?= number_format($order['order_total'], 2) ?></span>
    </div>
  </div>

  <!-- Reservation Info -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12 text-sm md:text-base">
    <div class="space-y-2">
      <p><span class="font-medium">Reservation Code:</span> <?= $order['order_code'] ?></p>
      <p><span class="font-medium">Customer Name:</span> <?= ucfirst($order['Fullname']) ?></p>
      <p><span class="font-medium">Date Ordered:</span> <?= $order['order_date'] ?></p>
      <p><span class="font-medium">Payment Method:</span> <?= $order['order_payment_method'] ?></p>
      <p><span class="font-medium">Pickup Schedule:</span> <i><?= $formattedPickup ?></i></p>
      <p><span class="font-medium">Status:</span>
        <i class="<?= $order['order_status'] === 'pending' ? 'text-yellow-500' : 'text-green-600' ?>">
          <?= $order['order_status'] === 'pending' ? 'Waiting Approval' : htmlspecialchars($order['order_status']) ?>
        </i>
      </p>
    </div>
  </div>

  <!-- QR & Payment Proof Section -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
  <!-- QR Code -->
  <div class="flex flex-col items-center bg-white p-6 rounded-lg shadow-md text-center">
    <p class="uppercase text-sm text-gray-500 font-semibold mb-3">QR Code</p>
    <div class="w-44 h-44 border rounded-lg overflow-hidden bg-gray-50 p-2">
      <img src="../qrcodes/qr_<?= $order['order_id'] ?>.png" alt="QR Code" class="w-full h-full object-contain">
    </div>
    <a href="../qrcodes/qr_<?= $order['order_id'] ?>.png" download
       class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow transition">
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
      </svg>
      Download QR
    </a>
  </div>

  <!-- Proof of Payment -->
  <div class="flex flex-col items-center bg-white p-6 rounded-lg shadow-md text-center">
    <p class="uppercase text-sm text-gray-500 font-semibold mb-3">Proof of Payment</p>
    <div class="w-44 h-44 border rounded-lg overflow-hidden bg-gray-50 p-2">
      <img src="../proofPayment/<?= $order['order_down_payment_receipt'] ?>" alt="Proof of Payment"
           class="w-full h-full object-cover rounded-md">
    </div>
    <a href="../proofPayment/<?= $order['order_down_payment_receipt'] ?>" download
       class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow transition">
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
      </svg>
      Download Receipt
    </a>
  </div>
</div>

<?php
$orderStatus = strtolower($order['order_status']);
?>

<?php if ($orderStatus === 'paid') : ?>
  <!-- Only Show Set Schedule Button -->
  <div class="flex justify-center items-center mt-4">
    <button 
      class="setScheduleToggler w-full sm:w-[220px] flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition"
      data-user_id="<?= $order['order_user_id'] ?>"
      data-order_id="<?= $order['order_id'] ?>"
      data-order_code="<?= $order['order_code'] ?>"
      data-fullname="<?= ucfirst($order['Fullname']) ?>">
      <span class="material-icons mr-2 text-base">calendar_month</span>
      Set Schedule
    </button>
  </div>

<?php elseif ($orderStatus === 'scheduled') : ?>
  <!-- Show Mark as Done and Re-Schedule Buttons -->
  <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
  <input type="hidden" name="new_status" value="picked up">

  <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mt-4">
    <!-- Mark as Done -->
    <button type="submit"
            class="orderActionToggler w-full sm:w-[220px] flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition"
            data-action='pickedup'
            data-order_code='<?= $order['order_code'] ?>'
            data-user_id='<?= $order['order_user_id'] ?>'
            data-order_id='<?= $order['order_id'] ?>'>
      <span class="material-icons mr-2 text-base">check_circle</span>
      Mark as Done
    </button>

    <!-- Re-Schedule Button -->
    <button 
      class="setScheduleToggler w-full sm:w-[220px] flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition"
      data-user_id="<?= $order['order_user_id'] ?>"
      data-order_id="<?= $order['order_id'] ?>"
      data-order_code="<?= $order['order_code'] ?>"
      data-fullname="<?= ucfirst($order['Fullname']) ?>">
      <span class="material-icons mr-2 text-base">calendar_month</span>
      Re-Schedule
    </button>
  </div>

<?php elseif ($orderStatus === 'pickedup') : ?>
  <!-- Picked up message -->
  <div class="mt-10 text-center text-green-700 font-semibold text-lg">
    ✅ This reservation has already been picked up.
  </div>

<?php endif; ?>


<!-- Footer -->
<div class="mt-12 pt-6 border-t border-gray-200 text-center text-sm text-gray-500 italic">
  Please present the downloaded QR on pickup.<br>
  Thank you for choosing <span class="text-blue-600 font-medium">MegaTech</span>!
</div>





















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

<?php include "components/footer.php"; ?>
