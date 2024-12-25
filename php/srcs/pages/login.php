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
        <title>Camagru - Login</title>
    </head>
    <body>
        <?php include("../includes/components/navbar.php"); ?>
        <h1>Login</h1>

        <div id="response"></div>

        <form id="loginForm" action="/api/login.php" method="post">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>


        <?php include("../includes/components/footer.php"); ?>
    </body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch('/api/login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok)
                    throw new Error('Erreur réseau');
                return response.text();
            })
            .then(data => {
                document.getElementById('response').innerHTML = data;
            })
            .catch(error => {
                document.getElementById('response').innerHTML = 'Erreur: ' + error.message;
            });
        });
    });
</script>
