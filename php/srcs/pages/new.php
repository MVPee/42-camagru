<?php
    session_start();
    require_once("../includes/database.php");
    if (!isset($_SESSION["user"])) {
        header("Location: /login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <title>Camagru - New</title>
    </head>
    <body>
        <?php include("../includes/components/navbar.php"); ?>

        <h1>New</h1>

        <div>
            <img src="/rsrcs/sock.png" width="255px" height="255px" alt="sock" class="sticker" onclick="activateSticker(this)">
            <img src="/rsrcs/sock.png" width="255px" height="255px" alt="sock" class="sticker" onclick="activateSticker(this)">
        </div>

        <style>
            .sticker {
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.3s;
            }
            .sticker.active {
            border-color: red;
            }
        </style>

        <div>
            <video id="video" width="640" height="480" autoplay></video>
            <button id="snap">Snap Photo</button><br>
            <canvas id="canvas0" width="640" height="480"></canvas>
            <canvas id="canvas1" width="640" height="480"></canvas>
            <canvas id="canvas2" width="640" height="480"></canvas>
            <canvas id="canvas3" width="640" height="480"></canvas>
        </div>


        <?php include("../includes/components/footer.php"); ?>
    </body>
</html>

<script>

    function activateSticker(element) {
        var stickers = document.querySelectorAll('.sticker');
        stickers.forEach(function(sticker) {
            sticker.classList.remove('active');
        });
        element.classList.add('active');
    }

    // Access the user's camera
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(stream) {
            var video = document.getElementById('video');
            video.srcObject = stream;
            video.play();
        })
        .catch(function(err) {
            console.log("An error occurred: " + err);
        });

    var snapCount = 0;
    const maxSnaps = 4;

    // Capture the photo
    document.getElementById('snap').addEventListener('click', function() {
        if (snapCount >= maxSnaps) {
            snapCount = 0;
        }
        if (snapCount < maxSnaps) {
            var canvas = document.getElementById('canvas' + snapCount);
            var context = canvas.getContext('2d');
            var video = document.getElementById('video');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            var activeSticker = document.querySelector('.sticker.active');
            if (activeSticker) {
                var stickerImage = new Image();
                stickerImage.src = activeSticker.src;
                stickerImage.onload = function() {
                    context.drawImage(stickerImage, 0, 0, canvas.width, canvas.height);
                };
            }
            snapCount++;
        }
    });
</script>
