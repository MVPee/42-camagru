<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Method not allowed."]);
    exit();
}

if (!isset($_POST["publication"]) || empty($_POST["publication"])) {
    echo json_encode(["error" => "No image data provided."]);
    http_response_code(400);
    exit();
}

$imageData = $_POST['publication'];

$imageData = str_replace('data:image/png;base64,', '', $imageData);

$imageData = base64_decode($imageData);

$fileName = '/var/www/html/rsrcs/public/' . uniqid('img_', true) . '.png';

file_put_contents($fileName, $imageData);

echo json_encode(["success" => "Image uploaded successfully."]);
?>