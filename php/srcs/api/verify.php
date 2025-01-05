<?php
require_once("../includes/database.php");
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    echo json_encode(["error" => "Method not allowed."]);
    exit();
}

$token = $_GET["token"] ?? null;

if (!$token) {
    echo json_encode(["error" => "Token is required"]);
    exit();
}

$query = $conn->prepare("SELECT * FROM users WHERE token = ?");
$query->bind_param("s", $token);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["error" => "Invalid token"]);
    exit();
}

$user = $result->fetch_assoc();
$query = $conn->prepare("UPDATE users SET verified = 1, token = NULL WHERE token = ?");
$query->bind_param("s", $token);
$query->execute();

header("Location: /login.php");
exit();
?>
