<?php
header('Content-Type: application/json');
require_once("../includes/database.php");
require_once('../includes/send_email.php');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Method not allowed."]);
    exit();
}

$username = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8');
$password = $_POST["password"];
$confirm_password = $_POST["confirm_password"];

if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    echo json_encode(["error" => "All fields are required"]);
    exit();
}

if ($password !== $confirm_password) {
    echo json_encode(["error" => "Passwords do not match"]);
    exit();
}

if (strlen($username) < 5) {
    echo json_encode(["error" => "Username must be at least 5 characters long"]);
    exit();
}

if (strlen($username) > 20) {
    echo json_encode(["error" => "Username must be at most 20 characters long"]);
    exit();
}

if (strlen($password) < 8) {
    echo json_encode(["error" => "Password must be at least 8 characters long"]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["error" => "Invalid email address"]);
    exit();
}

$query = $conn->prepare("SELECT * FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
if ($result->num_rows > 0) {
    echo json_encode(["error" => "Username already exists"]);
    exit();
}

$query = $conn->prepare("SELECT * FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();
if ($result->num_rows > 0) {
    echo json_encode(["error" => "Email already exists"]);
    exit();
}

$password_hashed = password_hash($password, PASSWORD_DEFAULT);
$query = $conn->prepare("INSERT INTO users (username, email, password, token) VALUES (?, ?, ?, ?)");
$token = bin2hex(random_bytes(16));
$query->bind_param("ssss", $username, $email, $password_hashed, $token);

if ($query->execute()) {
    
    $result = sendEmail($email, "Camagru", "Please verify your email address. http://localhost:8080/api/verify.php?token=" . $token . " to verify your email address");
    if ($result['success']) {
        echo json_encode(["success" => "User registered successfully. Please verify your email address."]);
    } else {
        echo json_encode(["error" => "An error occurred : " . $result['message']]);
    }
}
else {
    echo json_encode(["error" => "An error occurred: " . $conn->error]);
}
exit();
?>
