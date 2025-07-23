<?php
include('../class.php');
$db = new global_class();

$result = $db->get_inventory_report();

$data = [];

if ($result && $result->num_rows > 0) {
    $i = 1;
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $i++,
            'date' => date('Y-m-d', strtotime($row['date'])),
            'supplier_name' => $row['supplier_name'],
            'supplier_price' => number_format($row['supplier_price'], 2),
            'product_name' => $row['product_name'],
            'stock_qty' => $row['stock_qty'],
            'changes' => $row['changes']
        ];
    }
}

echo json_encode($data);
