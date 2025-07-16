<?php
include('../class.php');

$db = new global_class();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['requestType'])) {
        if ($_POST['requestType'] == 'Signup') {


            $name=$_POST['name'];
            $email=$_POST['email'];
            $phone=$_POST['phone'];
            $account_type=$_POST['account_type'];
           

            echo $db->SignUp($name,$email,$phone,$account_type);
        } else {
            echo 'Else';
        }
    } else {
        echo 'Access Denied! No Request Type.';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
}
?>