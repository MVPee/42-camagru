<?php

    // Parse the requested URI
    $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    // Route the URI to the appropriate file
    switch ($uri) {
        case '':
        case 'home':
        case 'index.php':
            $title = "Home";
            $page = "home.php";
            break;
        case 'login':
            $title = "Login";
            $page = "login.php";
            break;
        case 'register':
            $title = "Register";
            $page = "register.php";
            break;
        case 'logout':
            session_destroy();
            header("Location: /login");
            exit();
            break;
        case 'profile':
            if (!isset($_SESSION['username']) || !isset($_SESSION['id'])) {
                header("Location: /login");
                exit();
            }
            $title = "Profile";
            $page = "profile.php";
            break;
        default:
            $title = "404";
            $page = "404.php";
            break;
    }
    $title = ucfirst(str_replace('.php', '', $page));
    $page = "./pages/" . $page;
?>