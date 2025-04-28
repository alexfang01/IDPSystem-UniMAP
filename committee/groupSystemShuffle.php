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
    $groupping = $_SESSION['groupping'];
}
if (isset($_SESSION['avgstud']) && $_SESSION['avgstud'] !== null) {
    $avgstud = $_SESSION['avgstud'];
}

if (isset($_SESSION['student1']) && $_SESSION['student1'] !== null) {
    $student1 = $_SESSION['student1'];
}
if (isset($_SESSION['student2']) && $_SESSION['student2'] !== null) {
    $student2 = $_SESSION['student2'];
}
if (isset($_SESSION['student3']) && $_SESSION['student3'] !== null) {
    $student3 = $_SESSION['student3'];
}
if (isset($_SESSION['student4']) && $_SESSION['student4'] !== null) {
    $student4 = $_SESSION['student4'];
}

//shuffle
if (isset($_SESSION['groupping']) && $_SESSION['groupping'] !== null) {
    shuffle($student1);
    shuffle($student2);
    shuffle($student3);
    if (isset($student4)) {
        shuffle($student4);
    }
    $_SESSION['student1'] = $student1;
    $_SESSION['student2'] = $student2;
    $_SESSION['student3'] = $student3;
    $_SESSION['student4'] = $student4;

    for ($j = 0; $j <= ($avgstud - 1); $j++) {
        if (empty($student4[$j])) {
            $groupping[$j] = array($student1[$j], $student2[$j], $student3[$j]);
        } else {
            $groupping[$j] = array($student1[$j], $student2[$j], $student3[$j], $student4[$j]);

        }
    }    

    $_SESSION['groupping'] = $groupping;
}


header("Location: ./groupSystem.php");
?>