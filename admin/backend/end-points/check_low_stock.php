<?php
include('../class.php');

$db = new global_class();

$lowStockProducts = [];
$query = $db->fetch_all_product();

foreach ($query as $product) {
    if ($product['prod_stocks'] <= $product['prod_critical']) {
        $lowStockProducts[] = [
            'prod_id' => $product['prod_id'],
            'prod_name' => $product['prod_name'],
            'prod_stocks' => $product['prod_stocks'],
            'prod_critical' => $product['prod_critical']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($lowStockProducts);
