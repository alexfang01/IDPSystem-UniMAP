<?php
if (session_status() == PHP_SESSION_NONE) {
        session_start();
}

if (!isset($_SESSION['loggedin'])) {
        echo '<script language="javascript">';
        echo 'alert("Please Login First!")';
        echo '</script>';
        header('Refresh:0; url=../index.php');
        exit;
}

if ($_SESSION['userType'] != "Committee") {
        echo '<script language="javascript">';
        echo 'alert("You are not Committee!")';
        echo '</script>';
        header('Refresh:0; url=../index.php');
        exit;
}

?>