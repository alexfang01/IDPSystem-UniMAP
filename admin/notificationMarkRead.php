<?php
include('../config/db_connect.php');

session_start();

$response = ['status' => 'success'];
$errors = [];

$updateQueries = [
    "UPDATE student SET notify = 0 WHERE notify = 1",
    "UPDATE supervisor SET notify = 0 WHERE notify = 1",
    "UPDATE committee SET notify = 0 WHERE notify = 1"
];

foreach ($updateQueries as $query) {
    if (!mysqli_query($conn, $query)) {
        $errors[] = mysqli_error($conn);
    }
}

if (!empty($errors)) {
    $response['status'] = 'error';
    $response['errors'] = $errors;
}

// Close the database connection
mysqli_close($conn);

echo json_encode($response);
?>
