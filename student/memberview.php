<?php 
// connect to database
include('../config/db_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// credential check
include('./loginCheck.php');
date_default_timezone_set('Asia/Singapore'); // make sure is GMT +8

// Getting user's information
$studentid = $_SESSION['id'];
$sqlUser = "SELECT * FROM student WHERE studentid = '$studentid'";
$resultUser = mysqli_query($conn, $sqlUser);
$infoUser = mysqli_fetch_all($resultUser, MYSQLI_ASSOC); // convert to associate array
mysqli_free_result($resultUser);


$flag_nogroup = 1;

if($infoUser[0]['grpid'] != ""){
    $flag_nogroup = 0;
    $grpidTemp = $infoUser[0]['grpid'];
    $grpid = mysqli_real_escape_string($conn, $grpidTemp);

    // when reloading the pages
    if(isset($_GET['id'])){
        // getting the member information
        $idTemp = $_GET['id'];
        $memberid = mysqli_real_escape_string($conn,$idTemp);
        $_SESSION['memberid'] = $memberid;

        $sql = "SELECT name FROM student WHERE studentid='$memberid' AND grpid='$grpid'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            $memberinfo= mysqli_fetch_all($result, MYSQLI_ASSOC);
        }else{
            echo "<script>alert('Not allow to edit student from other group');</script>";
            echo '<script type="text/javascript">window.location.href="./peer.php";</script>';
        }
        
        // check submitted peer information
        $sql = "SELECT * FROM peerstudent WHERE grader='$studentid' AND member='$memberid'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            $peerRecord= mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        mysqli_free_result($result);
    }

    if(isset($_POST['submit'])){

        $trait1 = mysqli_real_escape_string($conn,$_POST['trait1']);
        $trait2 = mysqli_real_escape_string($conn,$_POST['trait2']);
        $trait4 = mysqli_real_escape_string($conn,$_POST['trait4']);
        $trait5 = mysqli_real_escape_string($conn,$_POST['trait5']);
        $trait6 = mysqli_real_escape_string($conn,$_POST['trait6']);
        $trait7 = mysqli_real_escape_string($conn,$_POST['trait7']);
        $comment = mysqli_real_escape_string($conn,$_POST['comment']);
        $memberid =  mysqli_real_escape_string($conn,$_SESSION['memberid']); 

        //calculating mark
        $trait1mark = $trait1*(8/75);
        $trait2mark = $trait2*(7/15); // contribute 3.5%
        $trait4mark = $trait4*(8/75);
        $trait5mark = $trait5*(8/75);
        $trait6mark = $trait6*(8/75);
        $trait7mark = $trait7*(8/75);

        $totalMark = $trait1mark +$trait2mark +$trait4mark +$trait5mark + $trait6mark + $trait7mark;
        $finalMarkTemp = ($totalMark / 5)*7.5;
        $finalMark =  mysqli_real_escape_string($conn,$finalMarkTemp); 

        // save the submit time into the database
        $current_date_time = new DateTime();
        $current_date_time_string = $current_date_time->format('m/d/Y g:i A');
        $submitTime = mysqli_real_escape_string($conn, $current_date_time_string);

        // check submitted peer information
        $sql = "SELECT * FROM peerstudent WHERE grader='$studentid' AND member='$memberid'";
        $resultSubmit = mysqli_query($conn, $sql);
        if(mysqli_num_rows($resultSubmit) > 0){
            $peerRecord= mysqli_fetch_all($resultSubmit, MYSQLI_ASSOC);
        }
        mysqli_free_result($resultSubmit);

        if(!empty($trait1) && !empty($trait2) && !empty($trait4) && !empty($trait5) && !empty($trait6) && !empty($trait7) && !empty($memberid) && !empty($studentid)){
            
            if(isset($peerRecord)){
                // if set means need update only!
                $sql = "UPDATE peerstudent SET trait1='$trait1', trait2='$trait2', trait4='$trait4', trait5='$trait5', trait6='$trait6', trait7='$trait7', timeSubmit='$submitTime', comment='$comment', mark='$finalMark' WHERE grader='$studentid' AND member='$memberid' AND grpid='$grpid'";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Edit Successfully');</script>";
                    unset($_SESSION['memberid']);
                } else {
                    echo 'query error'.mysqli_error($conn);
                }
            }else{
                // perform data insertion
                $sql = "INSERT INTO peerstudent (grader, member, trait1, trait2, trait4, trait5, trait6, trait7, comment, timeSubmit, grpid, mark) VALUES ('$studentid', '$memberid', '$trait1', '$trait2', '$trait4', '$trait5', '$trait6', '$trait7', '$comment', '$submitTime', '$grpid', '$finalMark')";

                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Evaluate Successfully');</script>";
                    unset($_SESSION['memberid']);
                } else {
                    echo 'query error'.mysqli_error($conn);
                }
            }
            // check whether able to calculate the final mark if everyone had evaluate you
            // compare the size of number of group member with the number of grader for the member
            $sql = "SELECT studentid FROM student WHERE grpid = '$grpid'";
            $resultx = mysqli_query($conn, $sql);
            if(mysqli_num_rows($resultx) > 0){
                $rows= mysqli_fetch_all($resultx, MYSQLI_ASSOC);
                foreach($rows as $row){
                    $membersGrp[] = $row['studentid'];
                }
                $sizeGrp = count($membersGrp);
            }
            $sql = "SELECT peerid FROM peerstudent WHERE member = '$memberid'";
            $resultx = mysqli_query($conn, $sql);
            if(mysqli_num_rows($resultx) > 0){
                $rows= mysqli_fetch_all($resultx, MYSQLI_ASSOC);
                foreach($rows as $row){
                    $membersGrader[] = $row['peerid'];
                }
                $sizeGrader = count($membersGrader);
            }
            // calculate the average mark if both size is same, indicating done evaluate the peer review for this member
        

            if($sizeGrp == $sizeGrader){
                $sql = "SELECT mark FROM peerstudent WHERE member='$memberid'";
                $resultx = mysqli_query($conn, $sql);
                if(mysqli_num_rows($resultx) > 0){
                    $rows= mysqli_fetch_all($resultx, MYSQLI_ASSOC);
                    foreach($rows as $row){
                        $marks[] = $row['mark'];
                    }
                    if(isset($marks)){
                        $sum = 0;
                        $size = count($marks);
                        foreach($marks as $mark){
                            $sum += $mark;
                        }
                        // average mark
                        $MARKTemp = $sum / $size;
                        $MARK =  mysqli_real_escape_string($conn,$MARKTemp); // write in database

                        //check whether the record exists or not
                        $sql = "SELECT * FROM peermark WHERE studentid='$memberid'";
                        $resultx = mysqli_query($conn, $sql);
                        if(mysqli_num_rows($resultx) > 0){
                            // exists means update
                            $sql = "UPDATE peermark SET peerstuMark = '$MARK' WHERE studentid='$memberid'";
                            if (!mysqli_query($conn, $sql)) {
                                echo 'query error'.mysqli_error($conn);
                            }
                        }else{
                            // not exists means insert new one
                            $sql = "INSERT INTO peermark (peerstuMark, studentid) VALUES ('$MARK', '$memberid')";
                            if (!mysqli_query($conn, $sql)) {
                                echo 'query error'.mysqli_error($conn);
                            }
                        }
                    }
                    
                    
                }
            }

            mysqli_free_result($resultx);
            echo "<script>window.location.href = 'peer.php';</script>";
            // INSERT TOTAL FINAL mark end

            
        }else{
            
        }
        
        

    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('dashTemp.php'); ?>

    <style>
        /* .box {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 0px 0px 40px 40px;
            background-color: white;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            } */

        /* form {
            border: 3px solid red;
            border-radius: 4px;
            padding-left: 5px;
        } */

        /* .synopsis-form{
            border: 3px solid red;
            border-radius: 4px;
            justify-content : center;
            align-items : center;
        } */

        /* .card{
            max-width: 1200px;
            margin: auto;
        } */

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
            margin-left: 230px;
        }

        .custom-textbox-size{
            width: calc(100% -208px - 208px); /* Adjust as needed */
            margin-left: 208px; /* Adjust as needed */
            margin-right: 208px; /* Adjust as needed */
        }
        
        .custom-btn-size{
            width:20%;
        }
    @media(max-width: 768px){
        .title-container{
            border-bottom : 3px solid black;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            /* border: 3px solid red; */
            font-size: 12px;
        }

        .profile-form {
            width: 100%; /* Adjust the width of the form */
            max-width: 700px; /* Set a maximum width if needed */
            font-size: 12px;
        }


        .sticky-textbox {
            width: calc(100% - 5px - 0px);
            margin-left: 5px;
            margin-right: 0px;
            font-size: 13px;
        }
        
        h5{
            font-size: 12px;
        }

        .container-custom {
            justify-content: center;
            align-items: center;
            width: 100%;
            margin-left: 5px;
            font-size: 12px;
        }

        .custom-textbox-size{
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
            <?php include('./navDisplay.php'); ?>
            <main class="content px-3 py-2">
            
            <div class="container">
            <form class="profile-form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                <div class="title-container">
                    <h1 class="text-center">Peer Review Evaluation</h1>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <h5 class="text-justify">
                        This semester you have worked with your group member to complete the project. 
                        Please rate yourself and your team members on your relative contribution to prepare and present your project work.
                        Rate yourself and your team members using the 1 to 5 grading scale as below:
                    </h5>
                    <h5 class="text-center">
                        <strong>
                            5-ALWAYS&emsp;&emsp;4-MOST OF THE TIME&emsp;&emsp;3-SOMETIMES&emsp;&emsp;2-RARELY&emsp;&emsp;1-NEVER
                        </strong>   <!-- &emsp; make 4 spaces -->
                    </h5>
                    <h5 class="text-justify">
                    Keep in mind that if you choose <strong>HIGH SCALE</strong> to <strong>EVERYONE</strong>, regardless of their contribution, team members who have worked unduly hard or provided extraordinary leadership will go unrecognized, as will those at the other end of the scale who need your corrective feedback.
                    </h5>
                    <h5 class="text-justify">
                        Your <strong>SCORE</strong> will be <strong>confidential and anonymous</strong>. Be honest in the evaluation.
                    </h5>
                </div>
                <h5 class="text-center py-5">
                        <?php if(!$flag_nogroup): ?>
                            <strong>
                                You are now evaluating<?php echo ($studentid == $idTemp)?" Yourself":strtoupper(" ".$memberinfo[0]['name']);?>
                            </strong>   
                        <?php endif ?>
                    </h5>
                <div class="container-custom">
                    <!-- ques1 -->
                    <div class="row g-3 align-items-center pt-2">
                        <h5 class="text-justify">
                            <strong>1. Technical Work Quality:</strong>
                        </h5>
                    </div>
                    <div class="row g-3 align-items-center pt-0">
                        <h6 class="text-justify">
                            Actively seek and suggest solutions to problems related to the project. Work is correct, precise and complete. 
                            The equations/ graphs/ notes are clear and understandable.
                        </h6>
                    </div>
                    <div class="text-center mb-3">
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait1" id="trait1" value="5" <?php echo (isset($peerRecord[0]['trait1']) && $peerRecord[0]['trait1'] == 5) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait1">5</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait1" id="trait1" value="4" <?php echo (isset($peerRecord[0]['trait1']) && $peerRecord[0]['trait1'] == 4) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait1">4</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait1" id="trait1" value="3" <?php if(!isset($peerRecord[0]['trait1'])){echo "checked";} ?><?php echo (isset($peerRecord[0]['trait1']) && $peerRecord[0]['trait1'] == 3) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait1">3</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait1" id="trait1" value="2" <?php echo (isset($peerRecord[0]['trait1']) && $peerRecord[0]['trait1'] == 2) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait1">2</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait1" id="trait1" value="1" <?php echo (isset($peerRecord[0]['trait1']) && $peerRecord[0]['trait1'] == 1) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait1">1</label>
                        </div>
                    </div>
                    <!-- ques2 -->
                    <div class="row g-3 align-items-center pt-2">
                        <h5 class="text-justify">
                            <strong>2. Team Project Commitment</strong>
                        </h5>
                    </div>
                    <div class="row g-3 align-items-center pt-0">
                        <h6 class="text-justify">
                        Attends all meetings. Arrives on time or early. Prepared for the meeting. Ready to work. 
                        Dependable, faithful and reliable.
                        </h6>
                    </div>
                    <div class="text-center mb-3">
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait2" id="trait2" value="5" <?php echo (isset($peerRecord[0]['trait2']) && $peerRecord[0]['trait2'] == 5) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait2">5</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait2" id="trait2" value="4" <?php echo (isset($peerRecord[0]['trait2']) && $peerRecord[0]['trait2'] == 4) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait2">4</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait2" id="trait2" value="3" <?php if(!isset($peerRecord[0]['trait2'])){echo "checked";} ?><?php echo (isset($peerRecord[0]['trait2']) && $peerRecord[0]['trait2'] == 3) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait2">3</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait2" id="trait2" value="2" <?php echo (isset($peerRecord[0]['trait2']) && $peerRecord[0]['trait2'] == 2) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait2">2</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait2" id="trait2" value="1" <?php echo (isset($peerRecord[0]['trait2']) && $peerRecord[0]['trait2'] == 1) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait2">1</label>
                        </div>
                    </div>
                    <!-- ques3 -->
                    <div class="row g-3 align-items-center pt-2">
                        <h5 class="text-justify">
                            <strong>3. Responsibility:</strong>
                        </h5>
                    </div>
                    <div class="row g-3 align-items-center pt-0">
                        <h6 class="text-justify">
                        Gladly accepts work and gets it completed on time. Spirit of excellence. 
                        Consistently focus on the task given and very self-directed.
                        </h6>
                    </div>
                    <div class="text-center mb-3">
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait4" id="trait4" value="5" <?php echo (isset($peerRecord[0]['trait4']) && $peerRecord[0]['trait4'] == 5) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait4">5</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait4" id="trait4" value="4"<?php echo (isset($peerRecord[0]['trait4']) && $peerRecord[0]['trait4'] == 4) ? "checked" : ''; ?> />
                        <label class="form-check-label" for="trait4">4</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait4" id="trait4" value="3" <?php if(!isset($peerRecord[0]['trait4'])){echo "checked";} ?><?php echo (isset($peerRecord[0]['trait4']) && $peerRecord[0]['trait4'] == 3) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait4">3</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait4" id="trait4" value="2" <?php echo (isset($peerRecord[0]['trait4']) && $peerRecord[0]['trait4'] == 2) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait4">2</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait4" id="trait4" value="1" <?php echo (isset($peerRecord[0]['trait4']) && $peerRecord[0]['trait4'] == 1) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait4">1</label>
                        </div>
                    </div>
                    <!-- ques4 -->
                    <div class="row g-3 align-items-center pt-2">
                        <h5 class="text-justify">
                            <strong>4. Contributions:</strong>
                        </h5>
                    </div>
                    <div class="row g-3 align-items-center pt-0">
                        <h6 class="text-justify">
                        Provides valuable ideas and has the skills the team needs. 
                        Make the most of these skills. Gives total effort and doesn't hold back.
                        </h6>
                    </div>
                    <div class="text-center mb-3">
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait5" id="trait5" value="5" <?php echo (isset($peerRecord[0]['trait5']) && $peerRecord[0]['trait5'] == 5) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait5">5</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait5" id="trait5" value="4" <?php echo (isset($peerRecord[0]['trait5']) && $peerRecord[0]['trait5'] == 4) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait5">4</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait5" id="trait5" value="3" <?php if(!isset($peerRecord[0]['trait5'])){echo "checked";} ?><?php echo (isset($peerRecord[0]['trait5']) && $peerRecord[0]['trait5'] == 3) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait5">3</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait5" id="trait5" value="2" <?php echo (isset($peerRecord[0]['trait5']) && $peerRecord[0]['trait5'] == 2) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait5">2</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait5" id="trait5" value="1" <?php echo (isset($peerRecord[0]['trait5']) && $peerRecord[0]['trait5'] == 1) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait5">1</label>
                        </div>
                    </div>
                    <!-- ques5 -->
                    <div class="row g-3 align-items-center pt-2">
                        <h5 class="text-justify">
                            <strong>5. Communication:</strong>
                        </h5>
                    </div>
                    <div class="row g-3 align-items-center pt-0">
                        <h6 class="text-justify">
                        Communicates clearly in terms of speaking and writing. Understands the team's direction.
                        </h6>
                    </div>
                    <div class="text-center mb-3">
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait6" id="trait6" value="5" <?php echo (isset($peerRecord[0]['trait6']) && $peerRecord[0]['trait6'] == 5) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait6">5</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait6" id="trait6" value="4" <?php echo (isset($peerRecord[0]['trait6']) && $peerRecord[0]['trait6'] == 4) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait6">4</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait6" id="trait6" value="3" <?php if(!isset($peerRecord[0]['trait6'])){echo "checked";} ?><?php echo (isset($peerRecord[0]['trait6']) && $peerRecord[0]['trait6'] == 3) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait6">3</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait6" id="trait6" value="2" <?php echo (isset($peerRecord[0]['trait6']) && $peerRecord[0]['trait6'] == 2) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait6">2</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait6" id="trait6" value="1" <?php echo (isset($peerRecord[0]['trait6']) && $peerRecord[0]['trait6'] == 1) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait6">1</label>
                        </div>
                    </div>
                    <!-- ques6 -->
                    <div class="row g-3 align-items-center pt-2">
                        <h5 class="text-justify">
                            <strong>6. Personality:</strong>
                        </h5>
                    </div>
                    <div class="row g-3 align-items-center pt-0">
                        <h6 class="text-justify">
                        Positive attitudes. Encourage others. Seeks consensus. Fun person to deal with. 
                        Bringing out the best in others. Peacemaker; pours water, not gasoline on fires.
                        </h6>
                    </div>
                    <div class="text-center mb-3">
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait7" id="trait7" value="5" <?php echo (isset($peerRecord[0]['trait7']) && $peerRecord[0]['trait7'] == 5) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait7">5</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait7" id="trait7" value="4" <?php echo (isset($peerRecord[0]['trait7']) && $peerRecord[0]['trait7'] == 4) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait7">4</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait7" id="trait7" value="3" <?php if(!isset($peerRecord[0]['trait7'])){echo "checked";} ?><?php echo (isset($peerRecord[0]['trait7']) && $peerRecord[0]['trait7'] == 3) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait7">3</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait7" id="trait7" value="2" <?php echo (isset($peerRecord[0]['trait7']) && $peerRecord[0]['trait7'] == 2) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait7">2</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input required class="form-check-input" type="radio" name="trait7" id="trait7" value="1" <?php echo (isset($peerRecord[0]['trait7']) && $peerRecord[0]['trait7'] == 1) ? "checked" : ''; ?>/>
                        <label class="form-check-label" for="trait7">1</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 py-2 custom-textbox-size">
                    <label for="comment" class="form-label">
                        <strong>7. Put any comments you like here:</strong>
                    </label>
                    <textarea class="form-control" id="comment" name="comment" rows="3"><?php echo (isset($peerRecord[0]['comment'])) ? htmlspecialchars($peerRecord[0]['comment']) : ''; ?></textarea>
                </div>
                <div class="py-2 d-flex justify-content-center" >
                    <button type="submit" id="submit" name="submit" class="btn btn-block mt-2 btn-custom-shadow custom-content-btn custom-btn-size">SUBMIT</button>
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
    </script>
    <?php mysqli_close($conn); ?>
</body>
</html>