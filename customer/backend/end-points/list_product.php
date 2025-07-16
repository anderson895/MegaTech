<h1 class="text-2xl font-semibold mb-6">All Products</h1>

<!-- Search Input -->
<input type="text" id="search" class="w-full p-2 mb-4 border rounded" placeholder="Search Products...">

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="product-grid">
    <?php 
    $fetch_all_product = $db->fetch_all_product();  // Fetch all products

    foreach ($fetch_all_product as $product):
        
            $discounted_price=$product['prod_price'];
    ?>
    
    <!-- Product Card -->
    <div class="bg-white p-4 rounded shadow-lg transition-transform transform hover:scale-105 hover:shadow-2xl product-card" data-category-id="<?=$product['prod_category_id']?>" data-price="<?=$product['prod_price']?>">
        <a href="login.php">

            <!-- Product Image -->
            <img src="../upload/<?=$product['prod_image']?>" alt="Product Image" class="w-full rounded mb-4 transition-transform hover:scale-105">

            <!-- Product Name with class for searching -->
            <h2 class="font-semibold text-lg transition-colors hover:text-blue-500 product-name"><?=$product['prod_name']?></h2>

            <!-- Product Description -->
            <p class="text-gray-600 transition-colors hover:text-gray-800"><?= substr($product['prod_description'], 0, 20) . (strlen($product['prod_description']) > 20 ? '...' : '') ?></p>

          
            <p class="text-lg font-bold text-red-600">PHP <?=number_format($product['prod_price'], 2);?></p>
           
        </a>
    </div>

    <?php endforeach; ?>
</div>


<script>
    $(document).ready(function() {
    $('#search').on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase(); 

        $('#product-grid .product-card').each(function() {
            var productName = $(this).find('.product-name').text().toLowerCase();

            if (productName.indexOf(searchTerm) !== -1) {
                $(this).show(); 
            } else {
                $(this).hide();
            }
        });
    });
});

</script>