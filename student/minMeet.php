<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('../config/db_connect.php');
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

    // Extract the group number
    $sql = "SELECT grpnum FROM idpgroup WHERE grpid = '$grpid'";
    $resultGroupNo = mysqli_query($conn, $sql);
    $infoGrpnum = mysqli_fetch_all($resultGroupNo, MYSQLI_ASSOC);

    // having the name of the members
    $sql = "SELECT name FROM student WHERE grpid='$grpid'";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);


}




// predefined
$infoMinmeet = "";
$flag_submitted = 0;
$meetdate_err = $weekno_err = $summarymeet_err ="";

// Due date time information
date_default_timezone_set('Asia/Singapore'); // make sure is GMT +8
$sqlDuetime = "SELECT duedate.dueDateTime FROM duedate INNER JOIN context ON duedate.contid = context.contid WHERE context = 'minute meetings'";
$resultDuetime = mysqli_query($conn, $sqlDuetime);

if(mysqli_num_rows($resultDuetime) > 0){
    $infoDuetime = mysqli_fetch_all($resultDuetime, MYSQLI_ASSOC); // convert to associate array
    $duetimeString = $infoDuetime[0]['dueDateTime'];
}


// Information of Submitted Min Meetings
if(isset($_GET['id'])){
    $idTemp = $_GET['id'];
    $mmid = mysqli_real_escape_string($conn,$idTemp);

    // store in session to use when getting error message
    $_SESSION['mmid'] = $_GET['id'];

    $sql = "SELECT * FROM minmeet WHERE mmid = '$mmid'";
    $resultMinmeet = mysqli_query($conn, $sql);
    $infoMinmeet= mysqli_fetch_all($resultMinmeet, MYSQLI_ASSOC);

    // Extract comment from supervisor
    if($infoMinmeet[0]['comment'] != ""){
        $comment = $infoMinmeet[0]['comment'];
    }
    

    if($infoMinmeet[0]['approved'] == 1){
        $_SESSION['approved'] = 1;
    }

    $flag_submitted = 1;
    mysqli_free_result($resultMinmeet);
}else {
    // If no id is provided, initialize $infoMinmeet as an empty array to prevent errors
    $infoMinmeet = array(array('meetdate' => '', 'weekNo' => '', 'taker' => '', 'attendee1' => '', 'attendee2' => '', 'attendee3' => '', 'summary' => '', 'mmid' => ''));

}

