<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../config/db_connect.php");

//disable the session to store the minute meeting id
unset($_SESSION['mmid']);

// disable the session to store the approved flag
unset($_SESSION['approved']);

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
    $flag_nogroup = 0; // change to FALSE to enable add meetings function
    $grpidTemp = $infoUser[0]['grpid'];
    $grpid = mysqli_real_escape_string($conn, $grpidTemp);

    $sqlminmeet = "SELECT * FROM minmeet WHERE grpid = '$grpid' ORDER BY weekNo";
    $resultminmeet = mysqli_query($conn, $sqlminmeet);
    $rows = mysqli_fetch_all($resultminmeet, MYSQLI_ASSOC);

    mysqli_free_result($resultminmeet);
}

// Due date time information
date_default_timezone_set('Asia/Singapore'); // ensure it is GMT +8
$sqlDuetime = "SELECT duedate.dueDateTime FROM duedate INNER JOIN context ON duedate.contid = context.contid WHERE context = 'minute meetings'";
$resultDuetime = mysqli_query($conn, $sqlDuetime);

if(mysqli_num_rows($resultDuetime) > 0){
    $infoDuetime = mysqli_fetch_all($resultDuetime, MYSQLI_ASSOC); // convert to associate array
    $duetimeString = $infoDuetime[0]['dueDateTime'];
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
            margin-top: 48px;
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
            width: 100px;
            color: white;

        }
    @media (max-width: 768px){
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
            width: 200px; 
            height: 30px; 
            align-content: center;
            font-size: 12px;
            margin-top: 10px;
        }

        .rounded-table {
            border-collapse: collapse;
            border-radius: 20px; /* Adjust the value to change the roundness */
            overflow: hidden; /* Ensures the border-radius is applied to the table */
            font-size: 12px;
            margin-left: 5px;
            margin-right: 5px;
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
            width: 32px;
            color: white;
            font-size: 12px;
            border-radius: 10px;
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
                            <h1 class="text-center">Minute Meeting</h1>
                        </div>
                        <!-- due date message --><p class="mt-2 text-center"><span class="due-msg">Due date: <?php echo isset($duetimeString)?htmlspecialchars($duetimeString):'';?></span></p>
                        <div class="col-auto">
                            <a href="./minMeet.php" 
                            type="button"
                            <?php if ($flag_nogroup): ?>
                                aria-disabled="true" 
                                tabindex="-1"
                            <?php endif; ?>
                            <?php if (!$flag_nogroup): ?>
                                class="btn btn-custom-shadow custom-content-btn btn-custom-add"
                            <?php else: ?>
                                class="btn btn-custom-shadow custom-content-btn btn-custom-add disabled"
                            <?php endif; ?>>
                                <i class="fas fa-plus"></i> Add Minute Meeting
                            </a>
                        </div>
                        <!-- table to show the minute meeting list -->
                        <div class="container mt-5">
                            <table class="table table-striped rounded-table">
                                <thead>
                                    <tr>
                                        <th>Week</th>
                                        <th>Meeting Date</th>
                                        <th>Time Submitted</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php if(!$flag_nogroup): ?>
                                        <?php foreach ($rows as $row): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['weekNo']); ?></td>
                                                <td><?php echo htmlspecialchars($row['meetdate']); ?></td>
                                                <td><?php echo htmlspecialchars($row['submitTime']); ?></td>
                                                <?php if($row['approved'] == 1): ?>
                                                    <td style="color: #1ADC06;">Approved</td>
                                                <?php elseif(!$row['approved'] && $row['submitted'] == 0): ?>
                                                    <td style="color: #EA0000;">Correction Needed</td>
                                                <?php elseif(!$row['approved'] && $row['submitted'] == 1): ?>
                                                    <td style="color: #0057E6;">Pending Review</td>
                                                <?php endif ?>
                                                <td>
                                                    <!-- Edit button with icon -->
                                                    <a href="minMeet.php?id=<?php echo $row['mmid']; ?>" class="btn btn-sm mr-2 btn-custom-action" style="background-color: #2FAB74;" >
                                                    <?php echo ($row['approved'] == 1) ? '<i class="fa-solid fa-eye"></i>' : '<i class="fas fa-edit"></i>'; ?>
                                                    <?php echo ($row['approved'] == 1) ? "<span class='custom-display'>View</span>" : "<span class='custom-display'>Edit</span>"; ?>
                                                        
                                                    </a>

                                                    <!-- Delete button with icon -->
                                                    <a href="deleteMinmeet.php?id=<?php echo $row['mmid']; ?>" class="btn btn-danger btn-sm btn-custom-action" onclick="return confirmDelete()"<?php echo $row['approved'] == 1?'style="pointer-events: none; background-color: #601A28;"':'';?>>
                                                        <i class="fas fa-trash-alt"></i><span class="custom-display">Delete</span>
                                                    </a>
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
                    <a id="download-pdf" class="btn btn-primary btn-custom-shadow" href="../fileuploaded/rubrics/rubric_minute.pdf" download="rubric_minute.pdf" style="background-color: #4CE833; border: none; color: black;"><i class="fa-solid fa-file-arrow-down pe-1"></i><span class="custom-display">Download</span> Rubrics</a>
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

        const url = '../fileuploaded/rubrics/rubric_minute.pdf';

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