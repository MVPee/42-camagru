<div id="publication_form">
    <h2>Create a new Publication</h2>
    <?php
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['id']; // Make sure the user ID is available
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $imageData = $_POST['image_data']; // Base64 encoded image
        
            // Remove "data:image/png;base64," part of the string
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
        
            // Decode the Base64 string
            $imageData = base64_decode($imageData);
        
            // Generate a unique filename
            $fileName = 'uploads/' . uniqid('img_', true) . '.png';
        
            // Save the image to the server
            file_put_contents($fileName, $imageData);
        
            // Insert image info into the database
            $query = "INSERT INTO images (userId, path, description) VALUES ('$userId', '$fileName', '$description')";
            if (mysqli_query($conn, $query)) {
                echo "<script>window.location.href = '/profile';</script>";
                exit();
            }
            else
                echo "Error: " . mysqli_error($conn);
        }

    ?>
    <form action="/new" method="post" enctype="multipart/form-data">
        <div id="webcam_container">
            <video id="webcam" autoplay></video>
            <canvas id="snapshot" style="display:none;"></canvas> 
        </div>
        <input type="text" name="description" id="description" placeholder="Description" style="display:none;" required>
        <input type="hidden" name="image_data" id="image_data">
        <button type="button" id="take_snapshot">Take Snapshot</button>
        <button type="submit" id="submit_button" style="display:none;">Send</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const video = document.getElementById("webcam");
        const canvas = document.getElementById("snapshot");
        const submitButton = document.getElementById("submit_button");
        const takeSnapshotButton = document.getElementById("take_snapshot");
        const description = document.getElementById("description");
        const imageDataInput = document.getElementById("image_data");

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error("Error accessing webcam:", err);
            });

        takeSnapshotButton.addEventListener("click", () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            // Convert the canvas image to Base64
            const imageData = canvas.toDataURL("image/png");

            // Set the hidden input field with the image data
            imageDataInput.value = imageData;

            submitButton.style.display = 'inline-block';
            canvas.style.display = 'inline-block';
            takeSnapshotButton.style.display = 'none';
            video.style.display = 'none';
            description.style.display = 'inline';
            canvas.style.maxWidth = `${canvas.width}px`;
        });
    });
</script>

<style>

    #publication_form {
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    #webcam_container {
        width: 100%;
    }

    #webcam, #snapshot {
        width: 90%;
        height: auto;
        max-height: 600px;
        transform: scaleX(-1);
    }
</style>
