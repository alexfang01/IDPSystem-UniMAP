<?php 
include('../config/db_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// credential check
include('./loginCheck.php');
date_default_timezone_set('Asia/Singapore'); // ensure it is GMT +8

// Getting user's information
$panelid = $_SESSION['id'];
$sqlUser = "SELECT * FROM panel WHERE panelid = '$panelid'";
$resultUser = mysqli_query($conn, $sqlUser);
$infoUser = mysqli_fetch_all($resultUser, MYSQLI_ASSOC); // convert to associate array

if(isset($_GET['id'])){
    $grpid = mysqli_real_escape_string($conn, $_GET['id']);
    
    //checking whether group is active or not
    $sql = "SELECT * FROM idpgroup WHERE grpid='$grpid' AND active='1'";
    $result = mysqli_query($conn, $sql);
    $infoGrp = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if (!(mysqli_num_rows($result) > 0)){
        echo "<script>alert('Illegal action detected');</script>";
        header("Location: idpexevalHome.php");
    }

    // getting evaluation information to prefill the data
    $sql = "SELECT * FROM idpexeval WHERE grpid='$grpid' AND evalType='panel' AND evaluatorid='$panelid'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0){
        $infoEval = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

if(isset($_POST['submit'])){
    $trait1 = mysqli_real_escape_string($conn, $_POST['trait1']);
    $trait2 = mysqli_real_escape_string($conn, $_POST['trait2']);
    $trait3 = mysqli_real_escape_string($conn, $_POST['trait3']);
    $trait4 = mysqli_real_escape_string($conn, $_POST['trait4']);
    $trait5 = mysqli_real_escape_string($conn, $_POST['trait5']);
    $trait6 = mysqli_real_escape_string($conn, $_POST['trait6']);
    $trait7 = mysqli_real_escape_string($conn, $_POST['trait7']);
    $trait8 = mysqli_real_escape_string($conn, $_POST['trait8']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // save the submit time into the database
    $current_date_time = new DateTime();
    $current_date_time_string = $current_date_time->format('m/d/Y g:i A');
    $submitTime = mysqli_real_escape_string($conn, $current_date_time_string);

    // calculating mark based on rubrics
    $trait1mark = $trait1 * 0.1;
    $trait2mark = $trait2 * 0.1;
    $trait3mark = $trait3 * 0.2;
    $trait4mark = $trait4 * 0.2;
    $trait5mark = $trait5 * 0.1;
    $trait6mark = $trait6 * 0.1;
    $trait7mark = $trait7 * 0.1;
    $trait8mark = $trait8 * 0.1;

    $totalMark = $trait1mark + $trait2mark + $trait3mark + $trait4mark + $trait5mark + $trait6mark + $trait7mark + $trait8mark;
    $finalMarkTemp = ($totalMark / 5)*15;
    $finalMark = mysqli_real_escape_string($conn, $finalMarkTemp);

    // checking whether evaluator already evaluate this group
    // if evaluated, update else insert
    $sql = "SELECT * FROM idpexeval WHERE grpid='$grpid' AND evalType='panel' AND evaluatorid='$panelid'";
    $result = mysqli_query($conn, $sql);
    // if exist, update
    if (mysqli_num_rows($result) > 0){
        $sql = "UPDATE idpexeval SET trait1='$trait1', trait2='$trait2', trait3='$trait3', trait4='$trait4', trait5='$trait5', trait6='$trait6', trait7='$trait7', trait8='$trait8', comment='$comment', submitTime='$submitTime'";
        if (mysqli_query($conn, $sql)) {
            $sql = "UPDATE grpmark SET idpexMarkPanel='$finalMark' WHERE grpid = '$grpid'";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Update successfully');</script>";
                //echo "<script>window.location.href = 'idpexevalHome.php';</script>";
            }else {
                echo 'query error'.mysqli_error($conn);
            }
            
        }else {
            echo 'query error'.mysqli_error($conn);
        }
    }else{
        $sql = "INSERT INTO idpexeval (evaluatorid, evalType, grpid, trait1, trait2, trait3, trait4, trait5, trait6, trait7, trait8, comment, submitTime) VALUES ('$panelid', 'panel', '$grpid', '$trait1', '$trait2', '$trait3', '$trait4', '$trait5', '$trait6', '$trait7', '$trait8', '$comment', '$submitTime')";
        if (mysqli_query($conn, $sql)) {
            //check whether the data is created or not
            $sql = "SELECT * FROM grpmark WHERE grpid='$grpid'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0){
                //exist means update, not insert
                $sql = "UPDATE grpmark SET idpexMarkPanel='$finalMark' WHERE grpid = '$grpid'";
                if (mysqli_query($conn, $sql)){
                    echo "<script>alert('Successfully evaluate');</script>";
                    echo "<script>window.location.href = 'idpexevalHome.php';</script>";
                }else {
                    echo 'query error'.mysqli_error($conn);
                }
            }else{
                $sql = "INSERT INTO grpmark (grpid, idpexMarkPanel) VALUES ('$grpid', '$finalMark')";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Successfully evaluate');</script>";
                    echo "<script>window.location.href = 'idpexevalHome.php';</script>";
                }else {
                    echo 'query error'.mysqli_error($conn);
                }
            }
            
        }else {
            echo 'query error'.mysqli_error($conn);
        }
    }

}

