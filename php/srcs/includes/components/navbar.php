<nav>
    <ul>
        <li><a href="/">Home</a></li>
        <?php if (isset($_SESSION["user"])) { ?>
            <li><a href="/new/">+</a></li>
            <li><a href="/api/logout.php">Logout</a></li>
        <?php } else { ?>
            <li><a href="/login.php">Login</a></li>
            <li><a href="/register.php">Register</a></li>
        <?php } ?>
    </ul>
</nav>
<hr>