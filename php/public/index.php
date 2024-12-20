<?php
    include("../srcs/database.php");
    include("../srcs/router.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <title><?= "Camagru - " . $title; ?></title>
    </head>
    <body>
        <?php include("./components/navbar.php"); ?>
        <main>
            <?php 
                if ($page && file_exists($page))
                    include($page);
                else
                    echo "<h1>404 - Page Not Found</h1>";
            ?>
        </main>
        <?php include("./components/footer.php"); ?>
    </body>
</html>
