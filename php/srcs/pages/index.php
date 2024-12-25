<?php
    session_start();
    require_once("../includes/database.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <title>Camagru - Home</title>
    </head>
    <body>
        <?php include("../includes/components/navbar.php"); ?>
        <h1>Home</h1>
        <?php include("../includes/components/footer.php"); ?>
    </body>
</html>
