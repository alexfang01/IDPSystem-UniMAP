<?php
$uploadDir = 'excelUpload/';
$uploadedFiles = scandir($uploadDir);
$response = [];

foreach($uploadedFiles as $file) {
    if($file != '.' && $file != '..') {
        $response[] = $file;
    }
}

echo json_encode($response);
?>
