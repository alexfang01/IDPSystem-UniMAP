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
$svid = $_SESSION['id'];
$sqlUser = "SELECT * FROM supervisor WHERE svid = '$svid'";
$resultUser = mysqli_query($conn, $sqlUser);
$infoUser = mysqli_fetch_all($resultUser, MYSQLI_ASSOC); // convert to associate array
mysqli_free_result($resultUser);

    $_SESSION['approved'] = 0;
    $_SESSION['submitted'] = 0;

    $context =  "finalreport";

    // Due date time information
    date_default_timezone_set('Asia/Singapore'); // ensure it is GMT +8
    $sqlDuetime = "SELECT duedate.dueDateTime FROM duedate INNER JOIN context ON duedate.contid = context.contid WHERE context = 'final report'";
    $resultDuetime = mysqli_query($conn, $sqlDuetime);

    if(mysqli_num_rows($resultDuetime) > 0){
        $infoDuetime = mysqli_fetch_all($resultDuetime, MYSQLI_ASSOC); // convert to associate array
        $duetimeString = $infoDuetime[0]['dueDateTime'];
        $duetime = DateTime::createFromFormat('m/d/Y g:i A', $duetimeString);

    }
    mysqli_free_result($resultDuetime);

    if($infoUser[0]['grpid'] != ""){
        $flag_nogroup = 0;
        $grpidTemp = $infoUser[0]['grpid'];
        $grpid = mysqli_real_escape_string($conn, $grpidTemp);
        
        //information on the file submitted
        $sqlFile = "SELECT * FROM idpfile WHERE grpid = '$grpid' AND type = 'finalreport'";
        $resultFile = mysqli_query($conn, $sqlFile);
        $infoFile = mysqli_fetch_all($resultFile, MYSQLI_ASSOC);
        
        if(mysqli_num_rows($resultFile) > 0){
            $_SESSION['fileid'] = $infoFile[0]['fileid'];
            if(!empty($infoFile)){
                if (isset($infoFile[0]['approved'])) {
                    if($infoFile[0]['approved'] == 1){
                        $flag_approved = 1;
                        $_SESSION['approved'] = 1; // for upload.php
                    }
                }
                $flag_submitted = 1;
                $_SESSION['submitted'] = 1;

                if($infoFile[0]['comment'] != ""){
                    $comment = $infoFile[0]['comment'];
                }
            }
            
        }
        mysqli_free_result($resultFile);
    }

    // comment and return to student
    if (isset($_POST['comment'])) {

        $fileid = $_SESSION['fileid'];
        $comment = mysqli_real_escape_string($conn, $_POST['commentText']);

        $sql="UPDATE idpfile SET comment='$comment', submitted=0 WHERE fileid='$fileid'";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Comment Successfully');</script>";
        } else {
            echo 'Query error: ' . mysqli_error($conn);
        }

        //file information to ensure it fill the textbox
        $sqlFile = "SELECT * FROM idpfile WHERE grpid = '$grpid' AND type = 'finalreport'";
        $resultFile = mysqli_query($conn, $sqlFile);
        $infoFile = mysqli_fetch_all($resultFile, MYSQLI_ASSOC);
        mysqli_free_result($resultFile);
    }

    // approved the proposal
    if (isset($_POST['approve'])) {

        $fileid = $_SESSION['fileid'];
        $sql="UPDATE idpfile SET approved = '1' WHERE fileid='$fileid'";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Project Report Approved, Student no longer able to edit');</script>";
        } else {
            echo 'Query error: ' . mysqli_error($conn);
        }
        //file information to ensure it fill the textbox
        $sqlFile = "SELECT * FROM idpfile WHERE grpid = '$grpid' AND type = 'finalreport'";
        $resultFile = mysqli_query($conn, $sqlFile);
        $infoFile = mysqli_fetch_all($resultFile, MYSQLI_ASSOC);
        mysqli_free_result($resultFile);
    }

    // reject the proposal for some reasons
    if (isset($_POST['reject'])) {

        $fileid = $_SESSION['fileid'];
        $sql="UPDATE idpfile SET approved = '0' WHERE fileid='$fileid'";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Project Report Rejected, Student able to make changes');</script>";
        } else {
            echo 'Query error: ' . mysqli_error($conn);
        }
        //file information to ensure it fill the textbox
        $sqlFile = "SELECT * FROM idpfile WHERE grpid = '$grpid' AND type = 'finalreport'";
        $resultFile = mysqli_query($conn, $sqlFile);
        $infoFile = mysqli_fetch_all($resultFile, MYSQLI_ASSOC);
        mysqli_free_result($resultFile);
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

        .report-form {
            width: 90%; /* Adjust the width of the form */
            max-width: 1100px; /* Set a maximum width if needed */
        }

        .multi-button{
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .rounded-table {
            width: 85%;
            border-collapse: collapse;
            border-radius: 20px; /* Adjust the value to change the roundness */
            overflow: hidden; /* Ensures the border-radius is applied to the table */
        }

        /* Style the table headers */
        .rounded-table th {
            background-color: #f2f2f2;
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: center;
        }

        /* Style the table cells */
        .rounded-table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: center;
        }
        
        .btn-custom-action{
            width: 120px;
            color: white;

        }
        
        .sticky-textbox{
            right: 0;
            width: calc(100% - 52px - 65px); /* Adjust as needed */
            margin-left: 52px; /* Adjust as needed */
            margin-right: 65px; /* Adjust as needed */
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
            font-size: 13px;
        }

        .report-form {
            width: 100%; /* Adjust the width of the form */
            max-width:700px; /* Set a maximum width if needed */
            font-size: 13px;
        }

        .multi-button{
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .rounded-table {
            width: 85%;
            border-collapse: collapse;
            border-radius: 20px; /* Adjust the value to change the roundness */
            overflow: hidden; /* Ensures the border-radius is applied to the table */
            font-size: 13px;
        }

        /* Style the table headers */
        .rounded-table th {
            background-color: #f2f2f2;
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: center;
            font-size: 13px;
        }

        /* Style the table cells */
        .rounded-table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: center;
            font-size: 13px;
        }
        
        .btn-custom-action{
            width: 30px;
            color: white;
            border-radius: 10px;

        }
        
        .sticky-textbox{
            width: calc(100% - 5px - 5px);
            margin-left: 5px;
            margin-right: 5px;
            font-size: 13px;
        }

        .custom-display{
            display: none;
        }
        
        .custom-btn-size{
            width: 80px;
            font-size: 12px;
        }

        .form-control{
            font-size: 13px;
        }

    }

    </style>
        <div class="main">
            <!-- Navigation bar part -->
            <?php include('navDisplay.php');?>
            <!-- nav bar end -->
            <main class="content px-3 py-2">
            
            <div class="container">
                <div class="report-form">
                    <div class="title-container">
                        <h1 class="text-center">Final Report</h1>
                    </div>
                    <!-- due date message --><p class="mt-2 text-center"><span class="due-msg">Due date: <?php echo isset($duetimeString)?htmlspecialchars($duetimeString):'';?></span></p>
                </div>
            </div>
            <div class="container">
                <table class="table rounded-table mt-5">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Time Submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php
                        // Display the uploaded files and download links
                        if (isset($infoFile)) {
                            foreach($infoFile as $row){
                                $file_path = "../fileuploaded/".$context."/".$grpid.$context.$row['filename'];
                                ?>
                                <tr>
                                    <td><?php echo $row['filename']; ?></td>
                                    <td><?php echo $row['submitTime']; ?></td>
                                    <td>
                                        <a href="<?php echo $file_path; ?>" class="btn btn-sm btn-custom-action" style="background-color: #2FAB74;" target="_blank"><i class="fa-solid fa-file-arrow-down pe-1"></i><span class="custom-display">View</span></a>
                                    
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="4">No files uploaded yet.</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="container">
                <form class="report-form" action="finalreport.php" method="post">
                    <div class="mb-3 py-2 sticky-textbox">
                        <label for="comment" class="form-label">Comment :</label>
                        <textarea class="form-control " id="commentText" name="commentText" rows="5"><?php echo isset($infoFile[0]['comment'])? $infoFile[0]['comment']:'';?></textarea>
                    </div>
                    <!-- button -->
                    <div class="py-1 d-flex justify-content-center" >
                        <div class="row">
                            <?php if(isset($infoFile[0]['approved'])): ?>
                                <div class="col">
                                <button type="submit" id="comment" name="comment" class="btn btn-block btn-custom-shadow custom-content-btn custom-btn-size">COMMENT</button>
                                </div>

                                <?php if($infoFile[0]['approved'] == 0): ?>
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

            <div class="container mt-5">
                <a id="download-pdf" class="btn btn-primary btn-custom-shadow" href="../fileuploaded/rubrics/rubric_report.pdf" download="rubric_report.pdf" style="background-color: #4CE833; border: none; color: black;"><i class="fa-solid fa-file-arrow-down pe-1"></i><span class="custom-display">Download</span> Rubrics</a>
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
        
        function confirmDelete() {
            return confirm("Are you sure you want to delete this file?");
        }

        const url = '../fileuploaded/rubrics/rubric_report.pdf';

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