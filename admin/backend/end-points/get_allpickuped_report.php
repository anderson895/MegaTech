<?php
include('../class.php');
$db = new global_class();

$result = $db->get_allpickuped_report();

$data = [];

if ($result && $result->num_rows > 0) {
    $i = 1;
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $i++,
            'date' => date('Y-m-d', strtotime($row['order_pickup_date'])),
            'order_code' => $row['order_code'],
            'order_total' => $row['order_total'],
            'Fullname' => $row['Fullname']
        ];
    }
}

echo json_encode($data);
