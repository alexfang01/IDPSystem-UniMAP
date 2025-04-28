<?php
// connect to database
include('../config/db_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// credential check
include('./loginCheck.php');

$title=$synopsis=$obj="";
$title_err=$syp_err=$obj_err=$submit_msg="";
$flag_submitted = 0; // if submit, then show different value of button
$flag_approved = 0; // if approved, then disabled the button and disallow to insert data into db
$flag_nogroup = 1; // if got group then allow normal form submission, else disable like approved case



// Getting user's information
$studentid = $_SESSION['id'];
$sqlUser = "SELECT * FROM student WHERE studentid = '$studentid'";
$resultUser = mysqli_query($conn, $sqlUser);
$infoUser = mysqli_fetch_all($resultUser, MYSQLI_ASSOC); // convert to associate array
mysqli_free_result($resultUser);

// assign grp id if exist
if($infoUser[0]['grpid'] != ""){
    $flag_nogroup = 0;
    $grpidTemp = $infoUser[0]['grpid'];
    $grpid = mysqli_real_escape_string($conn, $grpidTemp);

    // extracting supervisor data
    $sql = "SELECT name FROM supervisor WHERE grpid = '$grpid'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0){
        $info = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $svname = $info[0]['name'];
    }

    // extracting project theme
    $sql = "SELECT theme.title FROM idpgroup INNER JOIN theme ON idpgroup.themeid = theme.themeid WHERE grpid = '$grpid'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0){
        $info = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $theme = $info[0]['title'];
    }

    // extracting group number
    $sql = "SELECT grpnum FROM idpgroup WHERE grpid = '$grpid'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0){
        $info = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $grpnum = $info[0]['grpnum'];
    }

    // extracting leader information, if leader exist, then auto fill in the synopsis group member part, else no need to fill in
    $sql = "SELECT * FROM student WHERE grpid = '$grpid' AND leader = '1'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        $info = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $leadername = $info[0]['name'];
        $leadermatric = $info[0]['matric'];
        $leaderprog = $info[0]['prog'];

        // extract members info
        $sql = "SELECT * FROM student WHERE grpid = '$grpid'";
        $result = mysqli_query($conn, $sql);
        $member = array();
        if(mysqli_num_rows($result) > 0){
            $info = mysqli_fetch_all($result, MYSQLI_ASSOC);
            foreach ($info as $row){
                if($row['name'] != $leadername){
                    $member[] = $row['name'];
                    $membermatric[] = $row['matric'];
                    $memberprog[] = $row['prog'];
                }
            }
            
        }
    }
    mysqli_free_result($result);

    // Information of Submitted synopsis 
    $sqlSubmit = "SELECT * FROM idpsynopsis WHERE grpid = '$grpid'";
    $resultSubmit = mysqli_query($conn, $sqlSubmit);

    if (mysqli_num_rows($resultSubmit) > 0){
        $infoSubmit = mysqli_fetch_all($resultSubmit, MYSQLI_ASSOC); // convert to associate array
        // checking whether submitted or not
        if($infoSubmit[0]['submitted'] == 1){
            $flag_submitted = 1;
        }
        // checking whether approved or not
        if($infoSubmit[0]['approved'] == 1){
            $flag_approved = 1;
        }

        // extracting comment from Supervisor
        if($infoSubmit[0]['comment'] != ''){
            $comment = $infoSubmit[0]['comment'];
        }
    }
    
}





// Due date time information
date_default_timezone_set('Asia/Singapore'); // ensure it is GMT +8
$sqlDuetime = "SELECT duedate.dueDateTime FROM duedate INNER JOIN context ON duedate.contid = context.contid WHERE context = 'synopsis'";
$resultDuetime = mysqli_query($conn, $sqlDuetime);

if(mysqli_num_rows($resultDuetime) > 0){
    $infoDuetime = mysqli_fetch_all($resultDuetime, MYSQLI_ASSOC); // convert to associate array
    $duetimeString = $infoDuetime[0]['dueDateTime'];

    $duetime = DateTime::createFromFormat('m/d/Y g:i A', $duetimeString);
}

