<?php
session_start();
require_once("../includes/database.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Method not allowed."]);
    exit();
}

if (!$_SESSION['user']) {
    echo json_encode(["error" => "You must be logged in to upload an image."]);
    exit();
}

$userId = $_SESSION['user'];
$description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
$imageData = $_POST['image_data']; // Base64 encoded image


if (!$description || !$imageData) {
    echo json_encode(["error" => "All fields are required."]);
    exit();
}

// Remove "data:image/png;base64," part of the string
$imageData = str_replace('data:image/png;base64,', '', $imageData);

// Decode the Base64 string
$imageData = base64_decode($imageData);

// Generate a unique filename
$fileName = '/var/www/html/rsrcs/public/' . uniqid('img_', true) . '.png';

$path = '/rsrcs/public/' . basename($fileName);

// Save the image to the server
file_put_contents($fileName, $imageData);

try {
    $query = $conn->prepare("INSERT INTO images (userId, path, description) VALUES (?, ?, ?)");
    $query->bind_param("iss", $userId, $path, $description);
    $query->execute();
}
catch (Exception $e) {
    echo json_encode(["error" => "An error occurred while uploading the image."]);
    exit();
}

// Insert image info into the database
echo json_encode(["success" => "Image uploaded successfully."]);
exit();
?>