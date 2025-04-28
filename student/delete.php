<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// to delete the files of proposal and final report
include("../config/db_connect.php");

// credential check
include('./loginCheck.php');

$flag_nogroup = 1; // if got group then allow normal form submission, else disable like approved case

// Getting user's information
$studentid = $_SESSION['id'];
$sqlUser = "SELECT * FROM student WHERE studentid = '$studentid'";
$resultUser = mysqli_query($conn, $sqlUser);
$infoUser = mysqli_fetch_all($resultUser, MYSQLI_ASSOC); // convert to associate array
mysqli_free_result($resultUser);


if(isset($_SESSION['approved'])){
    $flag_approved = $_SESSION['approved'];
}
if(isset($_SESSION['context'])){
    $contextTemp = $_SESSION['context'];
    $context = mysqli_real_escape_string($conn, $contextTemp);
}

if($infoUser[0]['grpid'] != ""){
    $flag_nogroup = 0;
    $grpidTemp = $infoUser[0]['grpid'];
    $grpid = mysqli_real_escape_string($conn, $grpidTemp);

    if(isset($_POST['delete'])){

        // perform delete action
        if($contextTemp === 'proposal' || $contextTemp === 'finalreport' || $contextTemp === 'vidposter'){
            if(!$flag_nogroup){
                if(!$flag_approved){
                
                    // unlink the uploaded file
                    $sql = "SELECT filename FROM idpfile WHERE type = '$context' AND grpid = '$grpid'";
                    $resultFilename = mysqli_query($conn, $sql);
                    $infoFilename = mysqli_fetch_assoc($resultFilename);
        
                    if(mysqli_num_rows($resultFilename) > 0){
                        $target_dir = "../fileuploaded/".$contextTemp."/"; // Change this to the desired directory for uploaded files
                        $target_file = $target_dir.$grpid.$contextTemp. basename($infoFilename['filename']);
        
        
                        // delete the file info in db
                        $sqlDelete = "DELETE FROM idpfile WHERE type = '$context' AND grpid = '$grpid'";
        
                        // unlink first to ensure the db still have the file info
                        if (file_exists($target_file)) {
                            unlink($target_file);
                            if(mysqli_query($conn, $sqlDelete)){
                                //success to save
                                echo "<script>alert('File has successfully deleted');</script>";
                                echo "<script>window.location.href = '".$contextTemp.".php';</script>";
                            }else{
                                echo 'query error'.mysqli_error($conn);
                            }
                            
                        }else{
                            echo "<script>alert('File doesn't exist');</script>";
                            echo "<script>window.location.href = '".$contextTemp.".php';</script>";
        
                        }
                        
                        mysqli_free_result($resultFilename);
                    }else{
                        echo "<script>alert('No file record found');</script>";
                    }
        
                    
                }
            }
            
        }
        
    
    }else{
        echo "<script>alert('Illegal redirect action detected');</script>";
        echo "<script>window.location.href = '".$contextTemp.".php';</script>";
    }
}





mysqli_close($conn);

?>