<?php
// connect to database
include('../config/db_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// credential check
include('./loginCheck.php');

$flag_nogroup = 1; // if no group, display nothing, got group then display grouping info
$flag_youareleader = 0; // if you are leader, then display You is a leader

// Getting user's information
$studentid = $_SESSION['id'];
$sqlUser = "SELECT * FROM student WHERE studentid = '$studentid'";
$resultUser = mysqli_query($conn, $sqlUser);
$infoUser = mysqli_fetch_all($resultUser, MYSQLI_ASSOC); // convert to associate array

// assign grp id if exist
if($infoUser[0]['grpid'] != ""){
    $flag_nogroup = 0;
    $grpidTemp = $infoUser[0]['grpid'];
    $grpid = mysqli_real_escape_string($conn, $grpidTemp);

    // extracting supervisor data
    $sql = "SELECT * FROM supervisor WHERE grpid = '$grpid'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0){
        $info = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $svname = $info[0]['name'];
        $svemail = $info[0]['email'];
    }

    // extracting member data
    $sql = "SELECT * FROM student WHERE grpid = '$grpid' ORDER BY leader DESC";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0){
        $info = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // avoid extracting own data
        foreach($info as $row){
            if($row['studentid'] != $studentid){
                $members[] = $row['name'];
                $membersEmail[] = $row['email'];
                $membersPhone[] = $row['phnum'];
                $membersLead[] = $row['leader'];
            }else{
                if($row['leader'] == 1){
                    $flag_youareleader = 1;
                }
            }
        }
    }

    mysqli_free_result($result);

}

mysqli_free_result($resultUser);

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

        .container-custom{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50%;
        }

        .group-custom-container {
            width: 90%; /* Adjust the width of the form */
            max-width: 1100px; /* Set a maximum width if needed */
        }

        .member-textbox{
            /* width: 70%;
            margin-left: 195px;
            right: 0; */
        
        }

        .custom-textbox{
            font-size: 15px; /*for the floating label */
        }

        .sticky-textbox-1{
            right: 0;
            width: calc(100% - 80px - 208px); /* Adjust as needed */
            margin-left: 80px; /* Adjust as needed */
            margin-right: 208px; /* Adjust as needed */
        }

        .sticky-textbox-2{
            right: 0;
            width: calc(100% - 87px - 208px); /* Adjust as needed */
            margin-left: 87px; /* Adjust as needed */
            margin-right: 208px; /* Adjust as needed */
        }

        .sticky-textbox-3{
            right: 0;
            width: calc(100% - 42px - 208px); /* Adjust as needed */
            margin-left: 42px; /* Adjust as needed */
            margin-right: 208px; /* Adjust as needed */
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

        .container-custom{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50%;
        }

        .group-custom-container {
            width: 90%; /* Adjust the width of the form */
            max-width: 1100px; /* Set a maximum width if needed */
        }

        .member-textbox{
            /* width: 70%;
            margin-left: 195px;
            right: 0; */
        
        }

        .custom-textbox{
            font-size: 15px; /*for the floating label */
        }

        .sticky-textbox-1{
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
    }
    </style>
        <!-- Main content in here -->
        <div class="main">
            <!-- Navigation bar part -->
            <?php include('navDisplay.php');?>
            <main class="content px-3 py-2">
            
            <div class="container">
            <div class="group-custom-container">
            <div class="title-container">
                    <h1 class="text-center">Group Information</h1>
                </div>
                <!-- due date message --><p class="mt-2 text-center"><span class="due-msg">Please inform your Supervisor if the information is INCORRECT</span></p>
                <?php if(!$flag_nogroup):?>

                    <?php if($flag_youareleader): ?>
                        <div class="col-auto">
                            <label class="col-form-label" style="font-weight: bold;">You are Leader (assigned by Supervisor)</label>
                        </div>
                    <?php endif ?>

                    <div class="row g-3 align-items-center py-1">
                        <div class="col-auto">
                            <label for="username" class="col-form-label" style="font-weight: bold;">Supervisor</label>
                        </div>
                    </div>
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="username" class="col-form-label">Name :</label>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control sticky-textbox-1" disabled readonly id="svname" placeholder="Info not completed, please inform your supervisor" value="<?php echo isset($svname)?htmlspecialchars($svname):'';?>">
                        </div>
                    </div>
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="email" class="col-form-label">Email :</label>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control sticky-textbox-2" disabled readonly id="email" placeholder="Info not completed, please inform your supervisor" value="<?php echo isset($svemail)?htmlspecialchars($svemail):'';?>">
                        </div>
                    </div>
                    
                    <?php if(isset($members)): ?> 
                        <?php for($i = 0; $i < count($members); $i++): ?>
                        <div class="row g-3 align-items-center py-1 mt-3">
                            <div class="col-auto">
                                <label for="username" class="col-form-label" style="font-weight: bold;">Member <?php echo $i+1;?></label>
                                <?php if($membersLead[$i]): echo "(Leader)";?>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="membername" class="col-form-label">Name :</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control sticky-textbox-1" disabled readonly id="membername" placeholder="Info not completed, please inform your supervisor" value="<?php echo isset($members[$i])?htmlspecialchars($members[$i]):'';?>">
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="memberemail" class="col-form-label">Email :</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control sticky-textbox-2" disabled readonly id="memberemail" placeholder="Info not completed, please inform your supervisor" value="<?php echo isset($membersEmail[$i])?htmlspecialchars($membersEmail[$i]):'';?>">
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="memberphone" class="col-form-label">Phone No. :</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control sticky-textbox-3" disabled readonly id="memberphone" placeholder="Info not completed, please inform your supervisor" value="<?php echo isset($membersPhone[$i])?htmlspecialchars($membersPhone[$i]):'';?>">
                            </div>
                        </div>
                        <?php endfor ?>
                    <?php else: ?>
                        <div class="row g-3 align-items-center py-1">
                            <div class="col-auto">
                                <label class="col-form-label">No member yet, please inform IDP coordinator</label>
                            </div>
                        </div>
                    <?php endif ?>
                <?php else:?>
                    <div class="row g-3 align-items-center py-1">
                        <div class="col-auto">
                            <label class="col-form-label">No Group Yet, please inform IDP coordinator</label>
                        </div>
                    </div>
                <?php endif?>
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