if(isset($_POST['submit'])){
    $meetdate = mysqli_real_escape_string($conn, $_POST['meetdate']);
    $weekno = mysqli_real_escape_string($conn, $_POST['weekno']);
    $mintaker = mysqli_real_escape_string($conn, $_POST['mintaker']);
    $attend1 = mysqli_real_escape_string($conn, $_POST['attend1']);
    $attend2 = mysqli_real_escape_string($conn, $_POST['attend2']);
    $attend3 = mysqli_real_escape_string($conn, $_POST['attend3']);
    $summarymeet = mysqli_real_escape_string($conn, $_POST['summarymeet']);

    // save the submit time into the database
    $current_date_time = new DateTime();
    $current_date_time_string = $current_date_time->format('m/d/Y g:i A');
    $submitTime = mysqli_real_escape_string($conn, $current_date_time_string);

    if($meetdate == ''){
        echo "<script>alert('Please select the date');</script>";
        if(isset($_SESSION['mmid'])){
            echo "<script>window.location.href = 'minMeet.php?id=" . $_SESSION['mmid'] . "';</script>";
        }
        $meetdate_err = "Please select the date";
        
    }
    if($weekno == ''){
        echo "<script>alert('Please enter Week Number');</script>";
        if(isset($_SESSION['mmid'])){
            echo "<script>window.location.href = 'minMeet.php?id=" . $_SESSION['mmid'] . "';</script>";
        }
        $weekno_err = "Please enter Week Number";
    }
    if(strlen($summarymeet) < 2){
        echo "<script>alert('Please enter your discussion');</script>";
        if(isset($_SESSION['mmid'])){
            echo "<script>window.location.href = 'minMeet.php?id=" . $_SESSION['mmid'] . "';</script>";
        }
        $summarymeet_err = "Please enter your discussion";
    }

    // checking whether the number of week is used
    $sql = "SELECT weekNo FROM minmeet WHERE grpid = '$grpid'";
    $resultWeekno = mysqli_query($conn, $sql);
    if(mysqli_num_rows($resultWeekno) > 0){
        $infoWeekno = mysqli_fetch_all($resultWeekno, MYSQLI_ASSOC);
        foreach($infoWeekno as $week){
            if($weekno == $week['weekNo']){
                
                if(isset($_SESSION['mmid'])){
                    // direct check input in here
                    $mmid = mysqli_real_escape_string($conn,$_SESSION['mmid']);
                    $sql = "SELECT weekNo FROM minmeet WHERE mmid = '$mmid'";
                    $resultmin = mysqli_query($conn, $sql);
                    $infomin= mysqli_fetch_all($resultmin, MYSQLI_ASSOC);
                    mysqli_free_result($resultmin);

                    if ($weekno != $infomin[0]['weekNo']){
                        
                        echo $weekno;
                        echo "<script>alert('Week number Existed, Please enter a new Week number');</script>";
                        echo "<script>window.location.href = 'minMeet.php?id=" . $_SESSION['mmid'] . "';</script>";
                        $weekno_err = "Week number Existed, Please enter a new Week number";
                    }
                }else{
                    echo "<script>alert('Week number Existed, Please enter a new Week number');</script>";
                    $weekno_err = "Week number Existed, Please enter a new Week number";
                }
                
            }
        }
    }

    if(!$meetdate_err && !$weekno_err && !$summarymeet_err && !(isset($_SESSION['approved']) ? $_SESSION['approved'] : '')){
        // save the submit time into the database
        $current_date_time = new DateTime();
        $current_date_time_string = $current_date_time->format('m/d/Y g:i A');
        $submitTime = mysqli_real_escape_string($conn, $current_date_time_string);
        mysqli_free_result($resultDuetime);

        // if got id means, it just edit
        if(isset($_POST['hidmmid'])){
            $mmid = mysqli_real_escape_string($conn, $_POST['hidmmid']);
            // get id of the minute meeetings from URL
            $sql = "UPDATE minmeet SET meetdate='$meetdate', weekNo='$weekno', taker='$mintaker', attendee1='$attend1', attendee2='$attend2', attendee3='$attend3', summary='$summarymeet', submitTime='$submitTime', submitted=1 WHERE mmid='$mmid'";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Edit Successfully, Wait for Approval');</script>";
                // Redirect to minMeetHome.php
                echo "<script>window.location.href = 'minMeetHome.php';</script>";
            } else {
                echo 'query error'.mysqli_error($conn);
            }

        }else{ // means add new one
            
            $sql = "INSERT INTO minmeet (meetdate, weekNo, taker, attendee1, attendee2, attendee3, summary, approved, submitted, grpid, submitTime) VALUES ('$meetdate', '$weekno', '$mintaker', '$attend1', '$attend2', '$attend3', '$summarymeet', 0, 1, '$grpid','$submitTime')";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Minute Meeting Created, Wait for Approval');</script>";
                // Redirect to minMeetHome.php
                echo "<script>window.location.href = 'minMeetHome.php';</script>";
            } else {
                echo 'query error'.mysqli_error($conn);
            }
        }
    }
    
    mysqli_free_result($resultWeekno);
    
}





