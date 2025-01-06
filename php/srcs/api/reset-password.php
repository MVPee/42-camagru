<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Method not allowed."]);
    exit();
}

require_once('../includes/send_email.php');
require_once("../includes/database.php");

$password = $_POST["password"];
$confirm_password = $_POST["confirmPassword"];
$token = $_POST["token"];

if (empty($password) || empty($confirm_password) || empty($token)) {
    echo json_encode(["error" => "All fields are required"]);
    exit();
}

if ($password !== $confirm_password) {
    echo json_encode(["error" => "Passwords do not match"]);
    exit();
}

if (strlen($password) < 8) {
    echo json_encode(["error" => "Password must be at least 8 characters long"]);
    exit();
}

$query = $conn->prepare("SELECT * FROM users WHERE password_reset_token = ?");
$query->bind_param("s", $token);
$query->execute();
$result = $query->get_result();
if ($result->num_rows === 0) {
    echo json_encode(["error" => "Invalid token"]);
    exit();
}

$user = $result->fetch_assoc();
$password_hashed = password_hash($password, PASSWORD_DEFAULT);
$query = $conn->prepare("UPDATE users SET password = ?, password_reset_token = NULL WHERE id = ?");
$query->bind_param("si", $password_hashed, $user["id"]);

if ($query->execute())
    echo json_encode(["success" => "Password reset successfully. You can now <a href='/login.php'>login</a> with your new password."]);
else
    echo json_encode(["error" => "An error occurred. Please try again."]);
exit();
?>