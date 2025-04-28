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
if (isset($_SESSION['sv']) && $_SESSION['sv'] !== null) {
    $sv = $_SESSION['sv'];
}
if (isset($_SESSION['theme']) && $_SESSION['theme'] !== null) {
    $theme = $_SESSION['theme'];
}
if (isset($_SESSION['counter']) && $_SESSION['counter'] !== null) {
    $counter = $_SESSION['counter'];
}

//fetch insert group    
if (isset($_POST['unlock'])) {
    $group = $_POST['unlock'];
    $sql = "SELECT grpid FROM idpgroup WHERE grpnum = '$group' AND active = '1'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the result
        $row = $result->fetch_assoc();
        $grpid = $row['grpid'];

        // Prepare and bind the SQL statement
        $sql = "UPDATE student SET grpid = NULL WHERE grpid = '$grpid' ";
        if ($conn->query($sql) === TRUE) {
        } else {
            echo "Error modifying column: " . $conn->error;
        }
        $sql = "UPDATE supervisor SET grpid = NULL WHERE grpid = '$grpid' ";
        if ($conn->query($sql) === TRUE) {
        } else {
            echo "Error modifying column: " . $conn->error;
        }
        $sql = "DELETE FROM idpgroup WHERE grpid = '$grpid' AND active = '1' ";
        if ($conn->query($sql) === TRUE) {
        } else {
            echo "GROUP ASSESSMENT STARTED" . $conn->error;
        }

        // Close statement
        $conn->close();
    } else {
        echo "No group available: " . $conn->error;
    }

header("Location: ./groupSystemClear.php");
}


?>