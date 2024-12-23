<?php
    require_once("database.php");
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
        echo $username . "<br>";
        echo $password;
    }
?>