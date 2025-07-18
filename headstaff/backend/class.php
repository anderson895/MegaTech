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
        $query = $this->conn->prepare("SELECT * FROM orders WHERE orders.order_id = '$order_id'");
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



    

}
?>