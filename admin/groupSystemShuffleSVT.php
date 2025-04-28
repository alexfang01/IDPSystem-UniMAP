<?php
if (!isset($_SESSION)) {
    session_start();
    $host = "localhost"; // Your MySQL server hostname
    $user = "root";   // Your MySQL username
    $pass = ""; // Your MySQL password
    $db = "idpsystem";       // Your MySQL database name

    // Create a connection
    $conn = mysqli_connect($host, $user, $pass, $db);

    // Check if the connection was successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
}

if (isset($_SESSION['sv']) && $_SESSION['sv'] !== null) {
    $sv = $_SESSION['sv'];
}

//shuffle
if (isset($_SESSION['sv']) && $_SESSION['sv'] !== null) {
    shuffle($sv);
    $_SESSION['sv'] = $sv;
}

header("Location: ./groupSystem.php");
?>