mysqli_free_result($resultGroupNo);
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

        .synopsis-form {
            width: 90%; /* Adjust the width of the form */
            max-width: 1100px; /* Set a maximum width if needed */
        }



        .sticky-textbox {
            right: 0;
            width: calc(100% + 1px); /* Adjust as needed */
            margin-left: -1px; /* Adjust as needed */
        }

        .sticky-textbox-2{
            right: 0;
            width: calc(100% - 46px); /* Adjust as needed */
            margin-left: 46px; /* Adjust as needed */
        }

        .sticky-textbox-3{
            right: 0;
            width: calc(100% - 38px); /* Adjust as needed */
            margin-left: 38px; /* Adjust as needed */
        }

        .sticky-textbox-4{
            right: 0;
            width: calc(100% - 5px); /* Adjust as needed */
            margin-left: 5px; /* Adjust as needed */
        }

        .sticky-textbox-5{
            right: 0;
            width: calc(100% - 30px); /* Adjust as needed */
            margin-left: 30px; /* Adjust as needed */
        }
        
        .sticky-textbox-6 {
            right: 0;
            width: calc(100% - 158px); /* Adjust as needed */
            margin-left: 158px; /* Adjust as needed */
        }

        .custom-btn-size{
            width:20%;
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

        .synopsis-form {
            width: 100%; /* Adjust the width of the form */
            max-width: 700px; /* Set a maximum width if needed */
        }



        .sticky-textbox {
            width: calc(100% - 5px - 0px);
            margin-left: 5px;
            margin-right: 0px;
            font-size: 13px;
        }

        .sticky-textbox-2{
            width: calc(100% - 5px - 0px);
            margin-left: 5px;
            margin-right: 0px;
            font-size: 13px;
        }

        .sticky-textbox-3{
            width: calc(100% - 5px - 0px);
            margin-left: 5px;
            margin-right: 0px;
            font-size: 13px;
        }

        .sticky-textbox-4{
            width: calc(100% - 5px - 0px);
            margin-left: 5px;
            margin-right: 0px;
            font-size: 13px;
        }

        .sticky-textbox-5{
            width: calc(100% - 5px - 0px);
            margin-left: 5px;
            margin-right: 0px;
            font-size: 13px;
        }
        
        .sticky-textbox-6 {
            width: calc(100% - 5px - 0px);
            margin-left: 5px;
            margin-right: 0px;
            font-size: 13px;
        }

        .form-control{
            font-size: 13px;
        }

        .custom-btn-size{
            width:40%;
            font-size: 13px;
        }

    }
    </style>
        <!-- Main content in here -->
        <div class="main">
            <!-- Navigation bar part -->
            <?php include('navDisplay.php');?>
            <main class="content px-3 py-2">
            
            <div class="container">
            <form class="synopsis-form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return validateForm() && validateAttendees()">
                <!-- getting the id after pressing the button instead of using GET method -->
                <?php if(isset($infoMinmeet) && isset($_GET['id'])): ?>
                    <input type="hidden" id="hidmmid" name="hidmmid" value="<?php echo $_GET['id']; ?>">
                <?php endif; ?>
                <div class="title-container">
                <h1 class="text-center">Minute Meeting</h1>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col-auto">
                        <label for="meetdate" class="col-form-label">Meeting Date :</label>
                    </div>
                    <div class="col-auto">
                        <input type="date" class="form-control sticky-textbox" id="meetdate" name="meetdate" placeholder="" value="<?php echo isset($infoMinmeet)? $infoMinmeet[0]['meetdate']:'';?><?php echo isset($_POST['meetdate'])? $_POST['meetdate']:''; ?>">
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col-auto">
                        <label for="weekno" class="col-form-label">Week No :</label>
                    </div>
                    <div class="col">
                        <input type="number" class="form-control sticky-textbox-2" id="weekno" name="weekno" placeholder="#" min="1" max="14" value="<?php echo isset($infoMinmeet)? $infoMinmeet[0]['weekNo']:''; ?><?php echo isset($_POST['weekno'])? $_POST['weekno']:''; ?>">
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col-auto">
                        <label for="groupno" class="col-form-label">Group No :</label>
                    </div>
                    <div class="col">
                        <input type="number" readonly class="form-control sticky-textbox-3" id="groupno" placeholder="#" value="<?php echo isset($infoGrpnum)? $infoGrpnum[0]['grpnum']:''; ?>">
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col-auto">
                            <label for="mintaker" class="col-form-label" style="float:left;">Minute Taker :</label>
                    </div>
                    <div class="col">
                        <select id="mintaker" name="mintaker" class="form-select sticky-textbox-4" aria-label="Default select example">
                            <option value="" <?php echo isset($infoMinmeet)? '': "selected";?><?php echo isset($_POST['mintaker'])? '':"selected"; ?>>Select Member</option>
                            <?php foreach($rows as $row): ?>
                            <option value="<?php echo $row['name']?>" <?php if(isset($infoMinmeet)) {echo $infoMinmeet[0]['taker'] == $row['name']? "selected": '';}?><?php if(isset($_POST['mintaker'])) {echo $_POST['mintaker'] == $row['name']? "selected": '';}?> ><?php echo $row['name']?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col-auto">
                            <label for="" class="col-form-label" style="float:left;">Attendees :</label>
                    </div>
                    <div class="col">
                        <select id="attend1" name="attend1" class="form-select sticky-textbox-5" aria-label="Default select example" >
                            <option value="" <?php echo isset($infoMinmeet)? '': "selected";?><?php echo isset($_POST['attend1'])? '':"selected"; ?>>Select Member</option>
                            <?php foreach($rows as $row): ?>
                            <option value="<?php echo $row['name']?>" <?php if(isset($infoMinmeet)) {echo $infoMinmeet[0]['attendee1'] == $row['name']? "selected": '';}?><?php if(isset($_POST['attend1'])) {echo $_POST['attend1'] == $row['name']? "selected": '';}?>><?php echo $row['name']?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col">
                        <select id="attend2" name="attend2"class="form-select sticky-textbox-6" aria-label="Default select example" >
                            <option value="" <?php echo isset($infoMinmeet)? '': "selected";?><?php echo isset($_POST['attend2'])? '':"selected"; ?>>Select Member</option>
                            <?php foreach($rows as $row): ?>
                            <option value="<?php echo $row['name']?>" <?php if(isset($infoMinmeet)) {echo $infoMinmeet[0]['attendee2'] == $row['name']? "selected": '';}?><?php if(isset($_POST['attend2'])) {echo $_POST['attend2'] == $row['name']? "selected": '';}?>><?php echo $row['name']?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col">
                        <select id="attend3" name="attend3" class="form-select sticky-textbox-6" aria-label="Default select example" >
                            <option value="" <?php echo isset($infoMinmeet)? '': "selected";?><?php echo isset($_POST['attend3'])? '':"selected"; ?>>Select Member</option>
                            <?php foreach($rows as $row): ?>
                            <option value="<?php echo $row['name']?>" <?php if(isset($infoMinmeet)) {echo $infoMinmeet[0]['attendee3'] == $row['name']? "selected": '';}?><?php if(isset($_POST['attend3'])) {echo $_POST['attend3'] == $row['name']? "selected": '';}?>><?php echo $row['name']?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3 py-2">
                    <label for="summarymeet" class="form-label">Summary of Discussion :</label>
                    <textarea class="form-control" id="summarymeet" name="summarymeet" rows="5"><?php if(isset($infoMinmeet)){echo isset($infoMinmeet)? htmlspecialchars($infoMinmeet[0]['summary']):'';}?><?php echo isset($_POST['summarymeet'])? htmlspecialchars($_POST['summarymeet']):''; ?></textarea>
                </div>
                <div class="mb-3 py-2">
                    <label for="comment" class="form-label">Comment from Supervisor :</label>
                    <textarea readonly class="form-control" id="comment" name="comment" rows="5"><?php echo isset($comment)? htmlspecialchars($comment):'';?></textarea>
                </div>
                <div class="py-2 d-flex justify-content-center" >
                    <button type="submit" id="submit" name="submit" class="btn btn-block mt-2 btn-custom-shadow custom-content-btn custom-btn-size"  <?php echo (isset($_SESSION['approved'])? $_SESSION['approved']:'') ? "disabled": ''; ?><?php echo $flag_nogroup?"disabled": ''; ?>><?php echo $flag_submitted? "RESUBMIT": "SUBMIT";?></button>
                </div>
            </form>
            </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebarToggle = document.querySelector("#sidebar-toggle");
        sidebarToggle.addEventListener("click",function(){
            document.querySelector("#sidebar").classList.toggle("collapsed")
        });

        // Minute Taker should have at least one value
        function validateForm() {
            var mintakerValue = document.getElementById('mintaker').value;
            if (mintakerValue == '') {
                alert('Please select a name for Minute Taker.');
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }

        // Three attendee should have different value if all is selected
        function validateAttendees() {
            var mintaker = document.getElementById("mintaker").value;
            var attend1 = document.getElementById("attend1").value;
            var attend2 = document.getElementById("attend2").value;
            var attend3 = document.getElementById("attend3").value;

            // Check if all three dropdowns have different values
            if (attend1 !== "" && attend2 !== "" && attend3 !== "") {
                if (attend1 === attend2 || attend1 === attend3 || attend2 === attend3 || mintaker === attend1 || mintaker === attend2 || mintaker == attend3) {
                    alert("Attendees must have different values.");
                    return false;
                } else {
                    return true;
                }
            } else if (attend1 !== "" && attend2 !== ""){
                if (attend1 === attend2 || mintaker === attend1 || mintaker == attend2) {
                    alert("Attendees must have different values.");
                    return false;
                } else {
                    return true;
                }
            } else if (attend1 !== "" && attend3 !== ""){
                if (attend1 === attend3 || mintaker === attend1 || mintaker == attend3) {
                    alert("Attendees must have different values.");
                    return false;
                } else {
                    return true;
                }
            } else if (attend2 !== "" && attend3 !== ""){
                if (attend2 === attend3 || mintaker === attend2 || mintaker == attend3) {
                    alert("Attendees must have different values.");
                    return false;
                } else {
                    return true;
                }
            } else if (attend1 !== ""){
                if (mintaker === attend1) {
                    alert("Attendees must have different values.");
                    return false;
                } else {
                    return true;
                }
            } else if (attend2 !== ""){
                if (mintaker === attend2) {
                    alert("Attendees must have different values.");
                    return false;
                } else {
                    return true;
                }
            } else if (attend3 !== ""){
                if (mintaker === attend3) {
                    alert("Attendees must have different values.");
                    return false;
                } else {
                    return true;
                }
            } 

        }


    </script>
</body>
<?php mysqli_close($conn); ?>
</html>