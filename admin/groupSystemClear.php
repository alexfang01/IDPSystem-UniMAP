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

if (isset($_SESSION['groupping']) && $_SESSION['groupping'] !== null) {
    unset($_SESSION['groupping']);
}
if (isset($_SESSION['arygrp']) && $_SESSION['arygrp'] !== null) {
    unset($_SESSION['arygrp']);
}

if (isset($_SESSION['allgroup']) && $_SESSION['allgroup'] !== null) {
    unset($_SESSION['allgroup']);
}
if (isset($_SESSION['totalgrp']) && $_SESSION['totalgrp'] !== null) {
    unset($_SESSION['totalgrp']);
}

if (isset($_SESSION['avgstud']) && $_SESSION['avgstud'] !== null) {
    unset($_SESSION['avgstud']);
}

if (isset($_SESSION['student1']) && $_SESSION['student1'] !== null) {
    unset($_SESSION['student1']);
}
if (isset($_SESSION['student2']) && $_SESSION['student2'] !== null) {
    unset($_SESSION['student2']);
}
if (isset($_SESSION['student3']) && $_SESSION['student3'] !== null) {
    unset($_SESSION['student3']);
}
if (isset($_SESSION['student4']) && $_SESSION['student4'] !== null) {
    unset($_SESSION['student4']);
}

if (isset($_SESSION['sv']) && $_SESSION['sv'] !== null) {
    unset($_SESSION['sv']);
}
if (isset($_SESSION['theme']) && $_SESSION['theme'] !== null) {
    unset($_SESSION['theme']);
}
if (isset($_SESSION['counter']) && $_SESSION['counter'] !== null) {
    unset($_SESSION['counter']);
}

header("Location: ./groupSystemFetch.php");
