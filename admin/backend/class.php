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



    public function UpdateAdminPassword($admin_id, $newPassword, $currentPassword) {
        $query = "SELECT `admin_password` FROM `admin` WHERE `admin_id` = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return ['success' => false, 'message' => 'SQL error.'];

        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $stmt->bind_result($storedPassword);
        if (!$stmt->fetch()) {
            $stmt->close();
            return ['success' => false, 'message' => 'admin not found.'];
        }
        $stmt->close();

        if (hash('sha256', $currentPassword) !== $storedPassword) {
            return ['success' => false, 'message' => 'Incorrect current password.'];
        }

        $update = $this->conn->prepare("UPDATE `admin` SET `admin_password` = ? WHERE `admin_id` = ?");
        if (!$update) return ['success' => false, 'message' => 'SQL error.'];

        $hashed = hash('sha256', $newPassword);
        $update->bind_param("si", $hashed, $admin_id);
        $success = $update->execute();
        $update->close();

        return [
            'success' => $success,
            'message' => $success ? 'Password updated successfully.' : 'Failed to update password.'
        ];
    }









    public function fetch_admin_info($id){
        $query = $this->conn->prepare("SELECT * FROM `admin` where admin_id = '$id'");
        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }






    public function Login($username, $password)
    {
        $hashed_password = hash('sha256', $password);


        $query = $this->conn->prepare("SELECT * FROM `admin` WHERE `admin_username` = ? AND `admin_password` = ? AND admin_status='1'");
        $query->bind_param("ss", $username, $hashed_password);

        if ($query->execute()) {
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                session_start();
                $_SESSION['admin_username'] = $user['admin_username'];
                $_SESSION['admin_id'] = $user['admin_id'];

                return $user;
            } else {
                return false; 
            }
        } else {
            return false;
        }
    }

