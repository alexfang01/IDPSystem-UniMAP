<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    include("../config/db_connect.php");

    // credential check
    include('./loginCheck.php');


    // Getting user's information
    $panelid = $_SESSION['id'];
    $sqlUser = "SELECT * FROM panel WHERE panelid = '$panelid'";
    $resultUser = mysqli_query($conn, $sqlUser);
    $infoUser = mysqli_fetch_all($resultUser, MYSQLI_ASSOC); // convert to associate array
    mysqli_free_result($resultUser);
    
    if(isset($_GET['id'])){
        $grpid = mysqli_real_escape_string($conn, $_GET['id']);
        
        //checking whether group is active or not
        $sql = "SELECT * FROM idpgroup WHERE grpid='$grpid' AND active='1'";
        $result = mysqli_query($conn, $sql);
        $infoGrp = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if (!(mysqli_num_rows($result) > 0)){
            echo "<script>alert('Illegal action detected');</script>";
            header("Location: idpexevalHome.php");
        }

        //information on the file submitted
        $sqlFile = "SELECT * FROM idpfile WHERE grpid = '$grpid'";
        $resultFile = mysqli_query($conn, $sqlFile);
        if(mysqli_num_rows($resultFile) > 0){
            $infoFile = mysqli_fetch_all($resultFile, MYSQLI_ASSOC);
            foreach($infoFile as $row){
                $filename[] = $row['filename'];
                $filetype[] = $row['type'];
                $filesubmitTime[] = $row['submitTime'];
            }
        }

        // getting information on synopsis
        $sql = "SELECT * FROM idpsynopsis WHERE grpid='$grpid'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0){
            $infoSynopsis = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        // information on project theme
        $sql = "SELECT * FROM idpgroup INNER JOIN theme ON idpgroup.themeid  = theme.themeid WHERE grpid='$grpid'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0){
            $infoTheme = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    }
    mysqli_free_result($resultFile);
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

        .container-synopsis {
            display: block;
        }

        .proposal-form {
            width: 90%; /* Adjust the width of the form */
            max-width: 1100px; /* Set a maximum width if needed */
        }

        .multi-button{
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .rounded-table {
            width: 100%;
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

        .container-synopsis {
            display: block;
        }

        h5 {
            font-size: 15px;
        }

        .proposal-form {
            width: 100%; /* Adjust the width of the form */
            max-width: 700px; /* Set a maximum width if needed */
        }

        .multi-button{
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .rounded-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 20px; /* Adjust the value to change the roundness */
            overflow: hidden; /* Ensures the border-radius is applied to the table */
            font-size: 11px;
            margin-left: 0px;
            margin-right: 0px;
        }

        /* Style the table headers */
        .rounded-table th {
            background-color: #f2f2f2;
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: center;
            font-size: 11px;
        }

        /* Style the table cells */
        .rounded-table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: center;
            font-size: 11px;
        }
        
        .btn-custom-action{
            width: 40px;
            color: white;
            font-size: 10px;

        }
        
        .sticky-textbox{
            right: 0;
            width: calc(100% - 52px - 65px); /* Adjust as needed */
            margin-left: 52px; /* Adjust as needed */
            margin-right: 65px; /* Adjust as needed */
        }

    }

    </style>
        <!-- Main content in here -->
        <div class="main">
            <!-- Navigation bar part -->
            <?php include('navDisplay.php');?>
            <main class="content px-3 py-2">
            
                <div class="container">
                    <div class="proposal-form">
                            <div class="title-container">
                                <h1 class="text-center">Group <?php echo isset($infoGrp[0]['grpnum'])?htmlspecialchars($infoGrp[0]['grpnum']): ''; ?> Documents</h1>
                            </div>
                        <div class="container-synopsis mt-4">
                            <h5><strong>Project Title:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong><?php echo $infoSynopsis[0]['title']; ?></h5>
                            <h5><strong>Project Theme:&nbsp;</strong><?php echo $infoTheme[0]['title']; ?></h5>
                            <h5><strong>Project Synopsis:</strong></h5>
                            <h5><?php echo $infoSynopsis[0]['synopsis']; ?></h5>
                            <h5><strong>Project Objectives:</strong></h5>
                            <h5><?php echo $infoSynopsis[0]['objectives']; ?></h5>
                            
                        </div>
                        <table class="table rounded-table mt-4">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Document Type</th>
                                    <th>Time Submitted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php
                                // Display the uploaded files and download links
                                if (isset($infoFile)) {
                                    for ($i = 0; $i < count($filename); $i++) {
                                        $file_path = "../fileuploaded/" . $filetype[$i] . "/" . $grpid . $filetype[$i] . $filename[$i];
                                        ?>
                                        <tr>
                                            <td><?php echo $filename[$i]; ?></td>
                                            <td>
                                                <?php
                                                if ($filetype[$i] == 'vidposter') {
                                                    echo "<i class='fa-solid fa-photo-film pe-2'></i>Poster";
                                                } else if ($filetype[$i] == 'proposal') {
                                                    echo "<i class='fa-solid fa-note-sticky pe-2'></i>Proposal";
                                                } else if ($filetype[$i] == 'finalreport') {
                                                    echo "<i class='fa-solid fa-book pe-2'></i>Final Report";
                                                } else {
                                                    echo "Unidentified";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $filesubmitTime[$i]; ?></td>
                                            <td>
                                                <a href="<?php echo $file_path; ?>" class="btn btn-sm btn-custom-action" style="background-color: #2FAB74;" target="_blank"><i class="fa-solid fa-file-arrow-down pe-1"></i>View</a>
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
                
                </div>
                
            </main>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebarToggle = document.querySelector("#sidebar-toggle");
        sidebarToggle.addEventListener("click",function(){
            document.querySelector("#sidebar").classList.toggle("collapsed")
        });
    </script>
</body>
<?php mysqli_close($conn); ?>
</html>