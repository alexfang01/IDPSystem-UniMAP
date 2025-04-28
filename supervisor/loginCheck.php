<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin'])) {
    echo '<script language="javascript">';
    echo 'alert("Invalid action detected. Please Login to Proceed")';
    echo '</script>';
    header('Refresh:0; url=../login.php');
    exit;
}

if ($_SESSION['userType'] != "Supervisor") {
    echo '<script language="javascript">';
    echo 'alert("Account Not Exist!")';
    echo '</script>';
    header('Refresh:0; url=../login.php');
    exit;
}


?>