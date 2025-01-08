<?php
session_start();
require_once("../includes/database.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Method not allowed."]);
    exit();
}

if (!isset($_SESSION['user'])) {
    echo json_encode(["error" => "You must be logged in to delete an image."]);
    exit();
}

$userId = $_SESSION['user']['id'];
$imageId = $_POST['image_id'] ?? null;

if (!$imageId) {
    echo json_encode(["error" => "Image ID is required."]);
    exit();
}

$query = $conn->prepare("SELECT * FROM images WHERE id = ? AND userId = ?");
$query->bind_param("ii", $imageId, $userId);
$query->execute();
$result = $query->get_result();
$image = $result->fetch_assoc();

$imagePath = "/var/www/html" . $image['path'];
if (file_exists($imagePath)) {
    if (!unlink($imagePath)) {
        echo json_encode(["error" => "Failed to delete the image file."]);
        exit();
    }
} else {
    echo json_encode(["error" => "Image file does not exist."]);
    exit();
}

$query = $conn->prepare("DELETE FROM images WHERE id = ? AND userId = ?");
$query->bind_param("ii", $imageId, $userId);
$query->execute();


if ($query->affected_rows === 0) {
    echo json_encode(["error" => "Image not found or you do not have permission to delete it."]);
} else {
    echo json_encode(["success" => "Image deleted successfully."]);
}
exit();
?>