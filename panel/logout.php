<?php
    // Clear any existing session variables
    session_start();
    session_unset();
    session_destroy();

    // Check if session is really destroyed
    if(session_status() === PHP_SESSION_NONE) {
        // Session is destroyed, redirect to login page
        echo "<script>alert('Logout Successfully');</script>";
        echo "<script>window.location.href = '../login.php';</script>";
        exit();
    } else {
        // Session destruction failed, show an error message
        echo "<script>alert('Logout Failed');</script>";
        echo "<script>window.location.href = '../login.php';</script>";
        exit();
    }
?>