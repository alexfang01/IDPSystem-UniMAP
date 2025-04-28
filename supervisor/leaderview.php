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
$svid = $_SESSION['id'];
$sqlUser = "SELECT * FROM supervisor WHERE svid = '$svid'";
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
        $sql = "SELECT * FROM peersv WHERE svid='$svid' AND studentid='$memberid'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            $peerRecord= mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        mysqli_free_result($result);
    }

    if(isset($_POST['submit'])){

        $leadtrait = mysqli_real_escape_string($conn,$_POST['leadtrait']);
        $trait2 = mysqli_real_escape_string($conn,$_POST['trait2']);
        $trait3 = mysqli_real_escape_string($conn,$_POST['trait3']);
        $trait4 = mysqli_real_escape_string($conn,$_POST['trait4']);
        $trait5 = mysqli_real_escape_string($conn,$_POST['trait5']);
        $comment = mysqli_real_escape_string($conn,$_POST['comment']);
        $memberid =  mysqli_real_escape_string($conn,$_SESSION['memberid']);

        // save the submit time into the database
        $current_date_time = new DateTime();
        $current_date_time_string = $current_date_time->format('m/d/Y g:i A');
        $submitTime = mysqli_real_escape_string($conn, $current_date_time_string);

        // check submitted peer information
        $sql = "SELECT * FROM peersv WHERE svid='$svid' AND studentid='$memberid'";
        $resultSubmit = mysqli_query($conn, $sql);
        if(mysqli_num_rows($resultSubmit) > 0){
            $peerRecord= mysqli_fetch_all($resultSubmit, MYSQLI_ASSOC);
        }
        mysqli_free_result($resultSubmit);

        // calculating mark for SV peer review
        $leadtraitmark = $leadtrait*(7/15); // contribute 3.5%
        $trait2mark = $trait2*(2/15);
        $trait3mark = $trait3*(2/15);
        $trait4mark = $trait4*(2/15);
        $trait5mark= $trait5*(2/15);

        $totalMark = $leadtraitmark  + $trait2mark  + $trait3mark  + $trait4mark  + $trait5mark;
        $finalMarkTemp =  ($totalMark/5)*7.5; 
        $finalMark =  mysqli_real_escape_string($conn,$finalMarkTemp);
    

        // trait can be zero/empty
        if(!empty($memberid) && !empty($svid)){
            
            if(isset($peerRecord)){
                // if set means need update only!
                $sql = "UPDATE peersv SET leadtrait='$leadtrait', trait2='$trait2', trait3='$trait3', trait4='$trait4', trait5='$trait5', timeSubmit='$submitTime', comment='$comment' WHERE svid='$svid' AND studentid='$memberid' AND grpid='$grpid'";
                if (mysqli_query($conn, $sql)) {
                    // update mark in peermark table
                    $sql = "UPDATE peermark SET peersvMark='$finalMark' WHERE studentid = '$memberid'";
                    if (mysqli_query($conn, $sql)) {
                        echo "<script>alert('Edit Successfully');</script>";
                        echo "<script>window.location.href = 'peer.php';</script>";
                    } else {
                        echo 'query error'.mysqli_error($conn);
                    }
                } else {
                    echo 'query error'.mysqli_error($conn);
                }
            }else{
                // perform data insertion
                $sql = "INSERT INTO peersv (svid, studentid, leadtrait, trait2, trait3, trait4, trait5, comment, timeSubmit, grpid) VALUES ('$svid', '$memberid', '$leadtrait', '$trait2', '$trait3', '$trait4', '$trait5', '$comment', '$submitTime', '$grpid')";
                
                if (mysqli_query($conn, $sql)) {
                    //check whether the data is created or not
                    $sql = "SELECT * FROM peermark WHERE studentid='$memberid'";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0){
                        //exist means update, not insert
                        $sql = "UPDATE peermark SET peersvMark='$finalMark' WHERE studentid = '$memberid'";
                        if (mysqli_query($conn, $sql)) {
                            echo "<script>alert('Evaluate Successfully');</script>";
                            echo "<script>window.location.href = 'peer.php';</script>";
                        }else {
                            echo 'query error'.mysqli_error($conn);
                        }
                    }else{
                        $sql = "INSERT INTO peermark (studentid, peersvMark) VALUES ('$memberid', '$finalMark')";
                        if (mysqli_query($conn, $sql)) {
                            echo "<script>alert('Successfully evaluate');</script>";
                            echo "<script>window.location.href = 'peer.php';</script>";
                        }else {
                            echo 'query error'.mysqli_error($conn);
                        }
                    }
                
                } else {
                    echo 'query error'.mysqli_error($conn);
                }
            }
            
        }else{
            // debug use
        }
        
    }
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
            width: calc(100% -0px - 208px); /* Adjust as needed */
            margin-left: 0px; /* Adjust as needed */
            margin-right: 208px; /* Adjust as needed */
        }

        .custom-btn-size{
            width:30%;
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

        .form-check{
            font-size: 11px;
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
                    <div class="row g-3 align-items-center py-2">
                        <h5 class="text-justify">
                            Use 1 to 5 point <strong>SCORE: 0-NON-CONTRIBUTION, 1-POOR, 2-NEED IMPROVEMENT, 3-MODERATE, 4-GOOD, 5-EXCELLENT</strong>
                        </h5>
                    </div>
                </div>
                    <h5 class="text-center py-3">
                        <?php if(!$flag_nogroup): ?>
                            <strong>
                                You are now evaluating<?php echo isset($memberinfo[0]['name'])?strtoupper(" ".$memberinfo[0]['name']):'';?>
                            </strong>   
                        <?php endif ?>
                    </h5>
                <div class="container-custom">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="leader" class="col-form-label">Leader : <?php echo isset($memberinfo[0]['name'])?strtoupper(" ".$memberinfo[0]['name']):'';?></label>
                    </div>
                </div>
                <!-- ques1 -->
                <div class="row g-3 align-items-center py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>1. Leadership Skill </strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input" type="radio" name="leadtrait" id="leadtrait" value="0" <?php echo (isset($peerRecord[0]['leadtrait']) && $peerRecord[0]['leadtrait'] == 0)?"checked":'';?>/>
                            <label class="form-check-label" for="leadtrait">0&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input" type="radio" name="leadtrait" id="leadtrait" value="1" <?php echo (isset($peerRecord[0]['leadtrait']) && $peerRecord[0]['leadtrait'] == 1)?"checked":'';?>/>
                            <label class="form-check-label" for="leadtrait">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input" type="radio" name="leadtrait" id="leadtrait" value="2" <?php echo (isset($peerRecord[0]['leadtrait']) && $peerRecord[0]['leadtrait'] == 2)?"checked":'';?>/>
                            <label class="form-check-label" for="leadtrait">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input" type="radio" name="leadtrait" id="leadtrait" value="3" <?php echo (isset($peerRecord[0]['leadtrait']) && $peerRecord[0]['leadtrait'] == 3)?"checked":'';?>/>
                            <label class="form-check-label" for="leadtrait">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input" type="radio" name="leadtrait" id="leadtrait" value="4" <?php echo (isset($peerRecord[0]['leadtrait']) && $peerRecord[0]['leadtrait'] == 4)?"checked":'';?>/>
                            <label class="form-check-label" for="leadtrait">4&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required  class="form-check-input" type="radio" name="leadtrait" id="leadtrait" value="5" <?php echo (isset($peerRecord[0]['leadtrait']) && $peerRecord[0]['leadtrait'] == 5)?"checked":'';?>/>
                            <label class="form-check-label" for="leadtrait">5&emsp;</label>
                        </div>
                    </div>
                </div>
                <!-- ques2 -->
                <div class="row g-3 align-items-left py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>2. Quality of Work </strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input" type="radio" name="trait2" id="trait2" value="0" <?php echo (isset($peerRecord[0]['trait2']) && $peerRecord[0]['trait2'] == 0)?"checked":'';?>/>
                            <label class="form-check-label" for="trait2">0&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input" type="radio" name="trait2" id="trait2" value="1" <?php echo (isset($peerRecord[0]['trait2']) && $peerRecord[0]['trait2'] == 1)?"checked":'';?>/>
                            <label class="form-check-label" for="trait2">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input" type="radio" name="trait2" id="trait2" value="2" <?php echo (isset($peerRecord[0]['trait2']) && $peerRecord[0]['trait2'] == 2)?"checked":'';?>/>
                            <label class="form-check-label" for="trait2">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input" type="radio" name="trait2" id="trait2" value="3"<?php echo (isset($peerRecord[0]['trait2']) && $peerRecord[0]['trait2'] == 3)?"checked":'';?> />
                            <label class="form-check-label" for="trait2">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input" type="radio" name="trait2" id="trait2" value="4" <?php echo (isset($peerRecord[0]['trait2']) && $peerRecord[0]['trait2'] == 4)?"checked":'';?>/>
                            <label class="form-check-label" for="trait2">4&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input" type="radio" name="trait2" id="trait2" value="5" <?php echo (isset($peerRecord[0]['trait2']) && $peerRecord[0]['trait2'] == 5)?"checked":'';?>/>
                            <label class="form-check-label" for="trait2">5&emsp;</label>
                        </div>
                    </div>
                </div>
                <!-- ques3 -->
                <div class="row g-3 align-items-center py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>3. Communication Skill </strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required  class="form-check-input" type="radio" name="trait3" id="trait3" value="0" <?php echo (isset($peerRecord[0]['trait3']) && $peerRecord[0]['trait3'] == 0)?"checked":'';?>/>
                            <label class="form-check-label" for="trait3">0&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input  required class="form-check-input" type="radio" name="trait3" id="trait3" value="1" <?php echo (isset($peerRecord[0]['trait3']) && $peerRecord[0]['trait3'] == 1)?"checked":'';?>/>
                            <label class="form-check-label" for="trait3">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input  required class="form-check-input" type="radio" name="trait3" id="trait3" value="2" <?php echo (isset($peerRecord[0]['trait3']) && $peerRecord[0]['trait3'] == 2)?"checked":'';?>/>
                            <label class="form-check-label" for="trait3">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input  required class="form-check-input" type="radio" name="trait3" id="trait3" value="3"<?php echo (isset($peerRecord[0]['trait3']) && $peerRecord[0]['trait3'] == 3)?"checked":'';?>/>
                            <label class="form-check-label" for="trait3">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input  required class="form-check-input" type="radio" name="trait3" id="trait3" value="4" <?php echo (isset($peerRecord[0]['trait3']) && $peerRecord[0]['trait3'] == 4)?"checked":'';?>/>
                            <label class="form-check-label" for="trait3">4&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input  required class="form-check-input" type="radio" name="trait3" id="trait3" value="5" <?php echo (isset($peerRecord[0]['trait3']) && $peerRecord[0]['trait3'] == 5)?"checked":'';?>/>
                            <label class="form-check-label" for="trait3">5&emsp;</label>
                        </div>
                    </div>
                </div>
                <!-- ques4 -->
                <div class="row g-3 align-items-left py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>4. Task & Role </strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input  required class="form-check-input" type="radio" name="trait4" id="trait4" value="0" <?php echo (isset($peerRecord[0]['trait4']) && $peerRecord[0]['trait4'] == 0)?"checked":'';?>/>
                            <label class="form-check-label" for="trait4">0&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required  class="form-check-input" type="radio" name="trait4" id="trait4" value="1" <?php echo (isset($peerRecord[0]['trait4']) && $peerRecord[0]['trait4'] == 1)?"checked":'';?>/>
                            <label class="form-check-label" for="trait4">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required  class="form-check-input" type="radio" name="trait4" id="trait4" value="2" <?php echo (isset($peerRecord[0]['trait4']) && $peerRecord[0]['trait4'] == 2)?"checked":'';?>/>
                            <label class="form-check-label" for="trait4">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required  class="form-check-input" type="radio" name="trait4" id="trait4" value="3" <?php echo (isset($peerRecord[0]['trait4']) && $peerRecord[0]['trait4'] == 3)?"checked":'';?>/>
                            <label class="form-check-label" for="trait4">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input  required class="form-check-input" type="radio" name="trait4" id="trait4" value="4" <?php echo (isset($peerRecord[0]['trait4']) && $peerRecord[0]['trait4'] == 4)?"checked":'';?>/>
                            <label class="form-check-label" for="trait4">4&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input  required class="form-check-input" type="radio" name="trait4" id="trait4" value="5" <?php echo (isset($peerRecord[0]['trait4']) && $peerRecord[0]['trait4'] == 5)?"checked":'';?>/>
                            <label class="form-check-label" for="trait4">5&emsp;</label>
                        </div>
                    </div>
                </div>
                <!-- ques5 -->
                <div class="row g-3 align-items-left py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>5. Participation & Attitude </strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input  required class="form-check-input" type="radio" name="trait5" id="trait5" value="0" <?php echo (isset($peerRecord[0]['trait5']) && $peerRecord[0]['trait5'] == 0)?"checked":'';?> />
                            <label class="form-check-label" for="trait5">0&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required  class="form-check-input" type="radio" name="trait5" id="trait5" value="1" <?php echo (isset($peerRecord[0]['trait5']) && $peerRecord[0]['trait5'] == 1)?"checked":'';?>/>
                            <label class="form-check-label" for="trait5">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required  class="form-check-input" type="radio" name="trait5" id="trait5" value="2" <?php echo (isset($peerRecord[0]['trait5']) && $peerRecord[0]['trait5'] == 2)?"checked":'';?>/>
                            <label class="form-check-label" for="trait5">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required  class="form-check-input" type="radio" name="trait5" id="trait5" value="3" <?php echo (isset($peerRecord[0]['trait5']) && $peerRecord[0]['trait5'] == 3)?"checked":'';?>/>
                            <label class="form-check-label" for="trait5">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input  required class="form-check-input" type="radio" name="trait5" id="trait5" value="4" <?php echo (isset($peerRecord[0]['trait5']) && $peerRecord[0]['trait5'] == 4)?"checked":'';?>/>
                            <label class="form-check-label" for="trait5">4&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required  class="form-check-input" type="radio" name="trait5" id="trait5" value="5" <?php echo (isset($peerRecord[0]['trait5']) && $peerRecord[0]['trait5'] == 5)?"checked":'';?>/>
                            <label class="form-check-label" for="trait5">5&emsp;</label>
                        </div>
                    </div>
                </div>
                <!-- ques6 -->
                <div class="mb-3 py-2 custom-textbox-size">
                    <label for="comment1" class="form-label">
                        <strong>6. Put any comments you like here:</strong>
                    </label>
                    <textarea class="form-control" id="comment" name="comment" rows="3"><?php echo isset($peerRecord[0]['comment'])?htmlspecialchars($peerRecord[0]['comment']):''; ?></textarea>
                    <div class="py-2 d-flex justify-content-center" >
                        <button type="submit" id="submit" name="submit" class="btn btn-block mt-2 btn-custom-shadow custom-content-btn custom-btn-size">SUBMIT</button>
                    </div>
                </div>
    
                
            </form>       
            </div>
            </main>
                
                <div class="container">
                    <span class="prompt-msg"><p>ROTATE your screen horizontally if using smaller device to have better PDF view</p></span>
                </div>
                <div class="container mt-3">
                    <div id="pdf-container">
                        <canvas id="pdf-render"></canvas>
                    </div>
                </div>
        </div>
        
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>

    <script>
        const sidebarToggle = document.querySelector("#sidebar-toggle");
        sidebarToggle.addEventListener("click",function(){
            document.querySelector("#sidebar").classList.toggle("collapsed")
        });

        const url = '../fileuploaded/rubrics/rubric_peersv.pdf';

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
    <?php mysqli_close($conn); ?>
</body>
</html>