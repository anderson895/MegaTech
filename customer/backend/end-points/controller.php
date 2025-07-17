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

    
            // Call the function
        $response = $db->update_user_password($userID, $user_NewPassword, $user_CurrentPassword);

        // Echo JSON encoded response
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
        // If the update fails, retain the old image
        if ($profileImage !== $oldImage && file_exists($profileImage)) {
            unlink($profileImage); // Delete the newly uploaded image
        }
        echo json_encode(['status' => 'error', 'message' => 'Failed to update profile']);
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

        // Handle file upload if a file was provided
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

            if (is_array($selectedProductsArray) && !empty($selectedProductsArray)) {
                foreach ($selectedProductsArray as $product) {
                    $itemProductId = $product['productId'];
                    $itemQty = intval($product['qty']);  
                    $itemTotalPrice =$product['price']; 
                    $originalPrice = $product['originalPrice'];
                
                    $insertQuery = "INSERT INTO orders_item (item_order_id, item_product_id, item_product_price,item_qty,item_total) 
                                    VALUES ('$orderId', '$itemProductId', '$originalPrice', '$itemQty', '$itemTotalPrice')";
                    
                    $user_id = $_SESSION['user_id'];
                    
                    $response = $db->RemoveItem($user_id, $itemProductId);

                    if (!$db->conn->query($insertQuery)) {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to insert product into orders_item.']);
                        exit;
                    }
                }
                
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Selected products data is invalid.']);
                exit;
            }
            
            if ($selectedFilePath && !is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

           if (move_uploaded_file($fileTmpPath, $selectedFilePath)) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Order processed and file saved successfully.',
                    'order_id' => $orderId
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Order processed but file upload failed.',
                    'order_id' => $orderId
                ]);
            }

        } else {
            echo json_encode(['status' => 'error', 'message' => 'Order request failed.']);
        }


    }

}else if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $response = $db->getPaymentQr();
    echo json_encode(['status' => $response]);
}



 
 ?>
     