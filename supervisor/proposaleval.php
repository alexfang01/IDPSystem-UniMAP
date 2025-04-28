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


if($infoUser[0]['grpid'] != ""){
    $grpidTemp = $infoUser[0]['grpid'];
    $grpid = mysqli_real_escape_string($conn, $grpidTemp);

    // check submitted proposal mark information
    $sql = "SELECT * FROM proposalmark WHERE grpid='$grpid'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        $proposalRecord= mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    if(isset($_POST['submit'])){

        $trait1 = mysqli_real_escape_string($conn,$_POST['trait1']);
        $trait2 = mysqli_real_escape_string($conn,$_POST['trait2']);
        $trait3 = mysqli_real_escape_string($conn,$_POST['trait3']);
        $trait4 = mysqli_real_escape_string($conn,$_POST['trait4']);

        // save the submit time into the database
        $current_date_time = new DateTime();
        $current_date_time_string = $current_date_time->format('m/d/Y g:i A');
        $submitTime = mysqli_real_escape_string($conn, $current_date_time_string);


        // calculating mark for proposal
        $trait1mark = $trait1*0.3;
        $trait2mark = $trait2*0.4;
        $trait3mark = $trait3*0.2;
        $trait4mark = $trait4*0.1;

        $totalMark = $trait1mark  + $trait2mark  + $trait3mark  + $trait4mark;
        $finalMarkTemp =  ($totalMark/4)*10; 
        $finalMark =  mysqli_real_escape_string($conn,$finalMarkTemp);
    
            
        if(isset($proposalRecord)){
            // if set means need update only!
            $sql = "UPDATE proposalmark SET trait1='$trait1', trait2='$trait2', trait3='$trait3', trait4='$trait4', submitTime='$submitTime' WHERE grpid='$grpid'";
            if (mysqli_query($conn, $sql)) {
                // update mark in proposal table
                $sql = "UPDATE grpmark SET proposalMark='$finalMark' WHERE grpid = '$grpid'";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Edit Successfully');</script>";
                    echo "<script>window.location.href = 'proposal.php';</script>";
                } else {
                    echo 'query error'.mysqli_error($conn);
                }
            } else {
                echo 'query error'.mysqli_error($conn);
            }
        }else{
            // perform data insertion
            $sql = "INSERT INTO proposalmark (grpid, trait1, trait2, trait3, trait4, submitTime) VALUES ('$grpid', '$trait1', '$trait2', '$trait3', '$trait4', '$submitTime')";
            
            if (mysqli_query($conn, $sql)) {
                //check whether the data is created or not
                $sql = "SELECT * FROM grpmark WHERE grpid='$grpid'";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0){
                    //exist means update, not insert
                    $sql = "UPDATE grpmark SET proposalmark='$finalMark' WHERE grpid = '$grpid'";
                    if (mysqli_query($conn, $sql)) {
                        echo "<script>alert('Evaluate Successfully');</script>";
                        echo "<script>window.location.href = 'proposal.php';</script>";
                    }else {
                        echo 'query error'.mysqli_error($conn);
                    }
                }else{
                    $sql = "INSERT INTO grpmark (proposalmark, grpid) VALUES ('$finalMark', '$grpid')";
                    if (mysqli_query($conn, $sql)) {
                        echo "<script>alert('Successfully evaluate');</script>";
                        echo "<script>window.location.href = 'proposal.php';</script>";
                    }else {
                        echo 'query error'.mysqli_error($conn);
                    }
                }
            
            } else {
                echo 'query error'.mysqli_error($conn);
            }
        }
        mysqli_free_result($result);
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
                    <h1 class="text-center">Proposal Evaluation</h1>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="row g-3 align-items-center py-2">
                        <h5 class="text-justify text-center">
                            Please refer the rubrics given below
                        </h5>
                    </div>
                </div>
                <div class="container-custom">
                <!-- ques1 -->
                <div class="row g-3 align-items-center py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>1. Project Background (30%)</strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input required" type="radio" name="trait1" id="trait1" value="1" <?php echo (isset($proposalRecord[0]['trait1']) && $proposalRecord[0]['trait1'] == 1)?"checked":'';?>/>
                            <label class="form-check-label" for="trait1">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input required" type="radio" name="trait1" id="trait1" value="2" <?php echo (isset($proposalRecord[0]['trait1']) && $proposalRecord[0]['trait1'] == 2)?"checked":'';?>/>
                            <label class="form-check-label" for="trait1">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input required" type="radio" name="trait1" id="trait1" value="3" <?php echo (isset($proposalRecord[0]['trait1']) && $proposalRecord[0]['trait1'] == 3)?"checked":'';?>/>
                            <label class="form-check-label" for="trait1">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input required" type="radio" name="trait1" id="trait1" value="4" <?php echo (isset($proposalRecord[0]['trait1']) && $proposalRecord[0]['trait1'] == 4)?"checked":'';?>/>
                            <label class="form-check-label" for="trait1">4&emsp;</label>
                        </div>
                    </div>
                </div>
                <!-- ques2 -->
                <div class="row g-3 align-items-left py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>2. Benefit to the targeted group/society (40%)</strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input required" type="radio" name="trait2" id="trait2" value="1" <?php echo (isset($proposalRecord[0]['trait2']) && $proposalRecord[0]['trait2'] == 1)?"checked":'';?>/>
                            <label class="form-check-label" for="trait2">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input required" type="radio" name="trait2" id="trait2" value="2" <?php echo (isset($proposalRecord[0]['trait2']) && $proposalRecord[0]['trait2'] == 2)?"checked":'';?>/>
                            <label class="form-check-label" for="trait2">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input required" type="radio" name="trait2" id="trait2" value="3"<?php echo (isset($proposalRecord[0]['trait2']) && $proposalRecord[0]['trait2'] == 3)?"checked":'';?> />
                            <label class="form-check-label" for="trait2">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input required" type="radio" name="trait2" id="trait2" value="4" <?php echo (isset($proposalRecord[0]['trait2']) && $proposalRecord[0]['trait2'] == 4)?"checked":'';?>/>
                            <label class="form-check-label" for="trait2">4&emsp;</label>
                        </div>
                    </div>
                </div>
                <!-- ques3 -->
                <div class="row g-3 align-items-center py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>3. Project Planning (Gantt Chart) (20%)</strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input required" type="radio" name="trait3" id="trait3" value="1" <?php echo (isset($proposalRecord[0]['trait3']) && $proposalRecord[0]['trait3'] == 1)?"checked":'';?>/>
                            <label class="form-check-label" for="trait3">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input required" type="radio" name="trait3" id="trait3" value="2" <?php echo (isset($proposalRecord[0]['trait3']) && $proposalRecord[0]['trait3'] == 2)?"checked":'';?>/>
                            <label class="form-check-label" for="trait3">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input required" type="radio" name="trait3" id="trait3" value="3"<?php echo (isset($proposalRecord[0]['trait3']) && $proposalRecord[0]['trait3'] == 3)?"checked":'';?>/>
                            <label class="form-check-label" for="trait3">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input required" type="radio" name="trait3" id="trait3" value="4" <?php echo (isset($proposalRecord[0]['trait3']) && $proposalRecord[0]['trait3'] == 4)?"checked":'';?>/>
                            <label class="form-check-label" for="trait3">4&emsp;</label>
                        </div>
                    </div>
                </div>
                <!-- ques4 -->
                <div class="row g-3 align-items-left py-2">
                    <div class="text-justify mb-3">
                        <div>
                        <strong>4. Writing Format (10%) </strong>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input required" type="radio" name="trait4" id="trait4" value="1" <?php echo (isset($proposalRecord[0]['trait4']) && $proposalRecord[0]['trait4'] == 1)?"checked":'';?>/>
                            <label class="form-check-label" for="trait4">1&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input required" type="radio" name="trait4" id="trait4" value="2" <?php echo (isset($proposalRecord[0]['trait4']) && $proposalRecord[0]['trait4'] == 2)?"checked":'';?>/>
                            <label class="form-check-label" for="trait4">2&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input required" type="radio" name="trait4" id="trait4" value="3" <?php echo (isset($proposalRecord[0]['trait4']) && $proposalRecord[0]['trait4'] == 3)?"checked":'';?>/>
                            <label class="form-check-label" for="trait4">3&emsp;</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input required class="form-check-input required" type="radio" name="trait4" id="trait4" value="4" <?php echo (isset($proposalRecord[0]['trait4']) && $proposalRecord[0]['trait4'] == 4)?"checked":'';?>/>
                            <label class="form-check-label" for="trait4">4&emsp;</label>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 py-2 custom-textbox-size">
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

        const url = '../fileuploaded/rubrics/rubric_proposal.pdf';

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