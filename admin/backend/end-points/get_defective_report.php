<?php
include('../class.php');
$db = new global_class();

$result = $db->get_defective_report();

$data = [];

if ($result && $result->num_rows > 0) {
    $i = 1;
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $i++,
            'date' => date('Y-m-d', strtotime($row['return_date'])),
            'order_code' => $row['order_code'],
            'prod_name' => $row['prod_name'],
            'return_qty' => $row['return_qty'],
            'return_reason' => $row['return_reason']
        ];
    }
}

echo json_encode($data);
