<?php
session_start();
header('Content-Type: application/json');
require_once("../includes/database.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Method not allowed."]);
    exit();
}

if (!isset($_SESSION['user'])) {
    echo json_encode(["error" => "You must be logged in to update settings."]);
    exit();
}

$userId = $_SESSION['user']['id'] ?? null;
$username = htmlspecialchars($_POST["username"] ?? null, ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_POST["email"] ?? null, ENT_QUOTES, 'UTF-8');
$password = $_POST['password'] ?? null;
$confirm_password = $_POST['confirm_password'] ?? null;
$notifications = isset($_POST['notifications']) ? 1 : 0;

if (empty($username) || empty($email)) {
    echo json_encode(["error" => "Username and email are required."]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["error" => "Invalid email format."]);
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

// Handle Password Update
if ($password || $confirm_password) {
    if ($password !== $confirm_password) {
        echo json_encode(["error" => "Passwords do not match."]);
        exit();
    }

    if (strlen($password) < 8) {
        echo json_encode(["error" => "Password must be at least 8 characters long."]);
        exit();
    }

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $query = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $query->bind_param("si", $password_hashed, $userId);
    if (!$query->execute()) {
        echo json_encode(["error" => "Failed to update password."]);
        exit();
    }
}

// Handle Username Update
if ($username !== $_SESSION['user']['username']) {
    $query = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(["error" => "Username already exists."]);
        exit();
    } else {
        $query = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
        $query->bind_param("si", $username, $userId);
        if (!$query->execute()) {
            echo json_encode(["error" => "Failed to update username."]);
            exit();
        }
        $_SESSION['user']['username'] = $username;
    }
}

// Handle Email Update
if ($email !== $_SESSION['user']['email']) {
    $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(["error" => "Email already exists."]);
        exit();
    } else {
        $query = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
        $query->bind_param("si", $email, $userId);
        if (!$query->execute()) {
            echo json_encode(["error" => "Failed to update email."]);
            exit();
        }
        $_SESSION['user']['email'] = $email;
    }
}

// Handle Notifications Update
$query = $conn->prepare("UPDATE users SET notifications = ? WHERE id = ?");
$query->bind_param("ii", $notifications, $userId);
if (!$query->execute()) {
    echo json_encode(["error" => "Failed to update notifications."]);
    exit();
}
$_SESSION['user']['notifications'] = $notifications;

echo json_encode(["success" => "Settings updated successfully."]);
exit();
?>