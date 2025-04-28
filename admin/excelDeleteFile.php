
<?php
include '../config/db_connect.php';
include 'excelManageFunc.php';

// Check if the file parameter is set
if (isset($_GET['file'])) {
    $filename = $_GET['file'];
    deleteFile($filename); // Call the deleteFile function
} else {
    $_SESSION['message'] = "Error: File parameter not specified.";
    header("Location: excelManage.php");
    exit();
}
?>
