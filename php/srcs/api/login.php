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
        echo json_decode(["succes" => $username . " " . $password]);
    }
?>