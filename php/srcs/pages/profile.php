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
    <title>Camagru - Profile</title>
</head>
<body>
    <?php include("../includes/components/navbar.php"); ?>

    <h1>Profile</h1>
    <h3><a href="/settings.php">Settings</a></h3>
    <?php
        $query = $conn->prepare("SELECT * FROM images WHERE userId = ?");
        $query->bind_param("i", $_SESSION['user']['id']);
        $query->execute();
        $result = $query->get_result();
        $images = $result->fetch_all(MYSQLI_ASSOC);
    ?>
    <div id="images">
        <?php if (empty($images)) { ?>
            <p>You don't have any publication.</p>
        <?php } ?>
        <?php foreach ($images as $image) { ?>
            <div class="image">
                <img src="<?php echo $image['path']; ?>" alt="<?php echo $image['description']; ?>">
                <p><?php echo $image['description']; ?></p>
                <form>
                    <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                    <button type="submit" name="delete_image">Delete</button>
                </form>
            </div>
        <?php } ?>
    </div>

    <?php include("../includes/components/footer.php"); ?>
</body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const images = document.getElementById('images');

        images.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!confirm('Are you sure you want to delete this image?'))
                return;

            const formData = new FormData(e.target);
            formData.append('image_id', formData.get('image_id'));

            fetch('/api/delete.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    e.target.closest('.image').remove();
                    document.location.reload();
                }
                else
                    console.log(data.error);
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
        });
    });
</script>