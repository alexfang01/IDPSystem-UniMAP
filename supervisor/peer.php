<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['memberid'])){
    unset($_SESSION['memberid']);
}
unset($_SESSION['']);
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

if($infoUser[0]['grpid'] != ""){
    $flag_nogroup = 0; // change to FALSE to enable add meetings function
    $grpidTemp = $infoUser[0]['grpid'];
    $grpid = mysqli_real_escape_string($conn, $grpidTemp);

    // extract members name, leader status and id
    $sql = "SELECT studentid, name, leader FROM student WHERE grpid = '$grpid'";
    $result = mysqli_query($conn, $sql);
    $members = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_free_result($result);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('dashTemp.php'); ?>

    <style>
        
        .custom-form {
            width: 90%; /* Adjust the width of the form */
            max-width: 1100px; /* Set a maximum width if needed */
        }

        .title-container{
            border-bottom : 3px solid black; 
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn-custom-add{
            width: 300px; 
            height: 50px; 
            align-content: center;
            margin-left: 12px;
        }

        .rounded-table {
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
            width: 150px;
            color: white;

        }

        h5{
            font-size: 18px;
        }
    
    @media(max-width: 768px){
        .custom-form {
            width: 100%; /* Adjust the width of the form */
            max-width: 700px; /* Set a maximum width if needed */
            font-size: 12px;
        }

        .title-container{
            border-bottom : 3px solid black; 
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
        }

        .btn-custom-add{
            width: 300px; 
            height: 50px; 
            align-content: center;
            margin-left: 12px;
        }

        .rounded-table {
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

        h5{
            font-size: 12px;
        }

        .custom-display{
            display: none;
        }
    }

    </style>
        <!-- Main content in here -->
        <div class="main">
            <!-- Navigation bar part -->
            <?php include('navDisplay.php');?>
            <main class="content px-3 py-2">
            
                <div class="container">
                    <form class="custom-form" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                        <div class="title-container">
                            <h1 class="text-center">Peer Review</h1>
                        </div>
                        <div class="row g-3 align-items-center py-2 mt-2">
                            <h5 class="text-justify">
                                Use 1 to 5 point <strong>SCORE: 0-NON-CONTRIBUTION, 1-POOR, 2-NEED IMPROVEMENT, 3-MODERATE, 4-GOOD, 5-EXCELLENT</strong>
                            </h5>
                        </div>
                        <!-- table to show the minute meeting list -->
                        <div class="container mt-5">
                            <table class="table table-striped rounded-table">
                                <thead>
                                    <tr>
                                        <th>Member</th>
                                        <th>Time Submitted</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php if(!$flag_nogroup): ?>
                                        <?php foreach ($members as $member): ?>
                                            <tr>
                                                <td><?php echo strtoupper(htmlspecialchars($member['name'])); ?></td>
                                                <?php
                                                    //extracting peer review time submitted info
                                                    $memberid = $member['studentid'];
                                                    $sql = "SELECT timeSubmit FROM peersv WHERE svid = '$svid' AND studentid = '$memberid'";
                                                    $resultTimeSubmit = mysqli_query($conn, $sql);
                                                    if(mysqli_num_rows($resultTimeSubmit) > 0){
                                                        $rows = mysqli_fetch_all($resultTimeSubmit, MYSQLI_ASSOC);
                                                    }
                                                
                                                
                                                ?>
                                                <td><?php echo isset($rows[0]['timeSubmit'])?htmlspecialchars($rows[0]['timeSubmit']):''; ?></td>
                                                <?php if(isset($rows[0]['timeSubmit'])):unset($rows[0]['timeSubmit']); ?>
                                                <?php endif ?>
                                                <td>
                                                    <!-- Change to leaderview evaluation if the members is leader -->
                                                    <?php if($member['leader'] == 1):?>
                                                        <!-- Evaluate leader-->
                                                        <a href="leaderview.php?id=<?php echo $member['studentid']; ?>" class="btn btn-sm mr-2 btn-custom-action" style="background-color: #2FAB74;" >
                                                        <i class="fas fa-edit pe-1"></i><span class="custom-display">Evaluate</span>
                                                        </a>
                                                    <?php else: ?>
                                                        <!-- Evaluate leader-->
                                                        <a href="memberview.php?id=<?php echo $member['studentid']; ?>" class="btn btn-sm mr-2 btn-custom-action" style="background-color: #2FAB74;" >
                                                        <i class="fas fa-edit pe-1"></i><span class="custom-display">Evaluate</span>
                                                        </a>
                                                    <?php endif ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif?>
                                </tbody>
                            </table>
                        </div>

                    </form>

                </div>

                <div class="container mt-5">
                    <a id="download-pdf" class="btn btn-primary btn-custom-shadow" href="../fileuploaded/rubrics/rubric_peersv.pdf" download="rubric_peersv.pdf" style="background-color: #4CE833; border: none; color: black;"><i class="fa-solid fa-file-arrow-down pe-1"></i><span class="custom-display">Download</span> Rubrics</a>
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
        
        // to confirm deletion
        function confirmDelete() {
            return confirm("Are you sure you want to delete this row?");
        }

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
</body>
<?php 
if(isset($resultTimeSubmit)){
    mysqli_free_result($resultTimeSubmit);
}
mysqli_close($conn); 
?>
</html>