<?php 
$userId = $_SESSION['user_id'];
$getCartlist = $db->getCartlist($userId); 
$totalItems = count($getCartlist);
$subTotal = 0;
$totalSavings = 0;
?>

<div class="container mx-auto px-4 py-8 max-w-5xl">
  <?php if ($totalItems == 0): ?>
    <p class="text-center text-gray-500 text-lg">Your cart is empty. Add some items to get started!</p>
  <?php else: ?>

    <!-- Select All Header -->
    <div class="flex items-center mb-6">
      <input type="checkbox" id="check-all" class="mr-2">
      <label for="check-all" class="text-gray-800 font-medium text-base">Select All</label>
    </div>

    <!-- Product List -->
    <div class="space-y-6">
    <?php foreach ($getCartlist as $cart): 
      $subTotal += $cart['prod_price'] * $cart['cart_Qty'];
      $originalPrice = $cart['prod_price'];
      $price = $cart['prod_price'] * $cart['cart_Qty'];
    ?>
    
    <!-- Single Product -->
    <div class="relative border-t border-gray-200 pt-4 pb-4">
      

       <!-- Checkbox  (Top-Left)-->
      <input type="checkbox" class="absolute top-2 left-2 product-checkbox"
        data-product-id="<?=$cart['cart_prod_id']?>" 
        data-Originalprice="<?=$originalPrice?>" 
        data-price="<?=$price?>" 
        data-qty="<?=$cart['cart_Qty']?>" />

      <!-- Remove Button  (Top-Right) -->
      <button class="absolute top-2 right-2 text-red-500 hover:text-red-700 TogglerRemoveItem" 
        data-cart_id="<?=$cart['cart_prod_id']?>">
        <span class="material-icons text-base">close</span>
      </button>

   

      <div class="flex flex-col md:flex-row md:items-center gap-4 mt-6">

        <!-- Product Image -->
        <div class="w-24 h-24 flex-shrink-0">
          <img src="../upload/<?=$cart['prod_image']?>" alt="Product Image"
            class="w-full h-full object-cover rounded-md shadow-sm">
        </div>

        <!-- Product Info -->
        <div class="flex-1 space-y-1">
          <h4 class="text-gray-900 font-semibold text-base"><?=$cart['prod_name']?></h4>
          <p class="text-sm text-gray-600"><?= substr($cart['prod_description'], 0, 50) ?>...</p>
          <p class="text-sm text-gray-500">Price: Php <?=number_format($cart['prod_price'], 2)?></p>
          <p class="text-sm text-gray-500">Stocks: <?=$cart['prod_stocks']?></p>

          <!-- Quantity Controls -->
          <div class="flex items-center gap-2 pt-2">
            <button class="togglerMinus w-8 h-8 bg-gray-200 rounded hover:bg-gray-300 text-gray-700"
              data-user_id="<?=$cart['cart_user_id']?>"
              data-product_id="<?=$cart['cart_prod_id']?>">
              <span class="material-icons text-sm">remove</span>
            </button>

            <input readonly type="number" value="<?=$cart['cart_Qty']?>" 
              class="w-14 h-8 text-center border rounded text-sm border-gray-300" />

            <button class="togglerAdd w-8 h-8 bg-gray-200 rounded hover:bg-gray-300 text-gray-700"
              data-user_id="<?=$cart['cart_user_id']?>"
              data-product_id="<?=$cart['cart_prod_id']?>">
              <span class="material-icons text-sm">add</span>
            </button>
          </div>
        </div>

        <!-- Price -->
        <div class="md:text-right text-left">
          <p class="text-red-600 font-semibold mt-2 md:mt-0">
            Php <?=number_format($price, 2)?>
          </p>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
