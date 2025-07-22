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

<div class="max-w-4xl mx-auto mt-12 p-8 bg-white rounded-2xl shadow-xl border font-sans text-gray-800">
  <!-- Logo & Header -->
  <div class="text-center mb-10 border-b pb-6">
    <img src="../assets/logo/logo1.jpg" alt="MegaTech Logo" class="h-16 w-16 mx-auto rounded-full border shadow mb-3 object-cover">
    <h1 class="text-3xl font-bold">Reservation Details</h1>
    <p class="text-blue-600 font-semibold uppercase tracking-wider text-sm">MegaTech Services</p>
  </div>

  <!-- Order Items Table -->
  <div class="mb-10">
    <h2 class="text-xl font-semibold text-gray-700 mb-4">Ordered Items</h2>
    <div class="overflow-x-auto border rounded-lg">
      <table class="w-full text-sm">
       <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
        <tr>
          <th class="px-4 py-3 text-left">Product</th>
          <th class="px-4 py-3 text-left">Quantity</th>
          <th class="px-4 py-3 text-left">Price</th>
          <th class="px-4 py-3 text-left">Subtotal</th>
          <?php if ($order['order_status'] === 'pickedup'): ?>
            <th class="px-4 py-3 text-left">Return Status</th>
            <th class="px-4 py-3 text-left">Action</th>
          <?php endif; ?>
        </tr>
      </thead>

    <tbody class="text-gray-700 bg-white">
  <?php while ($item = mysqli_fetch_assoc($fetch_order_item)) : ?>
  <tr class="border-t">
    <td class="px-4 py-3"><?= htmlspecialchars($item['prod_name']) ?></td>
    <td class="px-4 py-3"><?= $item['item_qty'] ?></td>
    <td class="px-4 py-3">₱<?= number_format($item['item_product_price'], 2) ?></td>
    <td class="px-4 py-3">₱<?= number_format($item['item_qty'] * $item['item_product_price'], 2) ?></td>

    <?php if ($order['order_status'] === 'pickedup'): ?>
      <!-- Return Status -->
      <td class="px-4 py-3">
        <?php
          $status = $item['return_status'];
          if ($status == 1) {
              echo '<span class="text-yellow-600 font-medium text-sm">Return Request</span>';
          } elseif ($status == 2) {
              echo '<span class="text-red-600 font-medium text-sm">Cancelled by Admin</span>';
          } elseif ($status == 3) {
              echo '<span class="text-red-500 font-medium text-sm">Cancelled</span>';
          } elseif ($status == 4) {
              echo '<span class="text-green-600 font-medium text-sm">Approved</span>';
          } else {
              echo '<span class="text-gray-500 text-sm italic">No Return</span>';
          }
        ?>
      </td>

      <!-- Action Buttons -->
      <td class="px-4 py-3">
        <?php if ($status == 1): ?>
          <button class="cancelReturnBtn bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded shadow"
            data-item_id="<?= $item['item_id'] ?>"
            data-prod_name="<?= $item['prod_name'] ?>"
            data-action="cancelReturn"
            >
            Cancel
          </button>
        <?php elseif (in_array($status, [2, 3, 4])): ?>
          <button class="bg-gray-300 text-gray-600 text-xs px-3 py-1 rounded shadow cursor-not-allowed" disabled>
            Cancel
          </button>
        <?php else: ?>
          <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
          <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
          <button 
            class="returnItemToggler bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded shadow"
            data-item_id="<?= $item['item_id'] ?>"              
            data-prod_name="<?= $item['prod_name'] ?>"              
            data-item_qty="<?= $item['item_qty'] ?>"              
          >
            Return
          </button>
        <?php endif; ?>
      </td>
    <?php endif; ?>
  </tr>
  <?php endwhile; ?>