mysqli_free_result($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('dashTemp.php'); ?>

    <style>

        .title-container{
            border-bottom : 3px solid black;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            /* border: 3px solid red; */
        }

        .profile-form {
            width: 90%; /* Adjust the width of the form */
            max-width: 1100px; /* Set a maximum width if needed */
        }

        .member-textbox{
            /* width: 70%;
            margin-left: 195px;
            right: 0; */
        
        }

        .sticky-textbox {
            right: 0;
            width: calc(100% - 0px); /* Adjust as needed */
            margin-left: 0px; /* Adjust as needed */
        }
        
        h5{
            font-size: 18px;
        }

        .container-custom {
            justify-content: center;
            align-items: center;
            width: 60%;
            margin-left: 300px;
        }

        .custom-textbox-size{
            width: 100%;
        }
    
    @media (max-width: 768px){
        .title-container{
            border-bottom : 3px solid black;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            /* border: 3px solid red; */
        }

        .profile-form {
            width: 100%; /* Adjust the width of the form */
            max-width: 700px; /* Set a maximum width if needed */
        }

        .sticky-textbox {
            right: 0;
            width: calc(100% - 0px); /* Adjust as needed */
            margin-left: 0px; /* Adjust as needed */
        }
        
        h5{
            font-size: 14px;
        }

        .custom-textbox-size{
            width: calc(100%);
            margin-left: 0px;
            margin-right: 0px;
            font-size: 13px;
        }

        .form-check {
            font-size: 11px;
        }

        .form-control {
            font-size: 13px;
            width: 100%;
        }
    }
    </style>
        <!-- Main content in here -->
        <div class="main">
            <!-- Navigation bar part -->
            <?php include('navDisplay.php');?>
            <main class="content px-3 py-2">
            <div class="container">
            <form class="profile-form" method="post" action="">
                <div class="title-container">
                    <h1 class="text-center">IDP Exhibition Evaluation</h1>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="row g-3 align-items-center py-2">
                        <h5 class="text-justify text-center">
                            Please refer the rubrics given below
                        </h5>
                    </div>
                </div>
                    <h5 class="text-center py-3">
                            <strong>
                                You are now evaluating Group <?php echo htmlspecialchars($infoGrp[0]['grpnum']); ?>
                            </strong>   
                    </h5>
                <div class="row g-3 align-items-center py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>1. Project Functionality [CO1/PO3] (10%)</strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait1" id="trait1" value="0" <?php if(isset($infoEval[0]['trait1'])){ echo $infoEval[0]['trait1'] == 0?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait1">0&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait1" id="trait1" value="1" <?php if(isset($infoEval[0]['trait1'])){ echo $infoEval[0]['trait1'] == 1?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait1">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait1" id="trait1" value="2" <?php if(isset($infoEval[0]['trait1'])){ echo $infoEval[0]['trait1'] == 2?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait1">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait1" id="trait1" value="3" <?php if(isset($infoEval[0]['trait1'])){ echo $infoEval[0]['trait1'] == 3?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait1">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait1" id="trait1" value="4" <?php if(isset($infoEval[0]['trait1'])){ echo $infoEval[0]['trait1'] == 4?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait1">4&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait1" id="trait1" value="5" <?php if(isset($infoEval[0]['trait1'])){ echo $infoEval[0]['trait1'] == 5?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait1">5&emsp;</label>
                        </div>
                    </div>
                </div>
                <!-- ques2 -->
                <div class="row g-3 align-items-left py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>2. Project Complexity and Quality [CO1/PO3] (10%)</strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait2" id="trait2" value="0" <?php if(isset($infoEval[0]['trait2'])){ echo $infoEval[0]['trait2'] == 0?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait2">0&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait2" id="trait2" value="1" <?php if(isset($infoEval[0]['trait2'])){ echo $infoEval[0]['trait2'] == 1?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait2">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait2" id="trait2" value="2" <?php if(isset($infoEval[0]['trait2'])){ echo $infoEval[0]['trait2'] == 2?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait2">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait2" id="trait2" value="3" <?php if(isset($infoEval[0]['trait2'])){ echo $infoEval[0]['trait2'] == 3?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait2">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait2" id="trait2" value="4" <?php if(isset($infoEval[0]['trait2'])){ echo $infoEval[0]['trait2'] == 4?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait2">4&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait2" id="trait2" value="5" <?php if(isset($infoEval[0]['trait2'])){ echo $infoEval[0]['trait2'] == 5?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait2">5&emsp;</label>
                        </div>
                    </div>
                </div>
                <!-- ques3 -->
                <div class="row g-3 align-items-center py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>3. Solutions Related to Public Health, Safety and Culture in the Society [CO2/PO6] (20%)</strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait3" id="trait3" value="0" <?php if(isset($infoEval[0]['trait3'])){ echo $infoEval[0]['trait3'] == 0?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait3">0&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait3" id="trait3" value="1" <?php if(isset($infoEval[0]['trait3'])){ echo $infoEval[0]['trait3'] == 1?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait3">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait3" id="trait3" value="2" <?php if(isset($infoEval[0]['trait3'])){ echo $infoEval[0]['trait3'] == 2?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait3">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait3" id="trait3" value="3" <?php if(isset($infoEval[0]['trait3'])){ echo $infoEval[0]['trait3'] == 3?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait3">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait3" id="trait3" value="4" <?php if(isset($infoEval[0]['trait3'])){ echo $infoEval[0]['trait3'] == 4?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait3">4&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait3" id="trait3" value="5" <?php if(isset($infoEval[0]['trait3'])){ echo $infoEval[0]['trait3'] == 5?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait3">5&emsp;</label>
                        </div>
                    </div>
                </div>
                <!-- ques4 -->
                <div class="row g-3 align-items-left py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>4. Solutions Related to Environmental and Sustainability Factors [CO3/PO7] (20%)</strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait4" id="trait4" value="0" <?php if(isset($infoEval[0]['trait4'])){ echo $infoEval[0]['trait4'] == 0?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait4">0&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait4" id="trait4" value="1" <?php if(isset($infoEval[0]['trait4'])){ echo $infoEval[0]['trait4'] == 1?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait4">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait4" id="trait4" value="2" <?php if(isset($infoEval[0]['trait4'])){ echo $infoEval[0]['trait4'] == 2?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait4">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait4" id="trait4" value="3" <?php if(isset($infoEval[0]['trait4'])){ echo $infoEval[0]['trait4'] == 3?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait4">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait4" id="trait4" value="4" <?php if(isset($infoEval[0]['trait4'])){ echo $infoEval[0]['trait4'] == 4?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait4">4&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait4" id="trait4" value="5" <?php if(isset($infoEval[0]['trait4'])){ echo $infoEval[0]['trait4'] == 5?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait4">5&emsp;</label>
                        </div>
                    </div>
                </div>
                <!-- ques5 -->
                <div class="row g-3 align-items-left py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>5. Presenter Appearance & Professionalism [CO6/PO7] (10%)</strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait5" id="trait5" value="0" <?php if(isset($infoEval[0]['trait5'])){ echo $infoEval[0]['trait5'] == 0?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait5">0&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait5" id="trait5" value="1" <?php if(isset($infoEval[0]['trait5'])){ echo $infoEval[0]['trait5'] == 1?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait5">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait5" id="trait5" value="2" <?php if(isset($infoEval[0]['trait5'])){ echo $infoEval[0]['trait5'] == 2?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait5">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait5" id="trait5" value="3" <?php if(isset($infoEval[0]['trait5'])){ echo $infoEval[0]['trait5'] == 3?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait5">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait5" id="trait5" value="4" <?php if(isset($infoEval[0]['trait5'])){ echo $infoEval[0]['trait5'] == 4?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait5">4&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait5" id="trait5" value="5" <?php if(isset($infoEval[0]['trait5'])){ echo $infoEval[0]['trait5'] == 5?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait5">5&emsp;</label>
                        </div>
                    </div>
                </div>
                <!-- ques6 -->
                <div class="row g-3 align-items-left py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>6. Delivery [CO6/PO10] (10%)</strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait6" id="trait6" value="0" <?php if(isset($infoEval[0]['trait6'])){ echo $infoEval[0]['trait6'] == 0?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait6">0&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait6" id="trait6" value="1" <?php if(isset($infoEval[0]['trait6'])){ echo $infoEval[0]['trait6'] == 1?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait6">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait6" id="trait6" value="2" <?php if(isset($infoEval[0]['trait6'])){ echo $infoEval[0]['trait6'] == 2?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait6">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait6" id="trait6" value="3" <?php if(isset($infoEval[0]['trait6'])){ echo $infoEval[0]['trait6'] == 3?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait6">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait6" id="trait6" value="4" <?php if(isset($infoEval[0]['trait6'])){ echo $infoEval[0]['trait6'] == 4?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait6">4&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait6" id="trait6" value="5" <?php if(isset($infoEval[0]['trait6'])){ echo $infoEval[0]['trait6'] == 5?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait6">5&emsp;</label>
                        </div>
                    </div>
                </div>
                <!-- ques7 -->
                <div class="row g-3 align-items-left py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>7. Poster Content [CO6/PO7] (10%)</strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait7" id="trait7" value="0" <?php if(isset($infoEval[0]['trait7'])){ echo $infoEval[0]['trait7'] == 0?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait7">0&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait7" id="trait7" value="1" <?php if(isset($infoEval[0]['trait7'])){ echo $infoEval[0]['trait7'] == 1?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait7">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait7" id="trait7" value="2" <?php if(isset($infoEval[0]['trait7'])){ echo $infoEval[0]['trait7'] == 2?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait7">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait7" id="trait7" value="3" <?php if(isset($infoEval[0]['trait7'])){ echo $infoEval[0]['trait7'] == 3?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait7">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait7" id="trait7" value="4" <?php if(isset($infoEval[0]['trait7'])){ echo $infoEval[0]['trait7'] == 4?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait7">4&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait7" id="trait7" value="5" <?php if(isset($infoEval[0]['trait7'])){ echo $infoEval[0]['trait7'] == 5?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait7">5&emsp;</label>
                        </div>
                    </div>
                </div>
                <!-- ques8 -->
                <div class="row g-3 align-items-left py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>8. Use of Graphics [CO6/PO7] (10%)</strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait8" id="trait8" value="0" <?php if(isset($infoEval[0]['trait8'])){ echo $infoEval[0]['trait8'] == 0?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait8">0&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait8" id="trait8" value="1" <?php if(isset($infoEval[0]['trait8'])){ echo $infoEval[0]['trait8'] == 1?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait8">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait8" id="trait8" value="2" <?php if(isset($infoEval[0]['trait8'])){ echo $infoEval[0]['trait8'] == 2?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait8">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait8" id="trait8" value="3" <?php if(isset($infoEval[0]['trait8'])){ echo $infoEval[0]['trait8'] == 3?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait8">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait8" id="trait8" value="4" <?php if(isset($infoEval[0]['trait8'])){ echo $infoEval[0]['trait8'] == 4?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait8">4&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="trait8" id="trait8" value="5" <?php if(isset($infoEval[0]['trait8'])){ echo $infoEval[0]['trait8'] == 5?"checked": '';} ?> required>
                            <label class="form-check-label" for="trait8">5&emsp;</label>
                        </div>
                    </div>
                </div>
                <!-- ques9 -->
                <div class="mb-3 py-2 custom-textbox-size">
                    <label for="comment1" class="form-label">
                        <strong>9. Put any comments you like here:</strong>
                    </label>
                    <textarea class="form-control" id="comment" name="comment" rows="3"><?php if(isset($infoEval[0]['comment'])){ echo isset($infoEval[0]['comment'])?htmlspecialchars($infoEval[0]['comment']): '';} ?></textarea>
                    <div class="py-2 d-flex justify-content-center" >
                        <button type="submit" id="submit" name="submit" class="btn btn-block mt-2 btn-custom-shadow custom-content-btn" style="width:30%;">SUBMIT</button>
                    </div>
                </div>
    
                
            </form>
            </div>

            <div class="container mt-5">
                <a id="download-pdf" class="btn btn-primary btn-custom-shadow" href="../fileuploaded/rubrics/rubric_idpex.pdf" download="rubric_idpex.pdf" style="background-color: #4CE833; border: none; color: black;"><i class="fa-solid fa-file-arrow-down pe-1"></i><span class="custom-display">Download</span> Rubrics</a>
            </div>
            <div class="container">
                <span class="prompt-msg"><p>ROTATE your screen horizontally if using smaller device to have better PDF view</p></span>
            </div>
            <div class="container mt-3">
                <div id="pdf-container">
                    <canvas id="pdf-render"></canvas>
                </div>
            </div>

            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>

    
    <script>
        const sidebarToggle = document.querySelector("#sidebar-toggle");
        sidebarToggle.addEventListener("click",function(){
            document.querySelector("#sidebar").classList.toggle("collapsed")
        });

        const url = '../fileuploaded/rubrics/rubric_idpex.pdf';

        // Asynchronously download PDF as a binary blob
        const loadingTask = pdfjsLib.getDocument(url);
        loadingTask.promise.then(pdf => {
            console.log('PDF loaded');

            const numPages = pdf.numPages;
            const pdfContainer = document.getElementById('pdf-container');

            // Loop through each page
            for (let pageNum = 1; pageNum <= numPages; pageNum++) {
                pdf.getPage(pageNum).then(page => {
                    console.log(`Rendering page ${pageNum}`);

                    const scale = 1.2;
                    const viewport = page.getViewport({ scale });

                    // Create canvas element for this page
                    const canvas = document.createElement('canvas');
                    canvas.className = 'pdf-render';
                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Append canvas to the container
                    pdfContainer.appendChild(canvas);

                    // Render PDF page into canvas context
                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    const renderTask = page.render(renderContext);
                    renderTask.promise.then(() => {
                        console.log(`Page ${pageNum} rendered`);
                    }).catch(error => {
                        console.error(`Error rendering page ${pageNum}`, error);
                    });
                }).catch(error => {
                    console.error(`Error fetching page ${pageNum}`, error);
                });
            }
        }).catch(reason => {
            console.error('Error loading PDF', reason);
        });
    </script>
    
</body>
<?php mysqli_close($conn); ?>
</html>