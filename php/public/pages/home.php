<script>
    function like(event, imageId) {
    event.preventDefault();

    fetch('like.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `imageId=${encodeURIComponent(imageId)}`
    });
}
</script>

<?php
    $query = "
        SELECT 
            images.*, 
            users.username, 
            COUNT(likes.id) AS like_count 
        FROM images
        JOIN users ON images.userId = users.id
        LEFT JOIN likes ON images.id = likes.imageId
        GROUP BY images.id, users.username
        ORDER BY images.created_at DESC
    ";
    $result = mysqli_query($conn, $query);

    echo "<div id=home_container>";
    echo "<h1>Welcome to Camagru</h1>";
    if (mysqli_num_rows($result) > 0) {
        while ($images_row = mysqli_fetch_assoc($result)) {
            echo "<span class=\"bar\"></span><br>";
            echo "<p><a href='/profile?profile=" . $images_row['username'] . "'>" . $images_row['username'] . "</a> " . $images_row['created_at'] . "</p>";
            echo "<img class='all_publications' src='/" . $images_row['path'] . 
            "' onerror='this.style.display=\"none\"; this.insertAdjacentHTML(\"afterend\", 
            \"<p>Image not found.<br>Contact an administator.</p>\");'><br>";
            echo "<button onclick='like(event, " . $images_row['id'] . ")'>Like</button> <span>" . $images_row['like_count'] . "</span><br>";
            echo htmlspecialchars($images_row['description'], ENT_QUOTES, 'UTF-8') . "<br>";
        }
    }
    echo "</div>";
?>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 }); // Set the threshold to 50% visibility

        // Observe each element with the 'bar' class
        document.querySelectorAll('.bar').forEach(bar => {
            observer.observe(bar);
        });

        // Observe each image as well
        document.querySelectorAll('.all_publications').forEach(image => {
            observer.observe(image);
        });
    });
</script>

<style>
    .bar, .all_publications {
        opacity: 0;
        transform: translateY(0px);
        transition: opacity 1s ease, transform 1s ease;
    }

    .bar.visible, .all_publications.visible {
        opacity: 1;
    }

    .bar {
        width: 90%;
        max-width: 500px;
        height: 20px;
        background-color: #e0e0e0;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        margin-top: 15px; 
    }

    #home_container {
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

    .all_publications {
        width: 90%;
        height: auto;
        max-height: 300px;
        margin: 20 auto; /* Ensures the image is centered horizontally */
        display: block; /* Ensures the image behaves as a block-level element */
        object-fit: contain;
        transform: scaleX(-1);
    }
<style>