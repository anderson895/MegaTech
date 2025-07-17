<?php
include "components/header.php";

$result = $db->fetch_order($_GET['order_id']);
$order = mysqli_fetch_assoc($result);

// Format pickup date and time
$pickupDate = $order['order_pickup_date'];
$pickupTime = $order['order_pickup_time'];
$pickupDateTime = new DateTime("$pickupDate $pickupTime");
$formattedPickup = $pickupDateTime->format('l, F j, Y - g:i A');
?>

<div class="max-w-4xl mx-auto bg-white p-10 mt-12 shadow-xl rounded-2xl border border-gray-200 font-sans">
  <!-- Header -->
  <div class="mb-8 text-center border-b pb-5">
    <h1 class="text-4xl font-bold text-gray-800">Reservation Receipt</h1>
    <p class="text-base text-blue-600 font-semibold mt-1">MegaTech</p>
  </div>

  <!-- Reservation Info -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div class="space-y-3">
      <h2 class="text-sm font-semibold text-gray-500 uppercase">Reservation Details</h2>
      <p><span class="font-semibold text-gray-800">Reservation ID:</span> <?= $order['order_id'] ?></p>
      <p><span class="font-semibold text-gray-800">Customer ID:</span> <?= $order['order_user_id'] ?></p>
      <p><span class="font-semibold text-gray-800">Date Ordered:</span> <?= $order['order_date'] ?></p>
      <p><span class="font-semibold text-gray-800">Payment Method:</span> <?= $order['order_payment_method'] ?></p>
      <p>
        <span class="font-semibold text-gray-800">Total:</span>
        <span class="text-green-600 font-bold">₱<?= number_format($order['order_total'], 2) ?></span>
      </p>
      <p><span class="font-semibold text-gray-800">Pickup Schedule:</span> <?= $formattedPickup ?></p>
    </div>
  </div>
  

  <!-- QR Code & Payment Proof -->
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start">
    <!-- QR Code -->
    <div class="text-center">
      <p class="text-sm font-medium text-gray-600 mb-2">Scan QR Code on Pickup</p>
      <div class="w-44 h-44 mx-auto bg-gray-50 border border-gray-300 rounded-xl shadow p-2">
        <img src="../qrcodes/qr_<?= $order['order_id'] ?>.png" alt="QR Code" class="w-full h-full object-contain">
      </div>
      <a href="../qrcodes/qr_<?= $order['order_id'] ?>.png" download="QR_<?= $order['order_id'] ?>.png"
         class="mt-3 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition duration-150">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
             xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"></path>
        </svg>
        Download QR
      </a>
    </div>

    <!-- Payment Proof -->
    <div class="text-center">
      <p class="text-sm font-medium text-gray-600 mb-2">Payment Proof</p>
      <div class="w-44 h-44 mx-auto bg-gray-50 border border-gray-300 rounded-xl shadow p-2">
        <img src="../proofPayment/<?= $order['order_down_payment_receipt'] ?>" alt="Proof of Payment"
             class="w-full h-full object-cover">
      </div>

      <a href="../proofPayment/<?= $order['order_down_payment_receipt'] ?>" download="../proofPayment/<?= $order['order_down_payment_receipt'] ?>"
         class="mt-3 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition duration-150">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
             xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"></path>
        </svg>
        Download Receipt
      </a>


    </div>
  </div>

  <!-- Footer -->
  <div class="mt-12 text-center border-t pt-6 text-sm text-gray-500 italic">
    Download the QR and Kindly present it on your pickup day. Thank you!
  </div>
</div>

<?php include "components/footer.php"; ?>
<script src="../function/js/filter_price_category.js"></script>
