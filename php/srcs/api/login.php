<?php
    header('Content-Type: application/json');
    require_once("../includes/database.php");

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        echo json_encode(["error" => "Method not allowed."]);
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');
        $password = $_POST["password"];

        if (empty($username) || empty($password)) {
            echo json_encode(["error" => "All fields are required"]);
            exit();
        }

        $query = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows === 0) {
            echo json_encode(["error" => "Invalid username or password"]);
            exit();
        }

        $user = $result->fetch_assoc();
        if (!password_verify($password, $user["password"])) {
            echo json_encode(["error" => "Invalid username or password"]);
            exit();
        }
        else {
            session_start();
            $_SESSION["user"] = $user;
            echo json_encode(["success" => "Login successful"]);
        }
        exit();
    }
?>