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

    // Execute and check
    if ($stmt->execute()) {
        return $this->conn->insert_id;
    } else {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }
}





    
    public function fetch_all_category(){
        $query = $this->conn->prepare("SELECT * FROM `category`");

        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
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



     public function pickedupOrder($orderId, $orderStatus) {
        $stmt = $this->conn->prepare("UPDATE `orders` SET `order_status` = '$orderStatus' WHERE `orders`.`order_id` = '$orderId'");
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
    $insertLog->close();

    // 5. Final result
    return ($stockUpdated && $logInserted) ? 'success' : 'error';
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