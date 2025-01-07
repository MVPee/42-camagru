<?php
    session_start();

require_once("../includes/database.php");
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Method not allowed."]);
    exit();
}

$userId = $_SESSION['user'];
$description = $_POST['description'];
$imageData = $_POST['image_data']; // Base64 encoded image

// Remove "data:image/png;base64," part of the string
$imageData = str_replace('data:image/png;base64,', '', $imageData);

// Decode the Base64 string
$imageData = base64_decode($imageData);

// Generate a unique filename
$fileName = '/var/www/html/rsrcs/public/' . uniqid('img_', true) . '.png';

// Save the image to the server
file_put_contents($fileName, $imageData);

// Insert image info into the database
echo json_encode(["success" => "Image uploaded successfully."]);
exit();
?>