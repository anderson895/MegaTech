<?php

require '../../../vendor/autoload.php';
include('../class.php');
$db = new global_class();

date_default_timezone_set('Asia/Manila');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$userId = $_POST['user_id'];
$action = $_POST['action']; // approve or cancel

class Mailer extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }

    public function sendReturnNotification($userId, $action)
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

            // Subject and message based on action
            if ($action === 'approve') {
                $subject = "Return Request Approved";
                $message = "
                    <h2>Hi $Fullname,</h2>
                    <p>Your return request has been <strong>approved</strong>.</p>
                    <p>Please proceed to the store <strong>today</strong> to return the product.</p>
                    <p>Don’t forget to bring the item and any necessary documentation (e.g., receipt or reference number).</p>
                    <p>Thank you for your cooperation!</p>
                ";
                $altMessage = "Hi $Fullname,\n\nYour return request has been approved.\nPlease proceed to the store today to return the product. Thank you!";
            } elseif ($action === 'cancel') {
                $subject = "Return Request Cancelled";
                $message = "
                    <h2>Hi $Fullname,</h2>
                    <p>Your return request has been <strong>cancelled</strong>.</p>
                    <p>Please disregard the return process. You do not need to go to the store.</p>
                    <p>If you have any concerns, feel free to contact our support team.</p>
                    <p>Thank you for understanding.</p>
                ";
                $altMessage = "Hi $Fullname,\n\nYour return request has been cancelled.\nYou do not need to proceed to the store. Thank you.";
            } else {
                echo json_encode(['status' => 400, 'message' => 'Invalid action specified.']);
                return;
            }

            // PHPMailer setup
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'angeladeniseflores199@gmail.com';
            $mail->Password = 'rpbm yjls katl wcrt'; // App Password
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
$mailer->sendReturnNotification($userId, $action);
