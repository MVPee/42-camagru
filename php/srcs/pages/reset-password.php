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
        <title>Camagru - Reset</title>
    </head>
    <body>
        <?php include("../includes/components/navbar.php"); ?>

        <h1>Reset password</h1>

        <div id="response"></div>

        <form id="passwordResetForm" action="/api/reset-password.php" method="post">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <label for="confirmPassword">Confirm password</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET["token"], ENT_QUOTES, 'UTF-8'); ?>">
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

            fetch('/api/reset-password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                console.log(response);
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
