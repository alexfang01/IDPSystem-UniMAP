<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('../config/db_connect.php');
// credential check
include('./loginCheck.php');


$flag_nogroup = 1; // if got group then allow normal form submission, else disable like approved case


// Getting user's information
$svid = $_SESSION['id'];
$sqlUser = "SELECT * FROM supervisor WHERE svid = '$svid'";
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
// comment and return to student
if (isset($_POST['comment'])) {

    $mmid = mysqli_real_escape_string($conn,$_SESSION['mmid']);
    $comment = mysqli_real_escape_string($conn, $_POST['commentText']);

    $sql="UPDATE minmeet SET comment='$comment', submitted=0 WHERE mmid='$mmid'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Comment Successfully');</script>";
    } else {
        echo 'Query error: ' . mysqli_error($conn);
    }

    $sql = "SELECT * FROM minmeet WHERE mmid = '$mmid'";
    $resultMinmeet = mysqli_query($conn, $sql);
    $infoMinmeet= mysqli_fetch_all($resultMinmeet, MYSQLI_ASSOC);
    mysqli_free_result($resultMinmeet);
}

// approved the synopsis
if (isset($_POST['approve'])) {

    $mmid = mysqli_real_escape_string($conn,$_SESSION['mmid']);
    $sql="UPDATE minmeet SET approved = '1' WHERE mmid='$mmid'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Minute Meeting Approved, Student no longer able to edit');</script>";
    } else {
        echo 'Query error: ' . mysqli_error($conn);
    }

    $sql = "SELECT * FROM minmeet WHERE mmid = '$mmid'";
    $resultMinmeet = mysqli_query($conn, $sql);
    $infoMinmeet= mysqli_fetch_all($resultMinmeet, MYSQLI_ASSOC);
    mysqli_free_result($resultMinmeet);
}

// reject the synopsis for some reasons
if (isset($_POST['reject'])) {
    $mmid = mysqli_real_escape_string($conn,$_SESSION['mmid']);

    $sql="UPDATE minmeet SET approved = '0' WHERE mmid='$mmid'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Minute Meeting Rejected, Student able to make changes');</script>";
    } else {
        echo 'Query error: ' . mysqli_error($conn);
    }

    $sql = "SELECT * FROM minmeet WHERE mmid = '$mmid'";
    $resultMinmeet = mysqli_query($conn, $sql);
    $infoMinmeet= mysqli_fetch_all($resultMinmeet, MYSQLI_ASSOC);
    mysqli_free_result($resultMinmeet);
}





mysqli_free_result($resultGroupNo);
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
            width: calc(100% - 4px); /* Adjust as needed */
            margin-left: 4px; /* Adjust as needed */
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

    @media (max-width: 768px){

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
            width: 80px;
            font-size: 12px;
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
                        <input readonly type="date" class="form-control sticky-textbox" id="meetdate" name="meetdate" placeholder="" value="<?php echo isset($infoMinmeet[0]['meetdate'])? htmlspecialchars($infoMinmeet[0]['meetdate']):'';?>">
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col-auto">
                        <label for="weekno" class="col-form-label">Week No :</label>
                    </div>
                    <div class="col">
                        <input readonly type="number" class="form-control sticky-textbox-2" id="weekno" name="weekno" placeholder="#" min="1" max="14" value="<?php echo isset($infoMinmeet[0]['weekNo'])? htmlspecialchars($infoMinmeet[0]['weekNo']):''; ?>">
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col-auto">
                        <label for="groupno" class="col-form-label">Group No :</label>
                    </div>
                    <div class="col">
                        <input type="number" readonly class="form-control sticky-textbox-3" id="groupno" placeholder="#" value="<?php echo isset($infoGrpnum)? htmlspecialchars($infoGrpnum[0]['grpnum']):''; ?>">
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col-auto">
                            <label for="mintaker" class="col-form-label" style="float:left;">Minute Taker :</label>
                    </div>
                    <div class="col">
                        <input type="text" readonly class="form-control sticky-textbox-4" id="groupno" placeholder="" value="<?php echo isset($infoMinmeet[0]['taker'])? htmlspecialchars($infoMinmeet[0]['taker']):''; ?>">
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col-auto">
                            <label for="" class="col-form-label" style="float:left;">Attendees :</label>
                    </div>
                    <div class="col">
                        <input type="text" readonly class="form-control sticky-textbox-5" id="groupno" placeholder="" value="<?php echo isset($infoMinmeet[0]['attendee1'])? htmlspecialchars($infoMinmeet[0]['attendee1']):''; ?>">
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                <div class="col">
                        <input type="text" readonly class="form-control sticky-textbox-6" id="groupno" placeholder="" value="<?php echo isset($infoMinmeet[0]['attendee2'])? htmlspecialchars($infoMinmeet[0]['attendee2']):''; ?>">
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                <div class="col">
                        <input type="text" readonly class="form-control sticky-textbox-6" id="groupno" placeholder="" value="<?php echo isset($infoMinmeet[0]['attendee3'])? htmlspecialchars($infoMinmeet[0]['attendee3']):''; ?>">
                    </div>
                </div>
                <div class="mb-3 py-2">
                    <label for="summarymeet" class="form-label">Summary of Discussion :</label>
                    <textarea readonly class="form-control" id="summarymeet" name="summarymeet" rows="5"><?php echo (isset($infoMinmeet[0]['summary']))?htmlspecialchars($infoMinmeet[0]['summary']) :''; ?></textarea>
                </div>
                <div class="mb-3 py-2">
                    <label for="comment" class="form-label">Comment from Supervisor :</label>
                    <textarea class="form-control" id="commentText" name="commentText" rows="5"><?php echo (isset($infoMinmeet[0]['comment']))?htmlspecialchars($infoMinmeet[0]['comment']) :''; ?></textarea>
                </div>
                <!-- button -->
                <div class="py-1 d-flex justify-content-center" >
                    <div class="row">
                        <?php if(isset($infoMinmeet[0]['approved'])): ?>
                            <div class="col">
                            <button type="submit" id="comment" name="comment" class="btn btn-block btn-custom-shadow custom-content-btn custom-btn-size">COMMENT</button>
                            </div>

                            <?php if($infoMinmeet[0]['approved'] == 0): ?>
                                <div class="col">
                                    <button type="submit" id="approve" name="approve" class="btn btn-block btn-custom-shadow custom-content-btn custom-btn-size">APPROVE</button>
                                </div>
                            <?php else: ?>
                                <div class="col">
                                    <button type="submit" id="reject" name="reject" class="btn btn-block btn-custom-shadow custom-content-btn custom-btn-size" style="background-color: #AB305B;">REJECT</button>
                                </div>
                            <?php endif ?>
                            
                        <?php  endif?>
                    </div>
                    
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