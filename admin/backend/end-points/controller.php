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


    }else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid request type'
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