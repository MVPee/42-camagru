<nav>
    <ul>
        <li><a href="/">Home</a></li>
        <?php if (isset($_SESSION["username"])) { ?>
            <li><a href="/profile/">Profile</a></li>
            <li><a href="/new/">+</a></li>
            <li><a href="/logout/">Logout</a></li>
        <?php } else { ?>
            <li><a href="/pages/login.php">Login</a></li>
            <li><a href="/pages/register.php">Register</a></li>
        <?php } ?>
    </ul>
</nav>
<hr>