if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $synopsis = mysqli_real_escape_string($conn, $_POST['synopsis']);
    $obj = mysqli_real_escape_string($conn, $_POST['obj']);
    $grpid = mysqli_real_escape_string($conn, $grpidTemp);

    if(empty($title)) {
        $title_err = "Please enter a title";
    }
    if(empty($synopsis)) {
        $syp_err = "Please enter synopsis";
    }
    if(empty($obj)) {
        $obj_err = "Please enter objective";
    }
    

    if (!$title_err && !$syp_err && !$obj_err && !$flag_approved && !$flag_nogroup) {
        // save the submit time into the database
        $current_date_time = new DateTime();
        $current_date_time_string = $current_date_time->format('m/d/Y g:i A');
        $submitTime = mysqli_real_escape_string($conn, $current_date_time_string);
        
        // show submit time message
        if(mysqli_num_rows($resultDuetime) > 0){
            // Check if current date and time is after the due date and time
            if ($current_date_time > $duetime) {
                $submit_msg = "Late Submission";
            }else{
                $submit_msg = "Submitted On Time";
            }
        }
        mysqli_free_result($resultDuetime);

        // Check if a record already exists for the given group id
        if (mysqli_num_rows($resultSubmit) > 0) {
            // Update existing record
            $sql = "UPDATE idpsynopsis SET title = '$title', synopsis = '$synopsis', objectives = '$obj', approved = 0, submitted = 1, submitTime = '$submitTime' WHERE grpid = $grpid";
        } else {
            // Insert new record
            $sql = "INSERT INTO idpsynopsis (grpid, title, synopsis, objectives, approved, submitted, submitTime) VALUES ($grpid, '$title', '$synopsis', '$obj', 0, 1, '$submitTime')";
        }
    
        // Execute the query
        if (mysqli_query($conn, $sql)) {
            // Set session variable for success message
            $_SESSION['success_message'] = "Synopsis Submitted, Wait for Approval";
            $flag_submitted = 1;
        } else {
            echo 'query error'.mysqli_error($conn);
        }
    }
    // Ensure the Textbox having the latest data
    $sqlSubmit = "SELECT * FROM idpsynopsis WHERE grpid = '$grpid'";
    $resultSubmit = mysqli_query($conn, $sqlSubmit);
    $infoSubmit = mysqli_fetch_all($resultSubmit, MYSQLI_ASSOC); // convert to associate array

    mysqli_free_result($resultSubmit);
}

    
    

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

        .member-textbox{
            /* width: 70%;
            margin-left: 195px;
            right: 0; */
        
        }

        .sticky-textbox {
            right: 0;
            width: calc(100% - 196px); /* Adjust as needed */
            margin-left: 196px; /* Adjust as needed */
        }

        .sticky-textbox-2{
            right: 0;
            width: calc(100% - 0px); /* Adjust as needed */
            margin-left: 0px; /* Adjust as needed */
        }

        .sticky-textbox-3{
            right: 0;
            width: calc(100% - 20px); /* Adjust as needed */
            margin-left: 20px; /* Adjust as needed */
        }

        .sticky-textbox-4{
            right: 0;
            width: calc(100% - 27px); /* Adjust as needed */
            margin-left: 27px; /* Adjust as needed */
        }

        .sticky-textbox-5{
            right: 0;
            width: calc(100% - 55px); /* Adjust as needed */
            margin-left: 55px; /* Adjust as needed */
        }

        .group-textbox{
            margin-left: 73px; 
            width: 10%;
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
            /* border: 3px solid red; */
        }

        .synopsis-form {
            width: 90%; /* Adjust the width of the form */
            max-width: 1100px; /* Set a maximum width if needed */
        }

        .member-textbox{
            /* width: 70%;
            margin-left: 195px;
            right: 0; */
        
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

        .group-textbox{
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
            <form class="synopsis-form" method="post" action="synopsis.php">
                <div class="title-container">
                    <h1 class="text-center">Synopsis</h1>
                </div>
                <!-- due date message --><p class="mt-2 text-center"><span class="due-msg">Due date: <?php echo isset($duetimeString)?htmlspecialchars($duetimeString):'';?></span></p>
                <div class="row g-3 align-items-center py-2">
                    <div class="col-auto">
                        <label for="groupno" class="col-form-label">Group No. :</label>
                    </div>
                    <div class="col">
                        <input type="number" class="form-control group-textbox" id="groupno" placeholder="#" value="<?php echo isset($grpnum)? $grpnum :'';?>" readonly>
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col-auto">
                    <label for="svname" class="col-form-label">Supervisor Name :</label>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control sticky-textbox-2" id="svname" placeholder="Supervisor Name" value="<?php echo isset($svname)? $svname :'';?>" readonly>
                    </div>
                </div>    
                <div class="row g-3 align-items-center py-2">
                    <div class="col-auto">
                    <label for="groupmem" class="col-form-label">Group Member :</label>
                    </div>
                    <div class="col">
                        <input readonly type="text" id="" class="form-control sticky-textbox-3" placeholder="Leader Name (Matric Num)(Programme)" value="<?php echo isset($leadername)? $leadername :'';?> <?php echo isset($leadermatric)? "(".$leadermatric.")" :'';?> <?php echo isset($leaderprog)? "(".$leaderprog.")" :'';?>" >
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="">
                        <input readonly type="text" class="form-control sticky-textbox" placeholder="Member Name (Matric Num)(Programme)" value="<?php echo isset($member[0])? $member[0] :'';?> <?php echo isset($membermatric[0])? "(".$membermatric[0].")" :'';?><?php echo isset($memberprog[0])? "(".$memberprog[0].")" :'';?>">
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="">
                        <input readonly type="text" class="form-control sticky-textbox" placeholder="Member Name (Matric Num)(Programme)" value="<?php echo isset($member[1])? $member[1] :'';?> <?php echo isset($membermatric[1])? "(".$membermatric[1].")" :'';?><?php echo isset($memberprog[1])? "(".$memberprog[1].")" :'';?>">
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="">
                        <input readonly type="text" class="form-control sticky-textbox" placeholder="Member Name (Matric Num)(Programme)" value="<?php echo isset($member[2])? $member[2] :'';?> <?php echo isset($membermatric[2])? "(".$membermatric[2].")" :'';?><?php echo isset($memberprog[2])? "(".$memberprog[2].")" :'';?>">
                    </div>
                </div>
                <div class="py-2">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="theme" class="col-form-label">Project Theme :</label>
                        </div>
                        <div class="col">
                            <input readonly type="text" id="theme" class="form-control sticky-textbox-4" placeholder="" value="<?php echo isset($theme)? $theme:'';?>">
                        </div>
                    </div>
                </div>
                <div class="py-2">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="title" class="col-form-label">Project Title :</label>
                        </div>
                        <div class="col">
                            <input type="text" id="title" name="title" class="form-control sticky-textbox-5" placeholder="" value="<?php echo isset($infoSubmit[0]['title']) ? htmlspecialchars($infoSubmit[0]['title']) : ''; ?>">
                        </div>
                        
                    </div>
                </div>
                <!-- error message--><p><span class="error-msg"><?php echo $title_err ?></span></p>
                <div class="mb-3 py-2">
                    <label for="synopsis" class="form-label">Project Synopsis :</label>
                    <textarea class="form-control" id="synopsis" name="synopsis" rows="5" ><?php echo isset($infoSubmit[0]['synopsis']) ? htmlspecialchars($infoSubmit[0]['synopsis']) : ''; ?></textarea>
                </div>
                <!-- error message--><p><span class="error-msg"><?php echo $syp_err ?></span></p>
                <div class="mb-3 py-2">
                    <label for="obj" class="form-label">Project Objectives :</label>
                    <textarea class="form-control" id="obj" name="obj" rows="5"><?php echo isset($infoSubmit[0]['objectives']) ? htmlspecialchars($infoSubmit[0]['objectives']) : ''; ?></textarea>
                </div>
                <!-- error message--><p><span class="error-msg"><?php echo $obj_err ?></span></p>
                <div class="mb-3 py-2">
                    <label for="comment" class="form-label">Comment from Supervisor :</label>
                    <textarea readonly class="form-control" id="comment" name="comment" rows="5"><?php echo isset($comment)? htmlspecialchars($comment):'';?></textarea>
                </div>
                <!-- Submission message--><p class="mt-2 text-center"><span class="submit-msg"><?php echo $submit_msg?></span></p>
                <div class="py-1 d-flex justify-content-center" >
                    <button type="submit" id="submit" name="submit" class="btn btn-block btn-custom-shadow custom-content-btn custom-btn-size"<?php echo $flag_approved || $flag_nogroup? "disabled" : ''; ?>><?php echo $flag_submitted ? "RESUBMIT" : "SUBMIT"; ?></button>
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

        <?php if(isset($_SESSION['success_message'])): ?>
            alert('<?php echo $_SESSION['success_message']; ?>');
            unset($_SESSION['success_message']);
        <?php endif; ?>
    </script>
</body>
<?php mysqli_close($conn); ?>
</html>