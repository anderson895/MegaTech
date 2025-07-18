<?php
include ('db.php');
date_default_timezone_set('Asia/Manila');
$getDateToday = date('Y-m-d H:i:s'); 


class global_class extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }



   



    public function Login($username, $password)
    {
        $hashed_password = hash('sha256', $password);


        $query = $this->conn->prepare("SELECT * FROM `headstaff` WHERE `hs_username` = ? AND `hs_password` = ? AND hs_status='1'");
        $query->bind_param("ss", $username, $hashed_password);

        if ($query->execute()) {
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                session_start();
                $_SESSION['hs_username'] = $user['hs_username'];
                $_SESSION['hs_id'] = $user['hs_id'];

                return $user;
            } else {
                return false; 
            }
        } else {
            return false;
        }
    }





     public function check_account($admin_id) {
        $admin_id = intval($admin_id);
    
        $query = "SELECT * FROM admin WHERE admin_id = $admin_id";
    
        $result = $this->conn->query($query);
    
        $items = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
        }
        return $items;
    }


   public function fetch_all_reservation(){
        $query = $this->conn->prepare("SELECT * FROM `orders`
        LEFT JOIN user
        ON user.user_id = orders.order_user_id
        ");

        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }




    public function fetch_order($order_id){
        $query = $this->conn->prepare("SELECT * FROM orders 
        LEFT JOIN user
        ON user.user_id = orders.order_user_id
        WHERE orders.order_id = '$order_id'");
        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }


     
    public function fetch_order_item($order_id){
        $query = $this->conn->prepare("SELECT * FROM orders
        LEFT JOIN orders_item
        ON orders.order_id = orders_item.item_order_id
        LEFT JOIN product
        ON product.prod_id = orders_item.item_product_id
        LEFT JOIN category
        ON category.category_id = product.prod_category_id
        where  orders_item.item_order_id ='$order_id'
        ");
        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }
    



     public function fetch_all_customers(){
        $query = $this->conn->prepare("SELECT * FROM `user`");

        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }



      public function getUserDetails($user_id) {
        $query = $this->conn->prepare("SELECT * FROM `user` WHERE user_id = ?");
        $query->bind_param("i", $user_id);

        if ($query->execute()) {
            $result = $query->get_result();
            return $result->fetch_assoc(); 
        } else {
            return null; 
        }
    }





  public function updateOrderStatus($orderId,$newStatus) {
        $stmt = $this->conn->prepare("UPDATE `orders` SET `order_status` = '$newStatus' WHERE `orders`.`order_id` = '$orderId'");
        return $stmt->execute();
    }




    public function validateStockSufficiency($orderId) {
        $insufficientStockProducts = [];
    
        $orderItemsQuery = "SELECT item_product_id, item_qty FROM orders_item WHERE item_order_id = '$orderId'";
        $orderItemsResult = mysqli_query($this->conn, $orderItemsQuery);
    
        if (!$orderItemsResult || mysqli_num_rows($orderItemsResult) === 0) {
            return false; // No items found
        }
    
        while ($item = mysqli_fetch_assoc($orderItemsResult)) {
            $productId = $item['item_product_id'];
            $itemQty = $item['item_qty'];
    
            $checkStockQuery = "SELECT prod_name, prod_stocks FROM product WHERE prod_id = $productId";
            $stockResult = mysqli_query($this->conn, $checkStockQuery);
            $stock = mysqli_fetch_assoc($stockResult);
    
            if (!$stock || $stock['prod_stocks'] < $itemQty) {
                $insufficientStockProducts[] = $stock['prod_name'] . " (ID: $productId)";
            }
        }
        if (!empty($insufficientStockProducts)) {
            return $insufficientStockProducts;
        }
        return true;
    }



public function stockout($orderId) {
    // Get all order items for the given order ID
    $orderItemsQuery = "
        SELECT p.prod_name, oi.item_product_id, oi.item_qty, p.prod_stocks 
        FROM orders_item as oi
        LEFT JOIN product as p ON p.prod_id = oi.item_product_id
        WHERE oi.item_order_id = ?
    ";

    $stmt = $this->conn->prepare($orderItemsQuery);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        return 'Error fetching order items: ' . $stmt->error;
    }

    session_start();
    $hs_id = $_SESSION['hs_id'];

    while ($item = $result->fetch_assoc()) {
        $productId = $item['item_product_id'];
        $itemQty = (int)$item['item_qty'];
        $current_qty = (int)$item['prod_stocks'];
        $prod_name = $item['prod_name'];

        // Check if enough stock
        if ($current_qty >= $itemQty) {
            $new_qty = $current_qty - $itemQty;

            // Update stock
            $updateStockQuery = "
                UPDATE product
                SET prod_stocks = ?
                WHERE prod_id = ?
            ";
            $updateStmt = $this->conn->prepare($updateStockQuery);
            $updateStmt->bind_param("ii", $new_qty, $productId);
            $updateStmt->execute();
            $updateStmt->close();

            // Log stockout
            $change_log = "$current_qty -> $new_qty";
            $logStmt = $this->conn->prepare("
                INSERT INTO stock_history 
                (stock_prod_id, stock_type,user_type, stock_Qty, stock_changes, stock_account_id) 
                VALUES (?, 'Stock Out','HeadStaff', ?, ?, ?)
            ");
            $logStmt->bind_param("iisi", $productId, $itemQty, $change_log, $hs_id);
            $logStmt->execute();
            $logStmt->close();

        } else {
            return "Not enough stock for product '$prod_name'. Required: $itemQty, Available: $current_qty";
        }
    }

    return true;
}



    

}
?>