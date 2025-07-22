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
        }else  if ($_POST['requestType'] == 'AddProduct') {

                $product_Code = $_POST['product_Code'];
                $product_Name = $_POST['product_Name'];
                $product_Price = $_POST['product_Price'];
                $critical_Level = $_POST['critical_Level'];

                $product_Category = $_POST['product_Category'];
                $product_Description = $_POST['product_Description'];
                $product_Stocks = $_POST['product_Stocks'];

                $product_Image = $_FILES['product_Image'];

                // Get specs from POST
                $specs_names = $_POST['specs_name'] ?? [];
                $specs_values = $_POST['specs_value'] ?? [];

                $specs = [];
                for ($i = 0; $i < count($specs_names); $i++) {
                    $name = trim($specs_names[$i]);
                    $value = trim($specs_values[$i]);
                    if ($name !== '' && $value !== '') {
                        $specs[] = [
                            'Specs' => $name,
                            'value' => $value
                        ];
                    }
                }

                if ($product_Image['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = '../../../upload/';
                    $fileExtension = pathinfo($product_Image['name'], PATHINFO_EXTENSION);
                    $uniqueFileName = uniqid('product_', true) . '.' . $fileExtension;
                    $uploadFilePath = $uploadDir . $uniqueFileName;

                    if (move_uploaded_file($product_Image['tmp_name'], $uploadFilePath)) {
                        $prod_id = $db->addProduct([
                            'code' => $product_Code,
                            'name' => $product_Name,
                            'price' => $product_Price,
                            'critical_level' => $critical_Level,
                            'category' => $product_Category,
                            'description' => $product_Description,
                            'image' => $uniqueFileName,
                            'stocks' => $product_Stocks,
                            'specs' => $specs 
                        ]);

                        if ($prod_id) {
                            echo "200"; // SUCCESS
                        } else {
                            echo "Error saving product data.";
                        }

                    } else {
                        echo "Error uploading image. Please try again.";
                    }
                } else {
                    echo "No image uploaded or there was an error with the image.";
                }


        
         }  else if ($_POST['requestType'] == 'UpdateProduct') {
        
        $product_ID = $_POST['product_ID'];
        $product_Code = $_POST['product_Code'];
        $product_Name = $_POST['product_Name'];
        $product_Price = $_POST['product_Price'];
        $critical_Level = $_POST['critical_Level'];
        
        $product_Category = $_POST['product_Category'];
        $product_Description = $_POST['product_Description'];
        
        $product_Image = $_FILES['product_Image'];


          // Get specs from POST
                $specs_names = $_POST['specs_name'] ?? [];
                $specs_values = $_POST['specs_value'] ?? [];

                $specs = [];
                for ($i = 0; $i < count($specs_names); $i++) {
                    $name = trim($specs_names[$i]);
                    $value = trim($specs_values[$i]);
                    if ($name !== '' && $value !== '') {
                        $specs[] = [
                            'Specs' => $name,
                            'value' => $value
                        ];
                    }
                }

        
        $existingImageName = $db->getProductImageById($product_ID); 
        
        if ($product_Image['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../../upload/';
        
            if ($existingImageName && file_exists($uploadDir . $existingImageName)) {
                unlink($uploadDir . $existingImageName);  
            }
        
            $fileExtension = pathinfo($product_Image['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('product_', true) . '.' . $fileExtension;
        
            $uploadFilePath = $uploadDir . $newFileName;
        
            if (move_uploaded_file($product_Image['tmp_name'], $uploadFilePath)) {
                $user = $db->updateProduct(
                    $product_ID,
                    $product_Code,
                    $product_Name,
                    $product_Price,
                    $critical_Level,
                    $product_Category,
                    $product_Description,
                    $newFileName,
                    $specs
                );
        
                if ($user === 'success') {
                    echo 200; 
                } else {
                    echo 'Failed to update product in the database.';
                }
            } else {
                echo 'Error uploading image. Please try again.';
            }
        } else {
            $user = $db->updateProduct(
                $product_ID,
                $product_Code,
                $product_Name,
                $product_Price,
                $critical_Level,
                $product_Category,
                $product_Description,
                $existingImageName,
                $specs
            );
        
            if ($user === 'success') {
                echo 200;  
            } else {
                echo 'Failed to update product in the database.';
            }
        }
        
        

        
    }else if($_POST['requestType'] =='removeProduct'){

      
        $prod_id=$_POST['prod_id'];

        $result = $db->updateProductStatus($prod_id);

         if ($result == "success") {
            echo json_encode(["status" => 200, "message" => "Remove Successfully"]);
        } else {
            echo json_encode(["status" => 400, "message" => $result]);
        }


    }else if($_POST['requestType'] =='StockIn'){

        session_start();

        $admin_id = intval($_SESSION['admin_id']);

        $prod_name = $_POST['product_name_stockin'];
        $stockin_qty = $_POST['stockin_qty'];
        $stock_prod_id = $_POST['product_id_stockin'];

        $user = $db->updateStock(
            $stock_prod_id,
            $admin_id,
            $stockin_qty,
            $prod_name
        );

        if ($user === 'success') {
            echo 200;
        } else {
            echo 'Failed to update product in the database.';
        }
    }else if ($_POST['requestType'] == 'acceptUser') {

        $user_id = $_POST['user_id'];
        $response = $db->acceptUser($user_id);

        echo json_encode($response);

    } else if ($_POST['requestType'] == 'declineUser') {

        $user_id = $_POST['user_id'];
        $response = $db->declineUser($user_id);

        echo json_encode($response);

    } else if ($_POST['requestType'] == 'restrictUser') {

        $user_id = $_POST['user_id'];
        $response = $db->restrict($user_id);

        echo json_encode($response);

    } else if ($_POST['requestType'] == 'activateUser') {

        $user_id = $_POST['user_id'];
        $response = $db->activateUser($user_id);

        echo json_encode($response);

    }else if ($_POST['requestType'] == 'pickedupUser') {

        $user_id = $_POST['user_id'];
        $response = $db->pickedupUser($user_id);

        echo json_encode($response);
    } else if ($_POST['requestType'] == 'setSchedule') {

        $orderId = $_POST['orderId'];
        $scheduleDate = $_POST['scheduleDate'];
        $scheduleTime = $_POST['scheduleTime'];
        $newStatus = "scheduled";
     
        $order = $db->updateOrderStatus($orderId,$scheduleDate,$scheduleTime,$newStatus);

        if ($order === true) {
            echo json_encode([
            'status' => 200,
            'message' => 'Stock updated successfully (stock out).'
            ]);
        } else {
             echo json_encode([
             'status' => 500,
             'message' => 'Failed to update stock in the database: ' . $order
               ]);
        }
    }else if ($_POST['requestType'] == 'pickedupOrder') {

        $orderStatus="pickedup";
        $orderId = $_POST['order_id'];
            $order = $db->pickedupOrder($orderId, $orderStatus);

            if ($order) {
                echo 200; 
            } else {
                echo 'Failed to update order in the database.';
            }
                

    }else if ($_POST['requestType'] == 'approveReturn') {

        $return_status=4;
        $return_id = $_POST['return_id'];
            $order = $db->approveReturn($return_id, $return_status);

            if ($order) {
                echo 200; 
            } else {
                echo 'Failed to update order in the database.';
            }
                

    }else if ($_POST['requestType'] == 'cancelReturn') {

        $return_status=2;
        $return_id = $_POST['return_id'];
            $order = $db->cancelReturn($return_id, $return_status);

            if ($order) {
                echo 200; 
            } else {
                echo 'Failed to update order in the database.';
            }
                

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