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
                $shippingFee = 0;
                $subTotal = 0;
                ?>
                <?php include "backend/end-points/cart_list.php"; ?>
            </div>
        </div>

        <!-- Right Section: Order Summary -->
        <div class="w-full lg:w-1/3 space-y-8">
            <!-- Order Summary -->
            <div class="w-full bg-white rounded-lg shadow-xl p-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h3>
                <div class="flex justify-between text-sm text-gray-700 mb-2">
                    <p>Sub-total (<span id="total-items"><?= count($getCartlist) ?></span> items)</p>
                    <p>Php <span id="sub-total"><?= number_format($subTotal, 2) ?></span></p>
                </div>

                <div class="border-t border-gray-200 mt-6 pt-4">
                    <div class="flex justify-between text-sm text-gray-700">
                        <p>Vat (12%)</p>
                        <p>Php <span id="vat"><?= number_format($subTotal * 0.12, 2) ?></span></p>
                    </div>

                    <!-- Shipping Fee -->
                    <div class="flex justify-between text-sm text-gray-700 mt-4">
                        <p>Shipping Fee</p>
                        <p>Php <span id="shipping-fee"><?= number_format($shippingFee, 2) ?></span></p>
                    </div>
                </div>

                <!-- Grand Total -->
                <div class="grandTotal border-t border-gray-200 mt-6 pt-4 flex justify-between text-lg font-bold text-gray-900">
                    <p>Total</p>
                    <p>Php <span id="total"><?= number_format($subTotal + ($subTotal * 0.12) + $shippingFee , 2) ?></span></p>
                </div>

                <button class="btnCheckOut w-full bg-red-500 text-white py-3 rounded-lg font-semibold hover:bg-red-600 mt-6 focus:outline-none focus:ring-2 focus:ring-red-500">Checkout</button>
            </div>
        </div>

    </div>
</div>









<?php include "components/footer.php"; ?>

<script src="js/cart.js"></script>
<script src="js/setAddress.js"></script>