public function fetch_all_product(){
        $query = $this->conn->prepare("SELECT * 
        FROM `product` 
        LEFT JOIN category
        ON product.prod_category_id = category.category_id
        where prod_status='1'
        ");

        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }
public function addProduct($productData)
{
    $product_Code = $productData['code'];
    $product_Name = $productData['name'];
    $product_Price = $productData['price'];
    $critical_Level = $productData['critical_level'];
    $product_Category = $productData['category'];
    $product_Description = $productData['description'];
    $product_Image = $productData['image'];
    $product_Stocks = $productData['stocks'];
    $specs = $productData['specs']; // Array of specs

    $getDateToday = date('Y-m-d H:i:s');
    
    // Convert specs array to JSON string
    $specsJson = json_encode($specs);

    // SQL query
    $query = "INSERT INTO `product` 
        (`prod_code`, `prod_name`, `prod_price`, `prod_category_id`, `prod_critical`, `prod_description`, `prod_image`, `prod_added`, `prod_status`, `prod_stocks`, `prod_specs`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?)";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $this->conn->error);
        return false;
    }

    // Bind parameters
    $stmt->bind_param(
        "ssdissssss", 
        $product_Code,
        $product_Name,
        $product_Price,
        $product_Category,
        $critical_Level,
        $product_Description,
        $product_Image,
        $getDateToday,
        $product_Stocks,
        $specsJson
    );

    if ($stmt->execute()) {
        return $this->conn->insert_id;
    } else {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }
}



    public function get_allpickuped_report(){
        $query = $this->conn->prepare("SELECT * FROM `orders`
        LEFT JOIN user
        ON orders.order_user_id  = user.user_id 
         WHERE order_status = 'pickedup'");
        if ($query->execute()) {
            return $query->get_result();
        }
        return null;
    }





    
    public function fetch_all_category(){
        $query = $this->conn->prepare("SELECT * FROM `category`");

        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }

    public function get_defective_report(){
        $query = $this->conn->prepare("SELECT * FROM `return_order`
        LEFT JOIN orders_item
        ON return_order.return_item_id  = orders_item.item_id 
        LEFT JOIN orders
        ON orders.order_id  = orders_item.item_order_id  
        LEFT JOIN product
        ON orders_item.item_product_id  = product.prod_id   
        ");

        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }



    public function get_sales_report(){
        $query = $this->conn->prepare("SELECT * FROM `orders` WHERE order_status = 'pickedup'");
        if ($query->execute()) {
            return $query->get_result();
        }
        return null;
    }


    public function get_inventory_report(){
        $query = $this->conn->prepare("
            SELECT 
                sh.stock_date AS date,
                sl.sl_supplier_name AS supplier_name,
                sl.sl_supplier_price AS supplier_price,
                p.prod_name AS product_name,
                sh.stock_Qty AS stock_qty,
                sh.stock_changes AS changes
            FROM 
                supply_logs sl
            INNER JOIN stock_history sh ON sl.sl_stock_id = sh.stock_id
            INNER JOIN product p ON sh.stock_prod_id = p.prod_id
            WHERE 
                sh.stock_type = 'Stock In'
            ORDER BY sh.stock_date DESC
        ");

        if ($query->execute()) {
            return $query->get_result();
        }
        return null;
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











    public function updateProduct(
                    $product_ID,
                    $product_Code,
                    $product_Name,
                    $product_Price,
                    $critical_Level,
                    $product_Category,
                    $product_Description,
                    $newFileName,
                    $specs
                ) {

        $specsJson = json_encode($specs);
        $getDateToday = date('Y-m-d H:i:s');

        $sql = "UPDATE `product` SET 
                    `prod_code` = ?, 
                    `prod_name` = ?, 
                    `prod_price` = ?, 
                    `prod_category_id` = ?, 
                    `prod_critical` = ?, 
                    `prod_description` = ?, 
                    `prod_specs` = ?, 
                    `prod_added` = ?";
        $params = [$product_Code, $product_Name, $product_Price, $product_Category, $critical_Level, $product_Description, $specsJson, $getDateToday];
        $paramTypes = "ssssssss";
        
        if (!empty($product_Image)) {
            $sql .= ", `prod_image` = ?";
            $params[] = $product_Image;
            $paramTypes .= "s";
        }
        $sql .= " WHERE `prod_id` = ?";
        $params[] = $product_ID;
        $paramTypes .= "i";
        $query = $this->conn->prepare($sql);
        $query->bind_param($paramTypes, ...$params);
        
        if ($query->execute()) {
            return "success";
        } else {
            return false; 
        }

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





 public function fetch_all_return(){
        $query = $this->conn->prepare("SELECT * FROM `return_order`
        LEFT JOIN orders_item
        ON orders_item.item_id = return_order.return_item_id
        LEFT JOIN orders
        ON orders.order_id  = orders_item.item_order_id 
        LEFT JOIN user
        ON orders.order_user_id  = user.user_id  
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
    


    public function updateOrderStatus($orderId,$scheduleDate,$scheduleTime,$newStatus) {
        $stmt = $this->conn->prepare("UPDATE `orders` SET `order_status` = '$newStatus',`order_pickup_date` = '$scheduleDate',`order_pickup_time` = '$scheduleTime' WHERE `orders`.`order_id` = '$orderId'");
        return $stmt->execute();
    }



    public function pickedupOrder($orderId, $orderStatus, $uniqueFileName) {
        $stmt = $this->conn->prepare("UPDATE `orders` 
            SET `order_status` = ?, `order_proof_recieved` = ? 
            WHERE `order_id` = ?");
        
        if ($stmt->execute([$orderStatus, $uniqueFileName, $orderId])) {
            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'message' => $stmt->errorInfo()[2]];
        }
    }



    public function approveReturn($return_id, $return_status) {
        $stmt = $this->conn->prepare("UPDATE `return_order` SET `return_status` = '$return_status' WHERE `return_order`.`return_id` = '$return_id'");
        return $stmt->execute();
    }

    public function cancelReturn($return_id, $return_status) {
        $stmt = $this->conn->prepare("UPDATE `return_order` SET `return_status` = '$return_status' WHERE `return_order`.`return_id` = '$return_id'");
        return $stmt->execute();
    }



    

    public function getProductImageById($product_ID) {
        $sql = "SELECT prod_image FROM product WHERE prod_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $product_ID);  
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['prod_image'] : null;
    }
    


    public function updateStock(
        $stock_prod_id,
        $stock_admin_id,
        $stockin_qty,
        $prod_name
    ) {
        // 1. Get current quantity
        $query = $this->conn->prepare("SELECT prod_stocks FROM product WHERE prod_id = ?");
        $query->bind_param("i", $stock_prod_id);
        $query->execute();
        $query->bind_result($current_qty);
        $query->fetch();
        $query->close();

        // 2. Update stock quantity (Stock In)
        $query = $this->conn->prepare("UPDATE product SET prod_stocks = prod_stocks + ? WHERE prod_id = ?");
        $query->bind_param("ii", $stockin_qty, $stock_prod_id);
        $stockUpdated = $query->execute();
        $query->close();

        // 3. Prepare stock history log
        $new_qty = $current_qty + $stockin_qty;
        $change_log = "$current_qty -> $new_qty";
        $stock_type = "Stock In";
        $user_type = "Administrator";

        // 4. Insert into stock_history
        $insertLog = $this->conn->prepare("
            INSERT INTO stock_history 
            (stock_prod_id, stock_type, stock_Qty, user_type, stock_changes, stock_account_id) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $insertLog->bind_param("isissi", $stock_prod_id, $stock_type, $stockin_qty, $user_type, $change_log, $stock_admin_id);
        $logInserted = $insertLog->execute();

        // Get the inserted stock_history ID
        $stock_id = $this->conn->insert_id;

        $insertLog->close();

        // 5. Final JSON result
        return json_encode([
            "status" => ($stockUpdated && $logInserted) ? "success" : "error",
            "stock_id" => ($logInserted) ? $stock_id : null
        ]);
    }

        public function record_supply_logs(
            $stock_id,
            $stockin_supplier,
            $stockin_price
        ) {
            // Use parameter binding to prevent SQL injection
            $query = $this->conn->prepare("
                INSERT INTO supply_logs (sl_stock_id, sl_supplier_name, sl_supplier_price) 
                VALUES (?, ?, ?)
            ");

            $query->bind_param("isd", $stock_id, $stockin_supplier, $stockin_price);

            if ($query->execute()) {
                $query->close();
                return 'success';
            } else {
                $query->close();
                return 'error';
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


   public function acceptUser($user_id) {
    // Generate a random 8-character password
    $plainPassword = bin2hex(random_bytes(4)); // 8-char password
    $hashedPassword = hash('sha256', $plainPassword); // SHA-256 hash

    // Update user status and hashed password
    $query = $this->conn->prepare("UPDATE `user` SET `status` = 1, `password` = ? WHERE `user_id` = ?");
    $query->bind_param("si", $hashedPassword, $user_id);

    if ($query->execute()) {
        return [
            'status' => 200,
            'message' => 'User accepted and password generated.',
            'generated_password' => $plainPassword // plain password to be emailed
        ];
    } else {
        return [
            'status' => 500,
            'message' => 'Error: ' . $query->error
        ];
    }
}

public function declineUser($user_id) {
    $query = $this->conn->prepare("DELETE FROM `user` WHERE `user_id` = ?");
    $query->bind_param("i", $user_id);

    if ($query->execute()) {
        return [
            'status' => 200,
            'message' => 'User declined and deleted.'
        ];
    } else {
        return [
            'status' => 500,
            'message' => 'Error: ' . $query->error
        ];
    }
}

public function restrict($user_id) {
    $query = $this->conn->prepare("UPDATE `user` SET `status` = 2 WHERE `user_id` = ?");
    if ($query === false) {
        return [
            'status' => 500,
            'message' => 'Prepare failed: ' . $this->conn->error
        ];
    }

    $query->bind_param("i", $user_id);

    if ($query->execute()) {
        return [
            'status' => 200,
            'message' => 'User restricted successfully.'
        ];
    } else {
        return [
            'status' => 500,
            'message' => 'Execute failed: ' . $query->error
        ];
    }
}




public function activateUser($user_id) {
    $query = $this->conn->prepare("UPDATE `user` SET `status` = 1 WHERE `user_id` = ?");
    if ($query === false) {
        return [
            'status' => 500,
            'message' => 'Prepare failed: ' . $this->conn->error
        ];
    }

    $query->bind_param("i", $user_id);

    if ($query->execute()) {
        return [
            'status' => 200,
            'message' => 'User Activated successfully.'
        ];
    } else {
        return [
            'status' => 500,
            'message' => 'Execute failed: ' . $query->error
        ];
    }
}





     public function updateProductStatus($prod_id) {
       
        $query = "UPDATE `product` 
                  SET `prod_status` = 0
                  WHERE `prod_id` = $prod_id";

        // Execute the query
        if ($this->conn->query($query)) {
            return 'success';
        } else {
            return 'Error: ' . $this->conn->error;
        }
}

    

}
?>