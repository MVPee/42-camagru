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

        <div id="response"></div>

        <form id="registerForm" action="/api/register.php" method="post">
            <input type="text" id="username" name="username" placeholder="Username" required>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
            <button type="submit">Register</button>
        </form>

        <?php include("../components/footer.php"); ?>
    </body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('registerForm');
        const responseDiv = document.getElementById('response');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch('/api/register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log(response);
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                console.log(data);
                if (data.success)
                    responseDiv.innerHTML = `<div class="success">${data.success}</div>`;
                else
                    responseDiv.innerHTML = `<div class="error">${data.error}</div>`;
            })
            .catch(error => {
                responseDiv.innerHTML = `<div class="error">Error: ${error.message}</div>`;
            });
        });
    });
</script>