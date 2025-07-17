<?php
include "components/header.php";
?>


<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <h1 class="text-3xl font-extrabold mb-8 text-gray-900">My Cart (<span class="cartCount">2</span>)</h1>

    <!-- Main Content -->
    <div class="flex flex-col gap-8 lg:flex-row">

        <!-- Left Section: Product List -->
        <div class="w-full lg:w-2/3 space-y-8">
            <!-- Product List -->
            <div class="p-6 bg-white rounded-xl shadow-lg space-y-6">
                <?php 
                $userId = $_SESSION['user_id'];
                $getCartlist = $db->getCartlist($userId);
                $subTotal = 0;
                ?>
                <?php include "backend/end-points/cart_list.php"; ?>
            </div>
        </div>

      <!-- Right Section: Order Summary -->
            <div class="w-full lg:w-1/3 space-y-8">
                <!-- Order Summary Box -->
                <div class="w-full bg-white rounded-lg shadow-xl p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h3>
                    <!-- Grand Total -->
                    <div class="border-t border-gray-200 mt-6 pt-4 flex justify-between text-lg font-bold text-gray-900">
                        <p>Total</p>
                        <p>Php <span id="total"><?= number_format($subTotal, 2) ?></span></p>
                    </div>

                    <!-- Checkout Button -->
                    <button class="btnCheckOut w-full bg-red-500 text-white py-3 rounded-lg font-semibold hover:bg-red-600 mt-6 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Pick-up
                    </button>

                  
                </div>

                  

            </div>


         




    </div>
</div>






<!-- Modal Structure -->
<div id="checkoutModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50" style="display:none;">
  <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full relative">
    <h2 class="text-2xl font-semibold mb-4">Confirm Checkout</h2>

    <!-- Important Message -->
    <div class="mb-4 text-sm text-gray-700 bg-yellow-100 border border-yellow-300 p-3 rounded">
      <p><strong>Note:</strong> Please choose a payment method and send <span class="font-bold text-red-500">50% downpayment</span> to reserve your order.</p>
      <p class="mt-2 text-xs text-gray-600">
        <strong>Terms & Conditions:</strong> Any payment that is incomplete or insufficient will be considered <span class="font-bold text-red-600">invalid</span>. Make sure to upload clear proof of payment.
      </p>
    </div>


        <!-- Downpayment Info -->
    <div id="downpaymentInfo" class="mb-4 text-center text-lg font-semibold text-indigo-600 hidden">
    50% Downpayment: ₱<span id="downpaymentAmount">0.00</span>
    </div>


    <!-- Payment Method Section -->
    <div class="mb-6">
      <label for="paymentMethod" class="block text-sm font-medium text-gray-700">Select Payment Method</label>
      <select id="paymentMethod" name="paymentMethod" class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="" >Select Payment Method</option>
        <option value="gcash" data-img="../assets/img/gcashQR.jpg" data-ename="GCash">GCash</option>
        <option value="maya" data-img="../assets/img/mayaQR.jpg" data-ename="Maya">Maya</option>
        <option value="bpi" data-img="../assets/img/bpiQR.jpg" data-ename="BPI">BPI</option>
      </select>
    </div>


    
    

    <!-- Payment Method Instructions -->
    <div id="paymentDetails" class="hidden">
      <!-- QR Code Image (for Gcash and Bank Transfer) -->
      <div id="qrCode" class="mb-4 hidden text-center">
        <label class="block text-sm font-medium text-gray-700 mb-2">Scan QR Code for Payment</label>
        <img src="" alt="QR Code" class="w-60 h-60 mx-auto border rounded shadow" />
      </div>

      <!-- Proof of Payment Upload Section -->
      <div id="proofOfPaymentSection" class="mt-4">
        <label for="proofOfPayment" class="block text-sm font-medium text-gray-700">Upload Proof of Payment</label>
        <input type="file" id="proofOfPayment" name="proofOfPayment" class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" accept="image/*" />
      </div>
    </div>

        <!-- Pickup Date & Time -->
    <div class="mb-6">
    <label for="pickupDate" class="block text-sm font-medium text-gray-700">Pickup Date</label>
    <input type="date" id="pickupDate" name="pickupDate" class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

    <label for="pickupTime" class="block text-sm font-medium text-gray-700 mt-4">Pickup Time</label>
    <input type="time" id="pickupTime" name="pickupTime" class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>


    <!-- Modal Footer -->
    <div class="flex justify-end mt-6">
      <button class="closeModal mr-4 px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
      <button id="btnConfirmCheckout" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Confirm</button>
    </div>

    <!-- Spinner -->
    <div class="loadingSpinner" style="display:none;">
      <div class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
        <div class="w-10 h-10 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
      </div>
    </div>
  </div>
</div>






<?php include "components/footer.php"; ?>
