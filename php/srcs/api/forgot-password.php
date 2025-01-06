<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Method not allowed."]);
    exit();
}

require_once('../includes/send_email.php');
require_once("../includes/database.php");

$email = htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8');

if (empty($email)) {
    echo json_encode(["error" => "Email is required."]);
    exit();
}

$query = $conn->prepare("SELECT * FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();
if ($result->num_rows === 0) {
    echo json_encode(["error" => "Email not found."]);
    exit();
}

$user = $result->fetch_assoc();
$token = bin2hex(random_bytes(16));

$query = $conn->prepare("UPDATE users SET password_reset_token = ? WHERE email = ?");
$query->bind_param("ss", $token, $email);
if ($query->execute()) {
    echo json_encode(["success" => "Email sent."]);
    sendEmail($email, "Password Reset", "Reset your password here http://localhost:8080/reset-password.php?token=" . $token);
}
else
    echo json_encode(["error" => "An error occurred. Please try again."]);
exit();
?>