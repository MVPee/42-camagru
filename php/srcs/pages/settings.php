<?php
    session_start();
    require_once("../includes/database.php");
    if (!isset($_SESSION['user'])) {
        header('Location: /login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>Camagru - Settings</title>
</head>
<body>
    <?php include("../includes/components/navbar.php"); ?>

    <h1>Settings</h1>

    <div id="response"></div>

    <form id="settingsForm" action="/api/settings.php" method="post">
        <input type="text" id="username" name="username" placeholder="Username" value="<?php echo $_SESSION['user']['username']; ?>" required>
        <input type="email" id="email" name="email" placeholder="Email" value="<?php echo $_SESSION['user']['email']; ?>" required>
        <input type="password" id="password" name="password" placeholder="Password">
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password">
        <label for="notifications">Enable Notifications</label>
        <input type="checkbox" id="notifications" name="notifications" <?php echo $_SESSION['user']['notifications'] ? 'checked' : ''; ?>>
        <button type="submit">Save</button>
    </form>

    <?php include("../includes/components/footer.php"); ?>
</body>
</html>

<script>
    form = document.getElementById('settingsForm');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);

        const response = await fetch('/api/settings.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        if (data.error) {
            document.getElementById('response').innerHTML = data.error;
        } else {
            document.getElementById('response').innerHTML = 'Settings saved successfully.';
        }
    });
</script>