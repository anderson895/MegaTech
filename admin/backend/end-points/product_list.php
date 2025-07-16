<?php 
$fetch_all_product = $db->fetch_all_product();

if (!empty($fetch_all_product)) {
    foreach ($fetch_all_product as $product):
        $status = ($product['prod_status'] > 0) ? "Active" : "Not Active";
        $image_path = !empty($product['prod_image']) ? "../upload/{$product['prod_image']}" : 'images/default.png';

        // Decode specs if JSON format
        $specs_html = '';
        if (!empty($product['prod_specs'])) {
            $decoded_specs = json_decode($product['prod_specs'], true);
            if (is_array($decoded_specs)) {
                foreach ($decoded_specs as $spec) {
                    $spec_name = htmlspecialchars($spec['Specs'] ?? '');
                    $spec_value = htmlspecialchars($spec['value'] ?? '');
                    $specs_html .= "<div><span class='font-medium'>$spec_name:</span> <span class=''>$spec_value</span></div>";
                }
            } else {
                $specs_html = '<span class="text-gray-500 italic">Invalid specs</span>';
            }
        } else {
            $specs_html = '<span class="text-gray-400 italic">No specs</span>';
        }
?>
<tr class="border-b">
    <td class="p-3"><?php echo $product['prod_code']; ?></td>
    <td class="p-3">
        <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($product['prod_name']); ?>" class="w-16 h-16 object-cover rounded">
    </td>
    <td class="p-3 font-semibold"><?php echo $product['prod_name']; ?></td>
    <td class="p-3 text-sm "><?php echo strlen($product['prod_description']) > 50 ? substr($product['prod_description'], 0, 50) . '...' : $product['prod_description']; ?></td>
    
    <td class="p-3 text-sm">
        <div class="space-y-1">
            <?php echo $specs_html; ?>
        </div>
    </td>

    <td class="p-3 "><?php echo $product['prod_stocks']; ?></td>
    <td class="p-3 "><?php echo $product['category_name']; ?></td>
    <td class="p-3 ">₱<?php echo number_format($product['prod_price'], 2); ?></td>
    <td class="p-3 ">
        <span class="<?php echo $product['prod_status'] > 0 ? 'text-green-600 font-semibold' : 'text-red-500 italic'; ?>">
            <?php echo $status; ?>
        </span>
    </td>
    <td class="p-3 text-center">
    <div class="flex flex-col sm:flex-row justify-center items-center space-y-1 sm:space-y-0 sm:space-x-2">
        <button class="bg-blue-500 text-white py-1 px-3 rounded-md text-sm updateProductToggler"
            data-prod_id="<?=$product['prod_id']?>"
            data-prod_code="<?=$product['prod_code']?>"
            data-prod_name="<?=$product['prod_name']?>"
            data-prod_price="<?=$product['prod_price']?>"
            data-prod_category_id="<?=$product['prod_category_id']?>"
            data-prod_critical="<?=$product['prod_critical']?>"
            data-prod_description="<?=$product['prod_description']?>"
        >
            Update
        </button>
        <button class="bg-green-500 text-white py-1 px-3 rounded-md text-sm stockInToggler"
            data-prod_stocks="<?=$product['prod_stocks']?>"
            data-prod_id="<?=$product['prod_id']?>"
            data-prod_name="<?=$product['prod_name']?>"
        >
            StockIn
        </button>
        <button class="bg-red-500 text-white py-1 px-3 rounded-md text-sm removeProduct"
            data-prod_id="<?=$product['prod_id']?>"
        >
            Remove
        </button>
    </div>
</td>

</tr>
<?php 
    endforeach; 
} else { ?>
<tr>
    <td colspan="10" class="p-4 text-center text-gray-500 italic">No records found.</td>
</tr>
<?php } ?>
