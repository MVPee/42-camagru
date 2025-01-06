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
        <title>Camagru - Forgot</title>
    </head>
    <body>
        <?php include("../includes/components/navbar.php"); ?>

        <h1>Forgot password</h1>

        <div id="response"></div>

        <form id="passwordResetForm" action="/api/forgot-password.php" method="post">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Submit</button>
        </form>

        <?php include("../includes/components/footer.php"); ?>
    </body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('passwordResetForm');
        const responseDiv = document.getElementById('response');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch('/api/forgot-password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    responseDiv.innerHTML = `<div class="success">${data.success}</div>`;
                }
                else {
                    responseDiv.innerHTML = `<div class="error">${data.error}</div>`;
                }
            })
            .catch(error => {
                responseDiv.innerHTML = `<div class="error">Error: ${error.message}</div>`;
            });
        });
    });
</script>
