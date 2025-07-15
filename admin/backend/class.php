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
    

}
?>