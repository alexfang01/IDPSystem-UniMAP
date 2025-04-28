<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('../config/db_connect.php');
// credential check
include('./loginCheck.php');

// Getting user's information
$studentid = $_SESSION['id'];
$sqlUser = "SELECT * FROM student WHERE studentid = '$studentid'";
$resultUser = mysqli_query($conn, $sqlUser);
$infoUser = mysqli_fetch_all($resultUser, MYSQLI_ASSOC); // convert to associate array
mysqli_free_result($resultUser);

$flag_approved = 0;
$flag_submitted = 0;
$flag_nogroup = 1; // if got group then allow normal form submission, else disable like approved case

if(isset($_SESSION['approved'])){
    $flag_approved = $_SESSION['approved'];
}
if(isset($_SESSION['submitted'])){
    $flag_submitted = $_SESSION['submitted'];
}
if(isset($_SESSION['context'])){
    $contextTemp = $_SESSION['context'];
    $context = mysqli_real_escape_string($conn, $contextTemp);
}

date_default_timezone_set('Asia/Singapore'); // ensure it is GMT +8
// save the submit time into the database
$current_date_time = new DateTime();
$current_date_time_string = $current_date_time->format('m/d/Y g:i A');
$submitTime = mysqli_real_escape_string($conn, $current_date_time_string);

if($infoUser[0]['grpid'] != ""){
    $flag_nogroup = 0;
    $grpidTemp = $infoUser[0]['grpid'];
    $grpid = mysqli_real_escape_string($conn, $grpidTemp);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if a file was uploaded without errors
        // the name of file = "file", make sure you check the name and id attribute
    
            // insert video link in database, it's ok if the file is not uploaded
        if($contextTemp === 'vidposter'){
            
            if(!$flag_nogroup){
                // getting video link info, if submitted, it will update the data, else insert new data
                $sql = "SELECT * FROM videolink WHERE grpid ='$grpid'";
                $result = mysqli_query($conn, $sql);
        
                if(isset($_POST['vidlink'])){
                    $videolink = mysqli_real_escape_string($conn, $_POST['vidlink']);
        
                    if(mysqli_num_rows($result) > 0){
                        $sql = "UPDATE videolink set videolink='$videolink', submitTime='$submitTime'  WHERE grpid = '$grpid'";
                        if(mysqli_query($conn, $sql)){
                            //success to save
                            echo "<script>alert('Video link Updated');</script>";
                        }else{
                            echo 'query error'.mysqli_error($conn);
                        }
                    }else{ // new record
                        $sql = "INSERT INTO videolink (videolink, submitted, grpid, submitTime) VALUES ('$videolink', 1, '$grpid', '$submitTime')";
                        if(mysqli_query($conn, $sql)){
                            //success to save
                            echo "<script>alert('Video link Saved');</script>";
                        }else{
                            echo 'query error'.mysqli_error($conn);
                        }
                        
                    }
                }
                mysqli_free_result($result);
            }
            
        
        }
        
        
    
        if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    
            if(!$flag_nogroup){
                if(!$flag_approved){
                    if(!$flag_submitted){
        
                        $target_dir = "../fileuploaded/".$contextTemp."/"; // Change this to the desired directory for uploaded files
                        //save filename with the grpid and the context
                        $target_file = $target_dir.$grpid.$contextTemp.basename($_FILES["file"]["name"]);
                        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
                        // Check if the file is allowed (you can modify this to allow specific file types)
                        // $allowed_types = array("jpg", "jpeg", "png", "gif", "pdf");
                        $allowed_types = array("pdf");
                        if (!in_array($file_type, $allowed_types)) {
                            echo "Sorry, only PDF files are allowed.";
                        } else {
                            // Move the uploaded file to the specified directory
                            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                                // File upload success, now store information in the database
                                $filename = $_FILES["file"]["name"];
                                $filesize = $_FILES["file"]["size"];
                                $filetype = $_FILES["file"]["type"];
        
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }
        
                                // Insert the file information into the database
                                $sql = "INSERT INTO idpfile (filename, filesize, filetype, type, approved, submitted, grpid, submitTime) VALUES ('$filename', $filesize, '$filetype', '$context',0,1,'$grpid','$submitTime')";
        
                                if ($conn->query($sql) === TRUE) {
                                    echo "<script>alert('File has uploaded');</script>";
        
                                } else {
                                    echo "<script>alert('Sorry, there was an error uploading your file and storing information in the database');</script>";
                                }
        
                            } else {
                                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
                            }
                        }
                    }else{
                        echo "<script>alert('Only accept 1 files, Please delete previous file to proceed to upload');</script>";
                    }
                    
                }else{
                    echo "<script>alert('Proposal approved, unable to perform upload');</script>";
                }
            }
            
            
        } else {
            echo "<script>alert('No file was uploaded.');</script>";
            
        }
        echo "<script>window.location.href = '".$contextTemp.".php';</script>";
    }
}




mysqli_close($conn);
?>

