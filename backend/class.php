<?php


include ('db.php');

date_default_timezone_set('Asia/Manila');

class global_class extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }


     public function fetch_all_categories(){
        $query = $this->conn->prepare("SELECT * 
        FROM category where status='1'"    
    );

        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }


    public function LoginAdmin($username, $password)
    {
        $query = $this->conn->prepare("SELECT * FROM `user_admin` WHERE `username` = ?");
        $query->bind_param("s", $username);
    
        if ($query->execute()) {
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
    
                if (password_verify($password, $user['password'])) {
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['user_type'] = "admin";
                    $query->close();
                    return ['success' => true, 'data' => $user];
                } else {
                    // Password mismatch
                    $query->close();
                    return ['success' => false, 'message' => 'Login Failed.'];
                }
            } else {
                // No user found
                $query->close();
                return ['success' => false, 'message' => 'Login Failed.'];
            }
        } else {
            $query->close();
            return ['success' => false, 'message' => 'Database error during execution.'];
        }
    }



    public function LoginMember($email, $password)
    {
        $query = $this->conn->prepare("SELECT * FROM `user_member` WHERE `email` = ? AND `status` != '2'");
        $query->bind_param("s", $email);
    
        if ($query->execute()) {
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
    
                if (password_verify($password, $user['password'])) {
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
    
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['user_type'] = "member";
                    $query->close();
                    return ['success' => true, 'data' => $user];
                } else {
                    // Password mismatch
                    $query->close();
                    return ['success' => false, 'message' => 'Incorrect password.'];
                }
            } else {
                // No user found
                $query->close();
                return ['success' => false, 'message' => 'Email not found or account inactive.'];
            }
        } else {
            $query->close();
            return ['success' => false, 'message' => 'Database error during execution.'];
        }
    }
    










     public function LoginCustomer($email, $password)
    {
        $query = $this->conn->prepare("SELECT * FROM `user_customer` WHERE `customer_email` = ? AND `customer_status` = '1'");
        $query->bind_param("s", $email);
    
        if ($query->execute()) {
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
    
                if (password_verify($password, $user['customer_password'])) {
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
    
                    $_SESSION['customer_id'] = $user['customer_id'];
                    $query->close();
                    return ['success' => true, 'data' => $user];
                } else {
                    // Password mismatch
                    $query->close();
                    return ['success' => false, 'message' => 'Incorrect password.'];
                }
            } else {
                // No user found
                $query->close();
                return ['success' => false, 'message' => 'Email not found or account inactive.'];
            }
        } else {
            $query->close();
            return ['success' => false, 'message' => 'Database error during execution.'];
        }
    }
    



    public function RegisterMember($fname, $mname, $email, $phone, $role, $sex, $id_number, $password)
    {
        // Step 1: Check if the email already exists in the database
        $query = $this->conn->prepare("SELECT COUNT(*) FROM `user_member` WHERE `email` = ?");
        if (!$query) {
            return false; // Query preparation failed
        }
        $query->bind_param("s", $email);
        $query->execute();
        $query->bind_result($emailCount);
        $query->fetch();
        $query->close();
    
        // If email already exists, return false or an error message
        if ($emailCount > 0) {
            return "Email is already registered."; // Or you can return a specific error code/message
        }
    
        // Step 2: If email doesn't exist, proceed with registration
        $fullname = $fname . ' ' . $mname;
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        $query = $this->conn->prepare("
            INSERT INTO `user_member`
                (`fullname`, `email`, `phone`, `password`, `role`, `sex`, `id_number`)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
    
        $query->bind_param(
            "sssssss",
            $fullname,
            $email,
            $phone,
            $hashedPassword,
            $role,
            $sex,
            $id_number
        );
    
        $result = $query->execute();
        $query->close();
    
        return $result;
    }
    

    




 public function RegisterCustomer($fullname, $email, $phone,$password)
    {
        // Step 1: Check if the email already exists in the database
        $query = $this->conn->prepare("SELECT COUNT(*) FROM `user_customer` WHERE `customer_email` = ?");
        if (!$query) {
            return false; // Query preparation failed
        }
        $query->bind_param("s", $email);
        $query->execute();
        $query->bind_result($emailCount);
        $query->fetch();
        $query->close();
        // If email already exists, return false or an error message
        if ($emailCount > 0) {
            return "Email is already registered."; // Or you can return a specific error code/message
        }
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        $query = $this->conn->prepare("
            INSERT INTO `user_customer`
                (`customer_fullname`, `customer_email`, `customer_phone`, `customer_password`)
            VALUES (?, ?, ?, ?)
        ");
        $query->bind_param(
            "ssss",
            $fullname,
            $email,
            $phone,
            $hashedPassword
        );
        $result = $query->execute();
        $query->close();
        return $result;
    }








public function check_account($id) {

    $id = intval($id);

    $query = "SELECT * FROM user_member WHERE id = $id";

    $result = $this->conn->query($query);

    $items = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    return $items; 
}


public function fetch_all_product() {
    $query = $this->conn->prepare("
        SELECT 
            product.*, 
            category.category_name 
        FROM product
        LEFT JOIN category ON product.prod_category_id = category.category_id
        WHERE product.prod_status = '1'
    ");

    if ($query === false) {
        return 'Error: ' . $this->conn->error;
    }

    if ($query->execute()) {
        $result = $query->get_result();
        return $result;
    } else {
        return false;
    }
}



public function getPriceRange() {
    $sql = "SELECT MIN(prod_price) AS min_price, MAX(prod_price) AS max_price FROM product WHERE prod_status = 1";
    $result = $this->conn->query($sql);
    return $result->fetch_assoc();
}

    



}