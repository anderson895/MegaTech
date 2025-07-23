<?php
include('../class.php');
$db = new global_class();

$result = $db->get_sales_report();
$data = [];

if ($result && $result->num_rows > 0) {
    $i = 1;
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $i++,
            'order_code' => $row['order_code'],
            'order_total' => number_format($row['order_total'], 2),
            'order_date' => date('Y-m-d', strtotime($row['order_date']))
        ];
    }
}

echo json_encode($data);
