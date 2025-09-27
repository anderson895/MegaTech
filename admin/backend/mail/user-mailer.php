<?php
require '../../../vendor/autoload.php';
include('../class.php');
$db = new global_class();

date_default_timezone_set('Asia/Manila');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$userId = $_POST['user_id'];
$action = $_POST['action']; // accept, decline, restrict
$password = $_POST['password'] ?? null;

class Mailer extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }

    public function sendAccountNotification($userId, $action, $password = null)
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
            if ($action === 'accept') {
                $subject = "Your account has been approved";
                $message = "
                    <h2>Hello, $Fullname</h2>
                    <p>Your account has been <strong style='color:green;'>approved</strong>.</p>
                    <p>Login credentials:</p>
                    <ul>
                        <li><strong>Email:</strong> $Email</li>
                        <li><strong>Password:</strong> $password</li>
                    </ul>
                    <p style='color:red;'><strong>⚠️ Please change your password immediately after logging in.</strong></p>
                    <p>
                        <a href='http://localhost/MegaTech/login?password=" . urlencode($password) . "&email=" . urlencode($Email) . "' 
                           style='background-color: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Login Now</a>
                    </p>
                ";
                $altMessage = "Hello $Fullname,\n\nYour account has been approved.\nEmail: $Email\nPassword: $password\n\nIMPORTANT: Change your password after logging in.";
            } elseif ($action === 'decline') {
                $subject = "Your account request was declined";
                $message = "
                    <h2>Hello, $Fullname</h2>
                    <p style='color:red;'>We regret to inform you that your account request has been declined.</p>
                    <p>If you believe this was a mistake, please contact support.</p>
                ";
                $altMessage = "Hello $Fullname,\n\nWe regret to inform you that your account request was declined.";
            } elseif ($action === 'restrict') {
                $subject = "Your account has been restricted";
                $message = "
                    <h2>Hello, $Fullname</h2>
                    <p style='color:orange;'>Your account has been temporarily <strong>restricted</strong> due to policy violations or pending verification.</p>
                    <p>Please contact support for more information or appeal the restriction.</p>
                ";
                $altMessage = "Hello $Fullname,\n\nYour account has been restricted. Contact support for more details.";
            } else {
                echo json_encode(['status' => 400, 'message' => 'Invalid action.']);
                return;
            }

            // PHPMailer setup
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            // $mail->Username = 'angeladeniseflores199@gmail.com';
            // $mail->Password = 'rpbm yjls katl wcrt'; 
            $mail->Username = 'depedtrack264@gmail.com'; 
            $mail->Password = 'krib ldvp jhfw orvn'; 
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
                    <div style='max-width:600px; margin:auto; padding:20px; background:#f9f9f9; border-radius:8px;'>
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
$mailer->sendAccountNotification($userId, $action, $password);
