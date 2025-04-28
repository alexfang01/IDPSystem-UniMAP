<?php
// Check if the file parameter is set for download
if(isset($_GET['file'])) {
    $filename = $_GET['file'];
    
    // Path to the file
    $filePath = 'excelUpload/' . $filename;
    
    // Check if the file exists
    if(file_exists($filePath)) {
        // Set headers for file download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filePath));
        
        // Read the file and output it to the browser
        readfile($filePath);
        exit();
    } else {
        $_SESSION['message'] = "Error: File '$filename' does not exist.";
    }

    // Redirect back to the page after attempting download
    header("Location: excelManage.php");
    exit();
} else {
    $_SESSION['message'] = "Error: File parameter not specified.";
}
?>