</tbody>


      </table>
    </div>
    <div class="text-right mt-4">
      <span class="text-base font-semibold">Total Amount:</span>
      <span class="text-xl text-green-600 font-bold ml-2">₱<?= number_format($order['order_total'], 2) ?></span>
    </div>
  </div>

  <!-- Reservation Info -->
  <div class="grid md:grid-cols-2 gap-6 mb-12 text-sm md:text-base">
    <div class="space-y-2">
      <p><span class="font-medium">Reservation Code:</span> <?= $order['order_code'] ?></p>
      <p><span class="font-medium">Customer Name:</span> <?= ucfirst($order['Fullname']) ?></p>
      <p><span class="font-medium">Date Ordered:</span> <?= $order['order_date'] ?></p>
    </div>
    <div class="space-y-2">
      <p><span class="font-medium">Payment Method:</span> <?= $order['order_payment_method'] ?></p>
      <p><span class="font-medium">Pickup Schedule:</span> <i><?= $formattedPickup ?></i></p>
      <p><span class="font-medium">Status:</span> <i><?= $order['order_status'] === 'pending' ? 'Waiting Approval' : htmlspecialchars($order['order_status']) ?></i></p>
    </div>
  </div>

  <!-- QR and Proof Section -->
  <div class="grid md:grid-cols-2 gap-6 mb-8">
    <!-- QR Code -->
    <div class="text-center bg-gray-50 rounded-lg p-4 shadow border">
      <p class="uppercase text-sm text-gray-600 font-semibold mb-3">QR Code</p>
      <div class="w-44 h-44 mx-auto bg-white border rounded-lg shadow p-2">
        <img src="../qrcodes/qr_<?= $order['order_id'] ?>.png" alt="QR Code" class="w-full h-full object-contain">
      </div>
      <a href="../qrcodes/qr_<?= $order['order_id'] ?>.png" download
         class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded shadow transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"></path>
        </svg>
        Download QR
      </a>
    </div>

    <!-- Payment Proof -->
    <div class="text-center bg-gray-50 rounded-lg p-4 shadow border">
      <p class="uppercase text-sm text-gray-600 font-semibold mb-3">Proof of Payment</p>
      <div class="w-44 h-44 mx-auto bg-white border rounded-lg shadow p-2">
        <img src="../proofPayment/<?= $order['order_down_payment_receipt'] ?>" alt="Proof of Payment"
             class="w-full h-full object-cover rounded-md">
      </div>
      <a href="../proofPayment/<?= $order['order_down_payment_receipt'] ?>" download
         class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded shadow transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"></path>
        </svg>
        Download Receipt
      </a>
    </div>
  </div>

  <!-- Footer -->
  <div class="mt-12 pt-6 border-t text-center text-sm text-gray-500 italic">
    Please present the downloaded QR during pickup.<br>Thank you for trusting <span class="text-blue-600 font-medium">MegaTech</span>!
  </div>
</div>












<div id="returnItemModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-2xl relative">
    
    <!-- Close Button -->
    <button id="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-red-600 text-3xl font-bold">&times;</button>

    <!-- Title -->
    <h2 class="text-3xl font-semibold mb-6 text-gray-800 text-center">Return Item</h2>

    <!-- Modal Details -->
    <div id="modalDetails" class="mb-6 text-sm text-gray-600 text-center">
      Please provide details of the item you want to return.
    </div>

    <!-- Return Form -->
    <form id="returnItemForm" class="space-y-4">
      <!-- ✅ Hidden field for original quantity -->
      <input type="hidden" id="original_qty" name="original_qty">

      <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
        <div>
          <label for="prod_name" class="block text-sm font-medium text-gray-700">Product Name</label>
          <input type="text" id="prod_name" name="prod_name" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        
        <div hidden>
          <label for="item_id" class="block text-sm font-medium text-gray-700">Item ID</label>
          <input type="text" id="item_id" name="item_id" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
          <label for="item_qty" class="block text-sm font-medium text-gray-700">Quantity</label>
          <input type="number" id="item_qty" name="item_qty" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
          <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Return</label>
          <select id="reason" name="reason" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <option value="">-- Select Reason --</option>
            <option value="damaged">Damaged Item</option>
            <option value="wrong_item">Wrong Item</option>
            <option value="defective">Defective Product</option>
            <option value="other">Other</option>
          </select>
        </div>
      </div>

      <!-- Upload Section -->
      <div>
        <label for="return_image" class="block text-sm font-medium text-gray-700">Upload Photo of Item</label>
        <input type="file" id="return_image" name="return_image" accept="image/*" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <!-- Submit Button -->
      <div class="text-center mt-6">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition duration-300">
          Submit Return Request
        </button>
      </div>
    </form>
  </div>
</div>










<?php include "components/footer.php"; ?>
