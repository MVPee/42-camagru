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

        <div id="response"></div>

        <div>
            <img src="/rsrcs/transparent.png" alt="transparent" class="sticker active" onclick="activateSticker(this)">
            <img src="/rsrcs/sock.png" alt="sock" class="sticker" onclick="activateSticker(this)">
        </div>

        <div id="camera">
            <video id="video" autoplay></video>
            <img src="/rsrcs/transparent.png" id="overlay-sticker" alt="sticker">
        </div>
        <input type="file" id="imageUpload" accept="image/*" onchange="useUploadedFile(this)">
        <button id="snap">Snap Photo</button><br>
        
        <div id="canvas-container">
            <form id="publicationForm0">
                <canvas for="publication" class="canva" id="canvas0"></canvas>
            </form>
            <form id="publicationForm1">
                <canvas for="publication" class="canva" id="canvas1"></canvas>
            </form>
            <form id="publicationForm2">
                <canvas for="publication" class="canva" id="canvas2"></canvas>
            </form>
            <form id="publicationForm3">
                <canvas for="publication" class="canva" id="canvas3"></canvas>
            </form>
        </div>

        <?php include("../includes/components/footer.php"); ?>
    </body>
</html>

<style>
    .sticker {
        cursor: pointer;
        border: 2px solid transparent;
        width: 255px;
        height: 255px;
    }
    .sticker.active {
        border-color: red;
    }
    #camera {
        position: relative;
        width: 100%;
        height: auto;
    }
    #video {
        display: block;
        align-items: center;
        width: 100%;
        max-width: 800px;
        height: 100%;
    }
    #overlay-sticker {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        max-width: 800px;
        height: 100%;
        pointer-events: none;
        z-index: 10;
    }
    .canva {
        width: 40%;
        max-width: 400px;
        height: auto;
    }
</style>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        const responseDiv = document.getElementById('response');

        for(let i = 0; i < 4; i++) {
            let formElement = document.getElementById('publicationForm' + i);
            let canvas = document.getElementById('canvas' + i);

            formElement.addEventListener('submit', function(e) {
                e.preventDefault();
                const image = canvas.toDataURL('image/png');
                const formData = new FormData();
                formData.append('publication', image);
                console.log(i);

                fetch('/api/new.php', {
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
        }
    });

    function useWebcam() {
        camera = document.getElementById('camera');
        camera.innerHTML = 
        `
            <video id="video" autoplay></video>
            <img src="/rsrcs/transparent.png" id="overlay-sticker" alt="sticker">
        `;
        activateCamera();
        activateSticker(document.querySelector('.sticker.active'));
    }

    function useUploadedFile(input) {
        if (input.files && input.files[0]) {
            document.getElementById('camera').innerHTML = '<img id="video" src="' + URL.createObjectURL(input.files[0]) + '">';
            document.getElementById('camera').innerHTML += '<img src="/rsrcs/transparent.png" id="overlay-sticker" alt="sticker">';
            document.getElementById('camera').innerHTML += '<button type="button" onclick="useWebcam()">X</button>';
            activateSticker(document.querySelector('.sticker.active'));
        }
    }

    function activateSticker(element) {
        const stickers = document.querySelectorAll('.sticker');
        stickers.forEach(function(sticker) {
            sticker.classList.remove('active');
        });
        element.classList.add('active');
        overlay_sticker = document.getElementById('overlay-sticker');
        overlay_sticker.src = element.src;
    }

    // Capture the photo
    let snapCount = 0;
    const maxSnaps = 4;
    document.getElementById('snap').addEventListener('click', function() {
        if (snapCount >= maxSnaps) {
            snapCount = 0;
        }
        if (snapCount < maxSnaps) {
            const canva_container = document.getElementById('publicationForm' + snapCount);

            canva_container.innerHTML = '<canvas for="publication" class="canva" id="canvas' + snapCount + '"></canvas>';
            canva_container.innerHTML += `<button type="submit">V</button>`;

            let canvas = document.getElementById('canvas' + snapCount);
            let context = canvas.getContext('2d');
            let video = document.getElementById('video');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            let activeSticker = document.querySelector('.sticker.active');
            if (activeSticker) {
                let stickerImage = new Image();
                stickerImage.src = activeSticker.src;
                stickerImage.onload = function() {
                    context.drawImage(stickerImage, 0, 0, canvas.width, canvas.height);
                };
            }
            snapCount++;
        }
    });

    // Access the user's camera
    function activateCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(stream) {
            let video = document.getElementById('video');
            video.srcObject = stream;
            video.play();
        })
        .catch(function(err) {
            console.log("An error occurred: " + err);
        });
    }

    activateCamera();
</script>
