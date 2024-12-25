<?php
    try {
        $conn = mysqli_connect(
            $_ENV["DB_HOST"],
            $_ENV["MYSQL_ROOT"],
            $_ENV["MYSQL_ROOT_PASSWORD"],
            $_ENV["MYSQL_DATABASE"]
        );
    }
    catch (mysqli_sql_exception $e) {
        echo "Failed to connect to database: " . $e->getMessage();
        die();
    }
?>