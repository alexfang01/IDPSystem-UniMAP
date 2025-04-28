<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    include("../config/db_connect.php");

    // credential check
    include('./loginCheck.php');

    

    // Getting user's information
    $studentid = $_SESSION['id'];
    $sqlUser = "SELECT * FROM student WHERE studentid = '$studentid'";
    $resultUser = mysqli_query($conn, $sqlUser);
    $infoUser = mysqli_fetch_all($resultUser, MYSQLI_ASSOC); // convert to associate array
    mysqli_free_result($resultUser);

    $flag_approved = 0;// if approved, then disabled the button and disallow to insert data into db
    $flag_submitted = 0; // only 1 file is upload at a time
    $flag_nogroup = 1; // if got group then allow normal form submission, else disable like approved case
    $flag_link_submitted = 0; // for the youtube link

    $_SESSION['approved'] = 0;
    $_SESSION['submitted'] = 0;

    // set this for delete.php
    $_SESSION['context'] = "vidposter";

    $context =  $_SESSION['context'];

    // Due date time information
    date_default_timezone_set('Asia/Singapore'); // ensure it is GMT +8
    $sqlDuetime = "SELECT duedate.dueDateTime FROM duedate INNER JOIN context ON duedate.contid = context.contid WHERE context = 'video and poster'";
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
        $sqlFile = "SELECT * FROM idpfile WHERE grpid = '$grpid' AND type = 'vidposter'";
        $resultFile = mysqli_query($conn, $sqlFile);
        $infoFile = mysqli_fetch_all($resultFile, MYSQLI_ASSOC);
        if(mysqli_num_rows($resultFile) > 0){
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

        // information on the video link
        $sqlvidlink = "SELECT * FROM videolink WHERE grpid = '$grpid'";
        $resultvidlink = mysqli_query($conn, $sqlvidlink);
        $infovidlink = mysqli_fetch_all($resultvidlink, MYSQLI_ASSOC);
        if(mysqli_num_rows($resultvidlink) > 0){
            if($infovidlink[0]['submitted'] == 1){
                $flag_link_submitted = 1;
            }
            
        }
        mysqli_free_result($resultvidlink);
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

        .vidposter-form {
            width: 90%; /* Adjust the width of the form */
            max-width: 1100px; /* Set a maximum width if needed */
        }

        .multi-button{
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .sticky-textbox-1{
            right: 0;
            width: calc(100% - 59px); /* Adjust as needed */
            margin-left: 59px; /* Adjust as needed */
        }
        
        .sticky-textbox-2{
            right: 0;
            width: calc(100% - 18px); /* Adjust as needed */
            margin-left: 18px; /* Adjust as needed */
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
            font-size: 13px;
        }

        .vidposter-form {
            width: 100%; /* Adjust the width of the form */
            max-width: 700px; /* Set a maximum width if needed */
            font-size: 13px;
        }

        .multi-button{
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .sticky-textbox-1{
            width: calc(100% - 5px - 0px);
            margin-left: 5px;
            margin-right: 0px;
            font-size: 12px;
        }
        
        .sticky-textbox-2{
            width: calc(100% - 5px - 0px);
            margin-left: 5px;
            margin-right: 0px;
            font-size: 12px;
        }

        .rounded-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 20px; /* Adjust the value to change the roundness */
            overflow: hidden; /* Ensures the border-radius is applied to the table */
            font-size: 12px;
        }

        /* Style the table headers */
        .rounded-table th {
            background-color: #f2f2f2;
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }

        /* Style the table cells */
        .rounded-table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }
        
        .btn-custom-action{
            width: 30px;
            color: white;
            border-radius: 10px;
            font-size: 12px;
        }

        .custom-btn-size{
            width:40%;
            font-size: 13px;
        }

        .custom-display{
            display: none;
        }

        .form-control{
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
            <form class="vidposter-form" action="upload.php" method="POST" enctype="multipart/form-data">
                <div class="title-container">
                <h1 class="text-center">Video & Poster</h1>
                </div>
                <!-- due date message --><p class="mt-2 text-center"><span class="due-msg">Due date: <?php echo isset($duetimeString)?htmlspecialchars($duetimeString):'';?></span></p>
                <div class="row g-3 align-items-center py-2 mt-4">
                    <div class="col-auto">
                        <label for="vidlink" class="col-form-label"><span class="custom-display">Please insert your </span>video link :</label>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control sticky-textbox-1" id="vidlink" name="vidlink" placeholder="Example : https://www.youtube.com/" value="<?php echo $flag_link_submitted? htmlspecialchars($infovidlink[0]['videolink']):'';?>">
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col-auto">
                        <label for="poster" class="col-form-label"><span class="custom-display">Please </span>upload <span class="custom-display">your </span>poster (PDF):</label>
                    </div>
                    <div class="col">
                        <input type="file" class="form-control sticky-textbox-2" id="file" name="file">
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    
                </div>
                <div class="py-2 justify-content-center multi-button">
                    <button type="submit" id="submit" name="submit" class="btn btn-block mt-2 btn-custom-shadow custom-content-btn custom-btn-size" <?php echo $flag_approved || $flag_nogroup ? "disabled" : ''; ?>>SUBMIT</button>
                </div>
                <div class="mb-3 py-2">
                    <label for="comment" class="form-label">Comment from Supervisor :</label>
                    <textarea readonly class="form-control" id="comment" name="comment" rows="5"><?php echo isset($comment)? htmlspecialchars($comment):'';?></textarea>
                </div>
            </form>
            </div>
            <form action="delete.php" method="POST">
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
                                $file_path = "../fileuploaded/" .$context."/".$grpid.$context.$row['filename'];
                                ?>
                                <tr>
                                    <td><?php echo $row['filename']; ?></td>
                                    <td><?php echo $row['submitTime']; ?></td>
                                    <td>
                                        <a href="<?php echo $file_path; ?>" class="btn btn-sm btn-custom-action" style="background-color: #2FAB74;" target="_blank"><i class="fa-solid fa-file-arrow-down pe-1"></i><span class="custom-display">View</span></a>
                                        <button type="submit" id="delete" name="delete" class="btn btn-sm btn-custom-action btn-danger" onclick="return confirmDelete()" <?php echo $flag_approved || $flag_nogroup ? 'disabled' : ''; ?>><i class="fas fa-trash-alt pe-1"></i><span class="custom-display">Delete</span></button>
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
            </form>

            <div class="container mt-5">
                <a id="download-pdf" class="btn btn-primary btn-custom-shadow" href="../fileuploaded/rubrics/rubric_poster.pdf" download="rubric_poster.pdf" style="background-color: #4CE833; border: none; color: black;"><i class="fa-solid fa-file-arrow-down pe-1"></i><span class="custom-display">Download</span> Rubrics</a>
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

        const url = '../fileuploaded/rubrics/rubric_poster.pdf';

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