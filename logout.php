<?php
    session_start();

    // Destroy all session
    session_destroy();

    // Redirect to login page
    header("Location: login.php");
    exit();
?>