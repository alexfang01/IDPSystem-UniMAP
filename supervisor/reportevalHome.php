<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../config/db_connect.php");

// credential check
include('./loginCheck.php');

// Getting user's information
$svid = $_SESSION['id'];
$sqlUser = "SELECT * FROM supervisor WHERE svid = '$svid'";
$resultUser = mysqli_query($conn, $sqlUser);
$infoUser = mysqli_fetch_all($resultUser, MYSQLI_ASSOC); // convert to associate array
mysqli_free_result($resultUser);

$flag_nogroup = 1;

// own group id for sv and eval id
if(isset($infoUser[0]['grpid'])){
    $flag_nogroup = 0;
    $grpid = mysqli_real_escape_string($conn, $infoUser[0]['grpid']);
    if(isset($infoUser[0]['evalid'])){
        // only if the supervisor had assigne with evalid
        $evalid = mysqli_real_escape_string($conn, $infoUser[0]['evalid']);
        // getting information for grpnum, theme and title
        $sql = "SELECT idpsynopsis.title AS idpsynopsis_title, idpgroup.grpnum, idpgroup.grpid, theme.title AS theme_title from idpgroup INNER JOIN idpsynopsis ON idpgroup.grpid=idpsynopsis.grpid INNER JOIN theme ON idpgroup.themeid=theme.themeid WHERE idpgroup.active='1' AND (idpgroup.grpid='$grpid' OR idpgroup.grpid='$evalid') ORDER BY grpnum ASC";
        $result = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }else{
        // no assign any group but own group only
        $sql = "SELECT idpsynopsis.title AS idpsynopsis_title, idpgroup.grpnum, idpgroup.grpid, theme.title AS theme_title from idpgroup INNER JOIN idpsynopsis ON idpgroup.grpid=idpsynopsis.grpid INNER JOIN theme ON idpgroup.themeid=theme.themeid WHERE idpgroup.active='1' AND idpgroup.grpid='$grpid' ORDER BY grpnum ASC";
        $result = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

if (isset($_POST['filter'])) {
    if(!$flag_nogroup){
        $grpnum = mysqli_real_escape_string($conn, $_POST['searchgrpnum']);

        $flag_grpnum = 0;
        // check the groupnumber is assign or not
        $sql = "SELECT grpnum FROM idpgroup WHERE idpgroup.active='1' AND (idpgroup.grpid='$grpid' OR idpgroup.grpid='$evalid')";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0){
            $infoGrpnum = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        foreach($infoGrpnum as $row){
            $grpnumArray[] = $row['grpnum'];
        }

        if(isset($grpnum)){
            if(isset($grpnumArray)){
                foreach($grpnumArray as $array){
                    if($array == $grpnum){
                        $flag_grpnum += 1;
                    }
                }
                if($flag_grpnum > 0){
                    $sql = "SELECT idpsynopsis.title AS idpsynopsis_title, idpgroup.grpnum, idpgroup.grpid, theme.title AS theme_title from idpgroup INNER JOIN idpsynopsis ON idpgroup.grpid=idpsynopsis.grpid INNER JOIN theme ON idpgroup.themeid=theme.themeid WHERE idpgroup.active='1' AND idpgroup.grpnum='$grpnum' ORDER BY grpnum ASC";
                    $result = mysqli_query($conn, $sql);
                    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
                }
            }
        }else{
            // load the default version
            $sql = "SELECT idpsynopsis.title AS idpsynopsis_title, idpgroup.grpnum, idpgroup.grpid, theme.title AS theme_title from idpgroup INNER JOIN idpsynopsis ON idpgroup.grpid=idpsynopsis.grpid INNER JOIN theme ON idpgroup.themeid=theme.themeid WHERE idpgroup.active='1' ORDER BY grpnum ASC";
            $result = mysqli_query($conn, $sql);
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    }
    
}



// time information
date_default_timezone_set('Asia/Singapore'); // ensure it is GMT +8

if(isset($result)){
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
            justify-content: center;
            align-items: center;
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

        .custom-textbox{
            width: 21%;
            font-size: 15px;
            margin-left: 13px;
        }

        .custom-search-btn{
            margin-top: 25px;
            margin-left: 40px;
        }

        .custom-drop{
            width: 80%; 
            padding: 16px; 
            font-size: 15px; 
            border-radius: 15px; 
            margin-top: 25px;
        }

        .custom-btn-style{
            width:130px;
            border-radius: 15px;
            height: 55px;
        }
    
    @media (max-width: 768px) {
        .custom-form {
            width: 100%; /* Adjust the width of the form */
            max-width: 700px; /* Set a maximum width if needed */
            justify-content: center;
            align-items: center;
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
            font-size: 12px;
            width: 100%; /* Make table take full width */
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
            width: 150px;
            color: white;

        }

        .custom-textbox{
            width: 100%;
            margin-left: 0px;
            margin-right: 0px;
            font-size: 12px;
        }

        .custom-search-btn{
            display: flex;
            margin-left: 37%;
        }

        .custom-drop{
            width: 180px; 
            padding: 16px; 
            font-size: 12px; 
            border-radius: 15px;
            margin-left: 0px !important;
            margin-right: 0px !important;
        }

        .custom-btn-style{
            width:130px;
            border-radius: 15px;
            height: 50px;
        }

        .btn-custom-size {
            width: 27px;
            font-size: 10px;
            margin-top: 5px;
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
                    <div class="custom-form">
                        <form class="" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                            <div class="title-container">
                                <h1 class="text-center">Final Report Evaluation</h1>
                            </div>
                            <div class="row">
                                <div class="col-3 form-floating mt-4 custom-textbox">
                                    <input type="text" class="form-control " id="searchgrpnum" name="searchgrpnum" placeholder="Enter username or email" style="border-radius: 15px;" value="<?php if(isset($_POST['searchgrpnum'])){ echo htmlspecialchars($_POST['searchgrpnum']); } ?>">
                                    <label for="searchgrpnum" style="margin-left: 10px;">Search by Group Number</label>
                                </div>
                                <div class="justify-content-center custom-search-btn col-3">
                                    <button type="submit" id="filter" name="filter" class="btn btn-block btn-custom-shadow custom-content-btn custom-btn-style" <?php echo $flag_nogroup?'disabled':''; ?>>SEARCH</button>
                                </div>
                                <div class="col-3"></div>
                            </div>
                            
                            
                            <span class="prompt-msg"><p>ROTATE your screen horizontally if using smaller device to have better table view</p></span>

                            <!-- table to show the minute meeting list -->
                            <div class="container mt-5">
                                <table class="table table-striped rounded-table">
                                    <thead>
                                        <tr>
                                            <th>Group Number</th>
                                            <th>Theme</th>
                                            <th>Title</th>
                                            <th class="custom-display">Remark</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                            <?php  if(isset($rows)):?>
                                                <?php foreach ($rows as $row): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['grpnum']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['theme_title']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['idpsynopsis_title']); ?></td>
                                                        <td class="custom-display"><?php if(isset($grpid)){echo ($row['grpid'] == $grpid)? "Own Group": 'Assigned Group';} ?></td>
                                                        <td>
                                                            <?php 
                                                                // getting status of the group evaluation
                                                                // whether it evaluate by supervisor or examiner
                                                                                                                        
                                                                $flag_examiner_eval = $flag_sv_eval = 0;
                                                                $grpidStatus = mysqli_real_escape_string($conn, $row['grpid']);

                                                                // extract grpid's supervisor id, to check whether the supervisor had evaluate the report or not
                                                                $sql = "SELECT svid FROM supervisor WHERE grpid='$grpidStatus'";
                                                                $resultCheck = mysqli_query($conn, $sql);
                                                                if(mysqli_num_rows($resultCheck) > 0){
                                                                    $infoSVID = mysqli_fetch_all($resultCheck, MYSQLI_ASSOC);
                                                                    $svidStatus = mysqli_real_escape_string($conn, $infoSVID[0]['svid']);
                                                                }

                                                                // checking own supervisor evaluate already??
                                                                $sql = "SELECT * FROM reporteval WHERE grpid='$grpidStatus' AND svid='$svidStatus'";
                                                                $resultCheck = mysqli_query($conn, $sql);
                                                                if(mysqli_num_rows($resultCheck) > 0){
                                                                    $flag_sv_eval = 1;
                                                                }
                                                                // checking got examiner evaluate?
                                                                $sql = "SELECT * FROM reporteval WHERE grpid='$grpidStatus' AND svid!='$svidStatus'";
                                                                $resultCheck = mysqli_query($conn, $sql);
                                                                if(mysqli_num_rows($resultCheck) > 0){
                                                                    $flag_examiner_eval = 1;
                                                                }
                                                                
                                                                if($flag_sv_eval && $flag_examiner_eval){
                                                                    echo "<span style='color: #1ADC06;'>Evaluation Complete</span>";
                                                                }else if($flag_sv_eval && !$flag_examiner_eval){
                                                                    echo "<span style='color: #EA0000;'>Require Examiner Evaluation</span>";
                                                                }else if(!$flag_sv_eval && $flag_examiner_eval){
                                                                    echo "<span style='color: #EA0000;'>Require Supervisor Evaluation</span>";
                                                                }else{
                                                                    echo "<span style='color: #EA0000;'>No Evaluation Record</span>";
                                                                }
                                                                mysqli_free_result($resultCheck);
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <!-- Evaluate button with icon -->
                                                            <a href="reporteval.php?id=<?php echo $row['grpid']; ?>" class="btn btn-sm mr-2 btn-custom-action btn-custom-size" style="background-color: #2FAB74;">
                                                                <i class="fas fa-edit pe-2"></i><span class="custom-display">Evaluate</span>
                                                            </a>
                                                            <!-- Document button with icon -->
                                                            <a href="document.php?id=<?php echo $row['grpid']; ?>" class="btn btn-sm mr-2 btn-custom-action btn-custom-size" style="background-color: #0049FF;">
                                                                <i class="fa-solid fa-note-sticky pe-2"></i><span class="custom-display">Documents</span>
                                                            </a>
                                                        </td>

                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
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
</body>
<?php mysqli_close($conn); ?>
</html>