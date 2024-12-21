<?php
    // Query to get user details
    $query = "SELECT * FROM users WHERE username = '" . $_SESSION["username"] . "'";
    $result = mysqli_query($conn, $query);
    $user_row = mysqli_fetch_assoc($result);

    // Query to get images uploaded by the user
    $query = "SELECT * FROM images WHERE userId = " . $user_row['id'] . " ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);
?>

<div id="profile">
    <h1>Profile</h1>
    <p>
        Username: <?=$user_row["username"]?><br>
        Email: <?=$user_row["email"]?><br>
        Member since: <?=$user_row["created_at"]?>
    </p>
</div>

<div id="publications">
    <h2>Your publications</h2>
    <p><?=mysqli_num_rows($result)?></p>
    <?php
        if (mysqli_num_rows($result) > 0) {
            while ($images_row = mysqli_fetch_assoc($result)) {
                echo "<span class=\"bar\"></span><br>";
                echo "<img id='publication_img' src='/" . $images_row['path'] . "' onerror='this.style.display=\"none\"; this.insertAdjacentHTML(\"afterend\", \"<p>Image not found.<br>Contact an administator.</p>\");'><br>";
                echo htmlspecialchars($images_row['description'], ENT_QUOTES, 'UTF-8') . "<br>";
                echo $images_row['created_at'];
            }
        }
        else
            echo "You don't have any publication for now.<br>";
    ?>
</div>



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
        document.querySelectorAll('#publication_img').forEach(image => {
            observer.observe(image);
        });
    });
</script>

<style>

    .bar, #publication_img {
        opacity: 0;
        transform: translateY(0px);
        transition: opacity 1s ease, transform 1s ease;
    }

    /* Add this class when elements are in view */
    .bar.visible, #publication_img.visible {
        opacity: 1; /* Make visible */
    }

    .bar {
        width: 90%;
        max-width: 500px;
        height: 20px;
        background-color: #e0e0e0;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        margin: 20px; 
    }

    #profile, #publication_form, #publications {
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

    #publication_img {
        width: 90%;
        height: auto;
        max-height: 300px;
        margin: 20 auto; /* Ensures the image is centered horizontally */
        display: block; /* Ensures the image behaves as a block-level element */
        object-fit: contain;
        transform: scaleX(-1);
    }

</style>
