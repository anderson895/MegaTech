<?php

require '../../../vendor/autoload.php';
include('../class.php');
$db = new global_class();

date_default_timezone_set('Asia/Manila');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// print_r($_POST);
$userId = $_POST['user_id'];
$order_code = $_POST['order_code'];
$order_id = $_POST['order_id'];
$action = "schedule_assigned"; 

$fetch_order = $db->fetch_order($order_id);
$order = mysqli_fetch_assoc($fetch_order);

$pickupDate = $order['order_pickup_date'];
$pickupTime = $order['order_pickup_time'];
$pickupDateTime = new DateTime("$pickupDate $pickupTime");
$formattedPickup = $pickupDateTime->format('l, F j, Y - g:i A');

class Mailer extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }

    public function sendAccountNotification($userId, $order_code, $formattedPickup)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM user WHERE user_id = ?");
            if (!$stmt) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }

            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                echo json_encode(['status' => 404, 'message' => 'User not found.']);
                return;
            }

            $user = $result->fetch_assoc();
            $Email = $user["Email"];
            $Fullname = $user["Fullname"];

            // Compose message with schedule
            $subject = "Your Order Schedule is Set";
            $message = "
                <h2>Hi $Fullname,</h2>
                <p>Your order <strong>$order_code</strong> has been <strong>approved</strong>.</p>
                <p><strong>Pickup Schedule:</strong><br>
                $formattedPickup</p>
                <p>Please be present at the pickup location on the scheduled date and time.</p>
                <p>Thank you for choosing us!</p>
            ";
            $altMessage = "Hello $Fullname,\n\nYour order $order_code has been approved.\nPickup Schedule: $formattedPickup\n\nPlease be present at the pickup location.\nThank you!";

            // PHPMailer setup
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'angeladeniseflores199@gmail.com';
            $mail->Password = 'rpbm yjls katl wcrt'; // Use App Password for Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('adornsia@gmail.com', 'MEGATECH');
            $mail->addAddress($Email, $Fullname);
            $mail->addReplyTo('no-reply@yourdomain.com', 'No Reply');

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = "
                <!DOCTYPE html>
                <html>
                <head><meta charset='UTF-8'></head>
                <body>
                    <div style='max-width:600px; margin:auto; padding:20px; background:#f9f9f9; border-radius:8px; font-family:sans-serif;'>
                        $message
                    </div>
                </body>
                </html>
            ";
            $mail->AltBody = $altMessage;

            $mail->send();

            echo json_encode(['status' => 200, 'message' => 'Email sent successfully.']);

        } catch (Exception $e) {
            echo json_encode([
                'status' => 500,
                'message' => 'Mailer Error: ' . $e->getMessage()
            ]);
        }
    }
}

// Send the email
$mailer = new Mailer();
$mailer->sendAccountNotification($userId, $order_code, $formattedPickup);
