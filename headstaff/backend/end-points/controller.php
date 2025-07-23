<?php
include('../class.php');

$db = new global_class();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['requestType'])) {
        if ($_POST['requestType'] == 'Login') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $db->Login($username, $password);

            if ($user) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'data' => $user
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid username or password'
                ]);
            }
    }else if ($_POST['requestType'] == 'paidOrder') {
        $orderStatus="paid";
        $orderId = $_POST['order_id'];
         $insufficientStockProducts = $db->validateStockSufficiency($orderId);

            if ($insufficientStockProducts === true) {
                $order = $db->updateOrderStatus($orderId, $orderStatus);

                if ($order) {
                    $stockout = $db->stockout($orderId);

                    if ($stockout === true) {
                        echo json_encode([
                            'status' => 200,
                            'message' => 'Stock updated successfully (stock out).'
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 500,
                            'message' => 'Failed to update stock in the database: ' . $stockout
                        ]);
                    }

                } else {
                    echo 'Failed to update order status in the database.';
                }
            } else {
                echo 'Insufficient stock for the following products: ' . implode(", ", $insufficientStockProducts);
            }


      
        

    } else if ($_POST['requestType'] == 'declineOrder') {

        $orderStatus="decline";

        $orderId = $_POST['order_id'];
        // Cancel the order
            $order = $db->updateOrderStatus($orderId, $orderStatus);

            if ($order) {
                echo 200; 
            } else {
                echo 'Failed to update order in the database.';
            }
                

    }else if ($_POST['requestType']=="UpdateHeadPassword") {
            $hs_id = $_POST['hs_id'];
            $user_NewPassword = $_POST['user_NewPassword'];
            $user_CurrentPassword = $_POST['user_CurrentPassword'];
            $response = $db->UpdateHeadPassword($hs_id, $user_NewPassword, $user_CurrentPassword);
            echo json_encode($response);

    }else {
        echo json_encode([
            'status' => 400,
            'message' => 'Invalid request type.'
        ]);
    }

    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Access Denied! No Request Type.'
        ]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        'status' => 'error',
        'message' => 'GET requests are not supported for login.'
    ]);
}
?>