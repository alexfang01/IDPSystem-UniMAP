<?php
include('../config/db_connect.php');

session_start();

$notifications = [];

// Query student table
$query = "SELECT name, verify FROM student WHERE notify = 1";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $message = 'Student ' . $row['name'] . ' is ' . ($row['verify'] ? 'verified successfully.' : 'now unverified.');
    array_unshift($notifications, ['message' => $message]);
}

// Query supervisor table
$query = "SELECT name, verify FROM supervisor WHERE notify = 1";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $message = 'Supervisor ' . $row['name'] . ' is ' . ($row['verify'] ? 'verified successfully.' : 'now unverified.');
    array_unshift($notifications, ['message' => $message]);
}

// Query committee table
$query = "SELECT name, verify FROM committee WHERE notify = 1";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $message = 'Committee ' . $row['name'] . ' is ' . ($row['verify'] ? 'verified successfully.' : 'now unverified.');
    array_unshift($notifications, ['message' => $message]);
}

echo json_encode($notifications);
?>
