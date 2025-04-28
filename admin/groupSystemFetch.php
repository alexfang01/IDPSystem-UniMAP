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

// Fetch total grouped student
//SELECT COUNT(DISTINCT student.grpid) as total_grp FROM student WHERE verify = '1' AND student.grpid IS NOT NULL
$sql = "SELECT COUNT(DISTINCT idpgroup.grpid) as total_grp FROM idpgroup WHERE idpgroup.active = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$stmt->bind_result($totalgrp);
$stmt->fetch();
$stmt->close();
$_SESSION['totalgrp'] = $totalgrp;

// Fetch total number student
$sql = "SELECT COUNT(*) as total_students FROM student WHERE verify = '1' AND grpid IS NULL";
$stmt = $conn->prepare($sql);
$stmt->execute();
$stmt->bind_result($totalstu);
$stmt->fetch();
$stmt->close();
$avgstud = ceil($totalstu / 4);
$_SESSION['avgstud'] = $avgstud;


// Fetch all group number
$sql = "SELECT DISTINCT idpgroup.grpnum FROM student INNER JOIN idpgroup ON student.grpid = idpgroup.grpid WHERE active = '1' AND student.grpid IS NOT NULL";
$result = $conn->query($sql);
$allgroup = [];
if ($result->num_rows > 0) {
    while (($row = $result->fetch_assoc())) {
        $allgroup[] = $row;
    }
    foreach ($allgroup as $element) {
        $allgroup2[] = $element["grpnum"]; // Extracting grpnum value and storing in test array
    }
}
if (isset($allgroup2)) {
    $_SESSION['allgroup'] = $allgroup2;
}


// Fetch all verify supervisor 
$sql = "SELECT * FROM supervisor WHERE verify = '1' AND grpid IS NULL";
$result = $conn->query($sql);
$sv = [];
if ($result->num_rows > 0) {
    while (($row = $result->fetch_assoc())) {
        $sv[] = $row;
    }
}
$counterz = '0';
foreach ($sv as $a):
    // Fetch Theme
    $y0 = $a['y0'];
    $sql = "SELECT title FROM theme WHERE themeid = '$y0' ";
    $result = $conn->query($sql);
    $theme = $result->fetch_assoc();
    if ($theme != NULL) {
        $sv[$counterz]['y00'] = $theme['title'];
    } else {
        $sv[$counterz]['y00'] = '';
    }
    $counterz++;
endforeach;

$_SESSION['sv'] = $sv;


//Fetch Ungroupped students
$sql = "SELECT * FROM student WHERE verify = '1' AND grpid IS NULL ORDER BY cgpa DESC";
$result = $conn->query($sql);
$student = [];

if ($result->num_rows > 0) {
    $i = 1;
    while (($row = $result->fetch_assoc())) {
        if ($i <= $avgstud) {
            $student1[] = $row;
            $i++;
        } else if ($i <= ($avgstud * 2)) {
            $student2[] = $row;
            $i++;
        } else if ($i <= ($avgstud * 3)) {
            $student3[] = $row;
            $i++;
        } else if ($i <= ($avgstud * 4)) {
            $student4[] = $row;
            $i++;
        }
    }
    $_SESSION['student1'] = $student1;
    $_SESSION['student2'] = $student2;
    $_SESSION['student3'] = $student3;
    $_SESSION['student4'] = $student4;

    //grouping
    for ($j = 0; $j <= ($avgstud - 1); $j++) {
        if (empty($student4[$j])) {
            $groupping[$j] = array($student1[$j], $student2[$j], $student3[$j]);
        } else {
            $groupping[$j] = array($student1[$j], $student2[$j], $student3[$j], $student4[$j]);

        }

    }
    $_SESSION['groupping'] = $groupping;
}

// Fetch notifications (if any)
if (isset($_SESSION['notifications']) && !empty($_SESSION['notifications'])) {
    $notifications = $_SESSION['notifications'];
} else {
    $notifications = [];
}

header("Location: ./groupSystem.php");
exit;

?>