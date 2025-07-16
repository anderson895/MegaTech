<?php
include "components/header.php";



$userID=$_SESSION['user_id'];
$product_id=$_GET['product_id'];

$product_info = $db->fetch_product_info($product_id); 
foreach ($product_info as $product):
    $prod_price = $product['prod_price'] ; 
endforeach;
?>

<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumbs -->
    <div class="text-gray-500 text-sm mb-4">
        <a href="#" class="hover:underline">Home</a> &gt;
        <a href="#" class="hover:underline"><?=$product['prod_name']?></a>
    </div>

   <div class="max-w-5xl mx-auto p-6 md:p-10 bg-white rounded-xl border border-gray-200">
    <div class="md:flex md:space-x-10 space-y-6 md:space-y-0 h-full">

        <!-- Image Section -->
        <div class="md:w-1/2">
            <div class="h-full w-full aspect-[4/3] md:aspect-auto">
                <img src="../upload/<?= $product['prod_image'] ?>" alt="Product Image"
                     class="w-full h-full object-cover rounded-lg">
            </div>
        </div>

        <!-- Product Details -->
        <div class="md:w-1/2 flex flex-col justify-between">

            <!-- Product Name and Price -->
            <div class="space-y-1">
                <h1 class="text-2xl font-medium text-gray-800"><?= htmlspecialchars($product['prod_name']) ?></h1>
                <p class="text-xl text-gray-900">₱<?= number_format($product['prod_price'], 2); ?></p>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <h2 class="text-sm font-semibold text-gray-600 mb-1">Description</h2>
                <p class="text-sm text-gray-700 leading-relaxed"><?= htmlspecialchars($product['prod_description']) ?></p>
            </div>

            <!-- Specifications -->
            <div class="mt-6">
                <h2 class="text-sm font-semibold text-gray-600 mb-3">Specifications</h2>
                <div class="divide-y divide-gray-200 border border-gray-200 rounded-lg overflow-hidden">
                    <?php
                    $specs = json_decode($product['prod_specs'], true);
                    if ($specs):
                        foreach ($specs as $spec): ?>
                            <div class="flex justify-between items-center px-4 py-3 bg-white hover:bg-gray-50 transition">
                                <span class="text-sm text-gray-600"><?= htmlspecialchars($spec['Specs']) ?></span>
                                <span class="text-sm text-gray-900 font-medium"><?= htmlspecialchars($spec['value']) ?></span>
                            </div>
                        <?php endforeach;
                    endif;
                    ?>
                </div>
            </div>

            <!-- Button -->
            <div class="mt-8">
                <button 
                    class="w-full bg-gray-900 text-white text-sm py-3 rounded-md hover:bg-gray-800 transition btnAddToCart"
                    data-product_id="<?= $product_id ?>"
                    data-user_id="<?= $userID ?>"
                >
                    Add to Cart
                </button>
            </div>

        </div>
    </div>
</div>


</div>










<?php include "components/footer.php"; ?>