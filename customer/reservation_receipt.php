<?php
include "components/header.php";

$fetch_order = $db->fetch_order($_GET['order_id']);
$fetch_order_item = $db->fetch_order_item($_GET['order_id']);
$order = mysqli_fetch_assoc($fetch_order);

// Format pickup date and time
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
    <h1 class="text-3xl font-bold text-gray-800">Reservation Receipt</h1>
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
      <p><span class="font-medium">Status:</span> <i><?= $order['order_status'] === 'pending' ? 'Waiting Approval' : htmlspecialchars($order['order_status']) ?></i></p>
    </div>
  </div>

  <!-- QR & Payment Proof -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
    <!-- QR Code -->
    <div class="text-center">
      <p class="uppercase text-sm text-gray-500 font-semibold mb-2">QR Code</p>
      <div class="w-44 h-44 mx-auto bg-white border rounded-lg shadow p-2">
        <img src="../qrcodes/qr_<?= $order['order_id'] ?>.png" alt="QR Code" class="w-full h-full object-contain">
      </div>
      <a href="../qrcodes/qr_<?= $order['order_id'] ?>.png" download
         class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"></path>
        </svg>
        Download QR
      </a>
    </div>

    <!-- Payment Proof -->
    <div class="text-center">
      <p class="uppercase text-sm text-gray-500 font-semibold mb-2">Proof of Payment</p>
      <div class="w-44 h-44 mx-auto bg-white border rounded-lg shadow p-2">
        <img src="../proofPayment/<?= $order['order_down_payment_receipt'] ?>" alt="Proof of Payment"
             class="w-full h-full object-cover rounded-md">
      </div>
      <a href="../proofPayment/<?= $order['order_down_payment_receipt'] ?>" download
         class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow transition">
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
    Please present the downloaded QR on pickup.<br>Thank you for choosing <span class="text-blue-600 font-medium">MegaTech</span>!
  </div>
</div>

<?php include "components/footer.php"; ?>
<script src="../function/js/filter_price_category.js"></script>
