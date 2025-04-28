<?php 
include('../config/db_connect.php');
include './logincheckComm.php';
include './sidebarComm.php';

date_default_timezone_set('Asia/Singapore'); // ensure it is GMT +8
// save the submit time into the database
$current_date_time = new DateTime();
$current_date_time_string = $current_date_time->format('m/d/Y g:i A');
$submitTime = mysqli_real_escape_string($conn, $current_date_time_string);



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // retrieve the filetype
    $rubricType = mysqli_real_escape_string($conn, $_POST['rubricType']);


    // upload file logic
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        
        $target_dir = "../fileuploaded/rubrics/"; // Change this to the desired directory for uploaded files
        //save filename with the grpid and the context
        $originalFileName = basename($_FILES["file"]["name"]);
        $file_extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $filename = "rubric_" . $rubricType .".".$file_extension;

        // change the filename
        $target_file = $target_dir . $filename;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is allowed (you can modify this to allow specific file types)
        // $allowed_types = array("jpg", "jpeg", "png", "gif", "pdf");
        $allowed_types = array("pdf");
        if (!in_array($file_type, $allowed_types)) {
            echo "Sorry, only PDF files are allowed.";
        } else {
            
            // check whether the file got same type or not
            $filename = mysqli_real_escape_string($conn, $filename);
            $sql = "SELECT * FROM rubricfile WHERE filename='$filename'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0){
                echo "<script>alert('Please delete the old file first');</script>";
                echo "<script>window.location.href = 'rubricUpload.php';</script>";
            }else{
                // Move the uploaded file to the specified directory
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                    // File upload success, now store information in the database
                    $filesize = $_FILES["file"]["size"];
                    $filetype = $_FILES["file"]["type"];
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                }

                // Insert the file information into the database
                $sql = "INSERT INTO rubricfile (filename, filesize, filetype, submitTime) VALUES ('$filename', $filesize, '$filetype', '$submitTime')";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('File has uploaded');</script>";
                    echo "<script>window.location.href = 'rubricUpload.php';</script>";
                } else {
                    echo "<script>alert('Sorry, there was an error uploading your file and storing information in the database');</script>";
                    echo "<script>window.location.href = 'rubricUpload.php';</script>";
                }
            }
            
        }   

        
    }
}
if(isset($result)){
    mysqli_free_result($resultFile);
}

mysqli_close($conn);
?>

