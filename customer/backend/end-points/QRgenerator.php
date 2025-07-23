<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Color\Color;


// echo "<pre>";
// print_r($_POST);
// echo "</pre>";

$orderId = $_POST['order_id'];
$order_code = $_POST['order_code'];

$data = $order_code;

// Generate QR
$qrCode = new QrCode(
    data: $data,
    encoding: new Encoding('UTF-8'),
    errorCorrectionLevel: ErrorCorrectionLevel::High,
    size: 300,
    margin: 10,
    roundBlockSizeMode: RoundBlockSizeMode::Margin,
    foregroundColor: new Color(0, 0, 0),
    backgroundColor: new Color(255, 255, 255),
);

$writer = new PngWriter();
$result = $writer->write($qrCode);

// Path and filename
$filename = "qr_$orderId.png";
$relativePath = "../../../qrcodes/" . $filename;
$savePath = __DIR__ . '/' . $relativePath;

// Save image
$result->saveToFile($savePath);

// Return JSON with image URL (adjust path if needed for web access)
$response = [
    'status' => 'success',
    'qr_image_url' => "/qrcodes/$filename" // Make sure this matches the accessible URL
];

header('Content-Type: application/json');
echo json_encode($response);
exit;
