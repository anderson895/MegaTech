<?php 
$fetch_all_product = $db->fetch_all_product();

if ($fetch_all_product->num_rows > 0) {
    foreach ($fetch_all_product as $product):
        $status = ($product['prod_status'] > 0) ? "Active" : "Not Active";
        $image_path = !empty($product['prod_image']) ? "../upload/{$product['prod_image']}" : 'images/default.png';

        // Decode specs if JSON format
        $specs_html = '';
        $decoded_specs = [];

        if (!empty($product['prod_specs'])) {
            $decoded_specs = json_decode($product['prod_specs'], true);

            if (is_array($decoded_specs)) {
                foreach ($decoded_specs as $spec) {
                    $spec_name = htmlspecialchars($spec['Specs'] ?? '');
                    $spec_value = htmlspecialchars($spec['value'] ?? '');
                    $specs_html .= "<div><span class='font-medium'>$spec_name:</span> <span>$spec_value</span></div>";
                }
            } else {
                $specs_html = '<span class="text-gray-500 italic">Invalid specs</span>';
            }
        } else {
            $specs_html = '<span class="text-gray-400 italic">No specs</span>';
        }

        // Encode specs for data attribute
        $specs_json = htmlspecialchars(json_encode($decoded_specs), ENT_QUOTES, 'UTF-8');
?>

<tr class="border-b">
    <td class="p-3"><?= $product['prod_code'] ?></td>
    <td class="p-3">
        <img src="<?= $image_path ?>" alt="<?= $product['prod_name'] ?>" class="w-16 h-16 object-cover rounded">
    </td>
    <td class="p-3 font-semibold"><?= $product['prod_name']?></td>
   

    <td class="p-3"><?= $product['prod_stocks'] ?></td>
    <td class="p-3"><?= $product['prod_critical'] ?></td>
    <td class="p-3">₱<?= number_format($product['prod_price'], 2) ?></td>
    <td class="p-3">
        <span class="<?= $product['prod_status'] > 0 ? 'text-green-600 font-semibold' : 'text-red-500 italic' ?>">
            <?= $status ?>
        </span>
    </td>

    <td class="p-3 text-center">
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-1 sm:space-y-0 sm:space-x-2">
            

            <!-- Stock In Button -->
            <button class="bg-green-500 text-white py-1 px-3 rounded-md text-sm stockInToggler"
                data-prod_stocks="<?= $product['prod_stocks'] ?>"
                data-prod_id="<?= $product['prod_id'] ?>"
                data-prod_name="<?= $product['prod_name'] ?>"
            >
                StockIn
            </button>

            <!-- Remove Button -->
            <button class="bg-red-500 text-white py-1 px-3 rounded-md text-sm togglerRemoveProduct"
                data-prod_id="<?= $product['prod_id'] ?>"
                data-prod_name="<?= $product['prod_name'] ?>"
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
    <td colspan="10" class="p-4 text-center text-gray-500 italic">No Product Found.</td>
</tr>
<?php } ?>
