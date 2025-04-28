<?php 
    // connect to database
    include('../config/db_connect.php');
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // credential check
    include('./loginCheck.php');

    // Getting user's information
    $panelid = $_SESSION['id'];
    $sqlUser = "SELECT * FROM panel WHERE panelid = '$panelid'";
    $resultUser = mysqli_query($conn, $sqlUser);
    $infoUser = mysqli_fetch_all($resultUser, MYSQLI_ASSOC); // convert to associate array
    mysqli_free_result($resultUser);

    //Fetching event data from db
    $sql = "SELECT * FROM event ORDER BY weekNo ASC, date ASC";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $info = mysqli_fetch_all($result, MYSQLI_ASSOC); // convert to associate array
        foreach($info as $row){
            $weekNo[] = $row['weekNo'];
            $event[] = $row['event'];
            $date[] = $row['date'];
            $startTime[] = $row['startTime'];
            $endTime[] = $row['endTime'];
            $venue[] = $row['venue'];   
        }
    }

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

        .profile-form {
            width: 100%; /* Adjust the width of the form */
            max-width: 1100px; /* Set a maximum width if needed */
        }

        .member-textbox{
            /* width: 70%;
            margin-left: 195px;
            right: 0; */
        
        }

        .event-box {
            display: block;
            background-color: #F3F6F4;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 0px 20px 20px 0px;
            align-items: left;
        }

        .week-box {
            background-color: #2B2F33;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            border-radius: 20px 0px 0px 20px;
        }

        .details h5{
            text-align: left;
            margin-top: 0;
            margin-left: 30px;

        }

        .details h2 {
            margin-top: 0;
        }

        p {
            font-family: 'Poppins',sans-serif;
            font-size: 1.125rem;
        }

        .details p {
            color: #555555;
        }
        
        h5{
            font-size: 18px;
        }

        .event-container{
            width: 70%;
            margin-left: 160px;
        }
        
    @media (max-width: 768px) {
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
            width: 100%; /* Adjust the width of the form */
            max-width: 550px; /* Set a maximum width if needed */
        }

        .member-textbox{
            /* width: 70%;
            margin-left: 195px;
            right: 0; */
        
        }

        .event-box {
            display: block;
            background-color: #F3F6F4;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 0px 20px 20px 0px;
            align-items: left;
        }

        .week-box {
            background-color: #2B2F33;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            border-radius: 20px 0px 0px 20px;
        }

        .details h5{
            text-align: left;
            margin-top: 0;
            margin-left: 30px;

        }

        .details h2 {
            margin-top: 0;
        }

        p {
            font-family: 'Poppins',sans-serif;
            font-size: 1.125rem;
        }

        .details p {
            color: #555555;
        }
        
        h5{
            font-size: 12px;
        }

        .event-container{
            width: 100%;
            margin-left:0;
        }
    }
    </style>
        <!-- Main content in here -->
        <div class="main">
            <!-- Navigation bar part -->
            <?php include('navDisplay.php');?>
            <!-- nav bar end -->
            <main class="content px-3 py-2">
            
                <div class="container">
                <form class="profile-form" method="post" action=".php">
                    <div class="title-container">
                        <h1 class="text-center">Home Page</h1>
                    </div>

                    <h5 class="text-justify text-center mt-3">Take note on <strong>Important Events</strong></h5>
                    <?php $weekTemp = -1; ?>
                    <?php for($i = 0; $i < count($weekNo); $i++): ?>
                        <?php if($weekNo[$i] != $weekTemp): ?>
                            <?php if($weekTemp != -1): ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="event-container row mt-4">
                                <div class="week-box col-3">Week <?php echo htmlspecialchars($weekNo[$i]); ?></div>
                                <div class="event-box col-9">
                        <?php endif; ?>
                                    <div class="details mt-3"><h5><strong><?php echo htmlspecialchars($event[$i]); ?></strong></h5></div>
                                    <div class="details"><h5>Date: <?php echo htmlspecialchars($date[$i]); ?></h5></div>
                                    <div class="details"><h5>Time: <?php echo htmlspecialchars($startTime[$i]); ?> - <?php echo htmlspecialchars($endTime[$i]); ?></h5></div>
                                    <div class="details"><h5>Venue: <?php echo htmlspecialchars($venue[$i]); ?></h5></div>
                        <?php $weekTemp = $weekNo[$i]; ?>
                    <?php endfor; ?>
                    <?php if(count($weekNo) > 0): ?>
                                </div>
                            </div>
                    <?php endif; ?>
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
    </script>
</body>
<?php mysqli_close($conn); ?>
</html>