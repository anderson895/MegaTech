<?php 
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";


include('../class.php');

$db = new global_class();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['requestType']=="RefundProduct") {

        print_r($_POST);
        $item_id =$_POST['item_id'];
        $RefundReason = $_POST['RefundReason'];

        $response = $db->RefundProduct($item_id,$RefundReason);

        echo json_encode(['status' => $response]);
        
    }else if ($_POST['requestType']=="AddToCart") {
        $userId = $_POST['cart_user_id'];
        $productId = $_POST['cart_prod_id'];

        $response = $db->AddToCart($userId, $productId);

        echo json_encode(['status' => $response]);

    }else if ($_POST['requestType']=="UpdateUserPassword") {
       // Get input data from POST request
        $userID = $_POST['userID'];
        $user_NewPassword = $_POST['user_NewPassword'];
        $user_CurrentPassword = $_POST['user_CurrentPassword'];
        $response = $db->update_user_password($userID, $user_NewPassword, $user_CurrentPassword);
        echo json_encode($response);

    }else if ($_POST['requestType']=="UpdateUserProfile") {
    $userID = $_POST['userID'];
    $fullname = $_POST['user_fullname'];
    $email = $_POST['user_email'];
    $phone = $_POST['user_phone'];

    $db = new global_class();

    $oldImage = $db->fetch_user_profile_image($userID); 

    $profileImage = '';
    if (isset($_FILES['profileimage']) && $_FILES['profileimage']['error'] == 0) {
        $uploadDir = '../../../upload/';
        $fileExtension = pathinfo($_FILES['profileimage']['name'], PATHINFO_EXTENSION);
        $uniqueFileName = uniqid('profile_', true) . '.' . $fileExtension;
        $profileImage = $uploadDir . $uniqueFileName;
        if (move_uploaded_file($_FILES['profileimage']['tmp_name'], $profileImage)) {
            if ($oldImage && file_exists($oldImage)) {
                unlink($oldImage); 
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload new profile image']);
            exit;
        }
    } else {
        $profileImage = $oldImage;
    }
    $updateSuccess = $db->update_user_info($userID, $fullname, $email, $phone, $uniqueFileName);

    if ($updateSuccess) {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
    } else {
        if ($profileImage !== $oldImage && file_exists($profileImage)) {
            unlink($profileImage); 
        }
        echo json_encode(['status' => 'error', 'message' => 'Failed to update profile']);
    }


    }else if ($_POST['requestType']=="returnItem") {
        $item_id = $_POST['item_id'];
        $item_qty = $_POST['item_qty'];
        $reason = $_POST['reason'];

        $return_image = '';
        $uploadDir = '../../../upload/';
        $uniqueFileName = null;
        
        if (!$item_id || !$item_qty || !$reason) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
            exit;
        }
        if (isset($_FILES['return_image']) && $_FILES['return_image']['error'] === 0) {
            $fileExtension = pathinfo($_FILES['return_image']['name'], PATHINFO_EXTENSION);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid file type.']);
                exit;
            }

            if ($_FILES['return_image']['size'] > 2 * 1024 * 1024) { // 2MB limit
                echo json_encode(['status' => 'error', 'message' => 'File size exceeds 2MB limit.']);
                exit;
            }

            $uniqueFileName = uniqid('return_', true) . '.' . $fileExtension;
            $return_image = $uploadDir . $uniqueFileName;

            if (!move_uploaded_file($_FILES['return_image']['tmp_name'], $return_image)) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
                exit;
            }
        }
        $result = $db->return_item($item_id, $item_qty, $reason, $uniqueFileName);
        if (is_array($result) && $result['status'] === 'success') {
            echo json_encode(['status' => 200, 'message' => 'Return request submitted successfully.']);
        } else {
            $message = is_array($result) && isset($result['message']) ? $result['message'] : 'Something went wrong.';
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
    }else if ($_POST['requestType'] == 'cancelReturnOrder') {

        $newStatus=3;

        $item_id = $_POST['item_id'];
            $order = $db->updateOrderStatus($item_id, $newStatus);

            if ($order) {
                echo 200; 
            } else {
                echo 'Failed to update order in the database.';
            }
                

    }else if ($_POST['requestType']=="AddToCart") {
        $userId = $_POST['cart_user_id'];
        $productId = $_POST['cart_prod_id'];

        $response = $db->AddToCart($userId, $productId);

        echo json_encode(['status' => $response]);

    }else if ($_POST['requestType']=="MinusToCart") {
        $userId = $_POST['cart_user_id'];
        $productId = $_POST['cart_prod_id'];
        
        $response = $db->MinusToCart($userId, $productId);
        
        echo json_encode(['status' => $response]);
    }else if ($_POST['requestType']=="RemoveItem") {
       
        session_start();
        $user_id = $_SESSION['user_id'];
        $cart_id = $_POST['cart_id'];
        $size = $_POST['size'];
        $response = $db->RemoveItem($user_id,$cart_id,$size);
        echo json_encode(['status' => $response]);
    }else if ($_POST['requestType']=="OrderRequest") {

      
      
        $total = $_POST['total'];

        $selectedPaymentMethod = $_POST['selectedPaymentMethod'];
        
        $selectedProducts = $_POST['selectedProducts'] ?? null;

        // Decode the JSON string into a PHP array
        $selectedProductsArray = json_decode($selectedProducts, true);  
        $selectedFilePath = null;
        $uniqueFileName = null;
        $fileTmpPath = null;

        if (isset($_FILES['selectedFile']) && $_FILES['selectedFile']['error'] == UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['selectedFile']['tmp_name'];
            $fileName = $_FILES['selectedFile']['name'];
            $uploadDir = '../../../proofPayment/';

            // Generate a unique filename to prevent overwriting
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = uniqid('proof_', true) . '.' . $fileExtension;

            // Validate file size and type before uploading
            $fileSize = $_FILES['selectedFile']['size'];
            $fileType = $_FILES['selectedFile']['type'];
            $maxFileSize = 10 * 1024 * 1024;  
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

            if (!in_array($fileType, $allowedMimeTypes)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid file type.']);
                exit;
            }

            if ($fileSize > $maxFileSize) {
                echo json_encode(['status' => 'error', 'message' => 'File size exceeds the maximum limit.']);
                exit;
            }

            $selectedFilePath = $uploadDir . $uniqueFileName;
        } else {
            $uniqueFileName = null;
            $selectedFilePath = null;
        }

        $response = $db->OrderRequest($selectedPaymentMethod, $uniqueFileName, $total);

if ($response['status'] === 'success') {
    $orderId = $response['order_id'];
    $orderCode = $response['order_code'];

    if (is_array($selectedProductsArray) && !empty($selectedProductsArray)) {
        foreach ($selectedProductsArray as $product) {
            $itemProductId = $product['productId'];
            $itemQty = intval($product['qty']);  
            $itemTotalPrice = $product['price']; 
            $originalPrice = $product['originalPrice'];

            $insertQuery = "INSERT INTO orders_item 
                            (item_order_id, item_product_id, item_product_price, item_qty, item_total) 
                            VALUES (?, ?, ?, ?, ?)";

            $stmt = $db->conn->prepare($insertQuery);
            if ($stmt) {
                $stmt->bind_param("iisid", $orderId, $itemProductId, $originalPrice, $itemQty, $itemTotalPrice);
                if (!$stmt->execute()) {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to insert item: ' . $stmt->error]);
                    exit;
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $db->conn->error]);
                exit;
            }

            // Remove item from cart
            $user_id = $_SESSION['user_id'];
            $removeResult = $db->RemoveItem($user_id, $itemProductId);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Selected products data is invalid or empty.']);
        exit;
    }

    // Handle file upload if needed
    if ($selectedFilePath && !is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($fileTmpPath, $selectedFilePath)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Order processed and file saved successfully.',
            'order_id' => $orderId,
            'order_code' => $orderCode
        ]);
        exit;
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Order processed but file upload failed.',
            'order_id' => $orderId,
            'order_code' => $orderCode
        ]);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Order request failed.']);
    exit;
}



    }

}else if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  
}



 
 ?>
     