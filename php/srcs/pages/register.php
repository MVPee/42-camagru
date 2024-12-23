<?php
    session_start();
    require_once("../api/database.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <title>Camagru - Register</title>
    </head>
    <body>
        <?php include("../components/navbar.php"); ?>
        <h1>Register</h1>
        <?php include("../components/footer.php"); ?>
    </body>
</html>

<style>
    body {
        margin: 0;
        padding: 0;
    }
</style>