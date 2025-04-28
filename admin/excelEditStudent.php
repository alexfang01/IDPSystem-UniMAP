<?php
// Start or resume the session
session_start();
include('../config/db_connect.php');

// Check if the delete_studentid parameter is set
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
if (isset($_POST['studentid'])) {
    // Updating student record
    $studentId = $_POST['studentid'];
    $name = $_POST['name'];
    $ic = $_POST['ic'];
    $matric = $_POST['matric'];
    $prog = $_POST['prog'];
    $race = $_POST['race'];
    $cgpa = $_POST['cgpa'];
    $phnum = $_POST['phnum'];
    $email = $_POST['email'];

    $sql = "UPDATE student SET name=?, ic=?, matric=?, prog=?, race=?, cgpa=?, phnum=?, email=? WHERE studentid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $name, $ic, $matric, $prog, $race, $cgpa, $phnum, $email, $studentId);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} elseif (isset($_POST['delete_studentid'])) {
    // Deleting student record
    $delete_studentid = mysqli_real_escape_string($conn, $_POST['delete_studentid']);

    $sql = "DELETE FROM student WHERE studentid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_studentid);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
}

$conn->close();
?>
