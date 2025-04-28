<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

if($infoUser[0]['grpid'] != ""){
    $flag_nogroup = 0;
    $grpidTemp = $infoUser[0]['grpid'];
    $grpid = mysqli_real_escape_string($conn, $grpidTemp);

    // checking credential, only the group own's mmid can deleted only
    $sql = "SELECT mmid FROM minmeet WHERE grpid = '$grpid'";
    $resultid = mysqli_query($conn, $sql);
    $infoid = mysqli_fetch_all($resultid, MYSQLI_ASSOC);

    // initialized flag
    $flag_id_exist = 0;

    if(isset($_GET['id'])){
        $_SESSION['mmid'] = $_GET['id'];

        foreach($infoid as $info){
            if($_SESSION['mmid'] === $info['mmid']){
                $flag_id_exist = 1;
            }
        }

        if($flag_id_exist){
            $mmid = mysqli_real_escape_string($conn,$_SESSION['mmid']);
            $sql = "DELETE FROM minmeet WHERE mmid = '$mmid'";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Successfully Deleted');</script>";
                echo "<script>window.location.href = 'minMeetHome.php';</script>";
            } else {
                echo 'query error'.mysqli_error($conn);
            }
        }else{
            echo "<script>alert('Illegal action detected');</script>";
            echo "<script>window.location.href = 'minMeetHome.php';</script>";
        }
        
    }

    mysqli_free_result($resultid);
}



mysqli_close($conn);
?>