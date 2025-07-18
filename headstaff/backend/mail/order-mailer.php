<?php
require '../../../vendor/autoload.php';
include('../class.php');
$db = new global_class();

date_default_timezone_set('Asia/Manila');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "<pre>";
print_r($_POST);
echo "</pre>";

$userId = $_POST['user_id'];
$action = $_POST['action'];
$order_code = $_POST['order_code'];

class Mailer extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }

    public function sendAccountNotification($userId, $order_code, $action)
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

            // Prepare message based on action
            if ($action === 'decline') {
                $subject = "Your account request was declined";
                $message = "
                    <h2>Hi $Fullname,</h2>
                    <p>We're sorry to inform you that your account request was declined.</p>
                    <p>If you believe this is a mistake or want to reapply, please contact our support team.</p>
                ";
                $altMessage = "Hello $Fullname,\n\nWe regret to inform you that your account request was declined. If you believe this is a mistake, please contact support.";

            } elseif ($action === 'paid') {
                $subject = "Order Approved - Awaiting Schedule";
                $message = "
                    <h2>Hi $Fullname,</h2>
                    <p>Thank you for your payment. Your order <strong>$order_code</strong> has been <strong>approved</strong>.</p>
                    <p>Please wait for the schedule to be assigned by our administrator.</p>
                    <p><strong>You will receive an email notification once your schedule has been finalized.</strong></p>
                ";
                $altMessage = "Hello $Fullname,\n\nThank you for your payment. Your order $order_code has been approved.\nPlease wait for the schedule from the administrator.\nYou will receive an email notification once your schedule has been finalized.";

            } else {
                echo json_encode(['status' => 400, 'message' => 'Invalid action.']);
                return;
            }

            // PHPMailer setup
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'angeladeniseflores199@gmail.com';
            $mail->Password = 'rpbm yjls katl wcrt'; // Use an App Password if using Gmail
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
$mailer->sendAccountNotification($userId, $order_code, $action);
