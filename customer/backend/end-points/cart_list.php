<?php 
$userId = $_SESSION['user_id'];
$getCartlist = $db->getCartlist($userId); 
$totalItems = count($getCartlist);
$subTotal = 0;
$totalSavings = 0;
?>

<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col gap-6">
        <?php if ($totalItems == 0): ?>
            <!-- Empty Cart Message -->
            <div class="bg-white p-6 rounded-lg shadow text-center text-gray-600 text-lg">
                Your cart is empty. Add some items to get started!
            </div>
        <?php else: ?>
            <?php foreach ($getCartlist as $cart): 
                $subTotal += $cart['prod_price'] * $cart['cart_Qty'];
                $originalPrice = $cart['prod_price'];
                $price = $cart['prod_price'] * $cart['cart_Qty'];
            ?>
                <!-- Product Item -->
                <div class="flex flex-col lg:flex-row items-start lg:items-center border rounded-lg shadow-sm p-4 bg-white relative gap-4">
                    
                  
                    
                    <div class="absolute top-2 right-2">
                        <button class="text-red-600 hover:bg-gray-200 rounded-full p-1 TogglerRemoveItem"
                            data-cart_id="<?=$cart['cart_prod_id']?>">
                            <span class="material-icons">close</span>
                        </button>
                    </div>

                    <!-- Product Image -->
                    <div class="w-full lg:w-24">
                        <img src="../upload/<?=$cart['prod_image']?>" alt="Product Image" class="w-20 h-20 object-cover rounded-md shadow">
                    </div>

                    <!-- Product Info -->
                    <div class="flex-grow space-y-1">
                        <h4 class="text-base font-semibold text-gray-800"><?=$cart['prod_name']?></h4>
                        <p class="text-sm text-gray-500"><?= substr($cart['prod_description'], 0, 50) ?></p>
                        <p class="text-sm text-gray-500">Original price: ₱<?= number_format($cart['prod_price'], 2) ?></p>
                        <p class="text-sm text-gray-500">Stocks: <?=$cart['prod_stocks']?></p>

                        <!-- Quantity Controls -->
                        <div class="flex items-center gap-2 mt-2">
                            <button class="togglerMinus w-8 h-8 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded"
                                    data-user_id='<?=$cart['cart_user_id']?>'
                                    data-product_id='<?=$cart['cart_prod_id']?>'>
                                <span class="material-icons text-sm">remove</span>
                            </button>
                            <input readonly type="number" value="<?=$cart['cart_Qty']?>" 
                                   class="w-16 h-8 text-center border border-gray-300 rounded text-sm" min="1" />
                            <button class="togglerAdd w-8 h-8 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded"
                                    data-user_id='<?=$cart['cart_user_id']?>'
                                    data-product_id='<?=$cart['cart_prod_id']?>'>
                                <span class="material-icons text-sm">add</span>
                            </button>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="text-right mt-2 lg:mt-0">
                        <p class="text-red-600 font-semibold text-lg itemPrice">₱<?= number_format($price, 2) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
