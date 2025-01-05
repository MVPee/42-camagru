<?php
    if ($_SERVER["REQUEST_METHOD"] !== "GET") {
        echo json_encode(["error" => "Method not allowed."]);
        exit();
    }

    session_start();
    session_destroy();
    header("Location: /");
    exit();
?>
