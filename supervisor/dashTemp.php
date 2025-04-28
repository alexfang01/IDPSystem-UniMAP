    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FTKEN IDP SYSTEM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/4c7903acb1.js" crossorigin="anonymous"></script>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
    *,::after,
    ::before{
        box-sizing: border-box;
    }

    body{
        font-family: 'Poppins',sans-serif;
        font-size: 20px;
        opacity: 1;
        overflow-y: scroll;
        margin: 0;
    }

    a{
        cursor: pointer;
        text-decoration: none;
        font-family: 'Poppins',sans-serif;
    }

    li{
        list-style: none;
    }

    h4{
        font-family: 'Poppins',sans-serif;
        font-size: 1.275rem;
        color: var(--bs-emphasis-color);
    }

    /* Layout for dashboard skeleton*/
    .wrapper{
        align-items: stretch;
        display: flex;
        width: 100%;
    }

    .custom-content-btn{
        background-color: #67F5E4;
        color: black;
    }

    .btn-custom-shadow {
        width: 150px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .custom-content-btn:hover {
        background-color: #7ED6CC; /* Set the darker hover color */
    }

    #sidebar{
        max-width: 320px;
        min-width: 320px;
        background: var(--bs-dark);
        transition: all 0.35s ease-in-out;

    }

    .main{
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        min-width: 0;
        overflow: hidden;
        transition: all 0.35s ease-in-out;
        width: 100%;
        background: var(--bs-dark-bg-subtle);
    }
    /* basic skeleton done */
    
    /* Sidebar Element Style */

    .sidebar-logo{
        padding: 1.15rem;
    }

    .sidebar-logo a{
        color: #e9ecef;
        font-size: 35px;
        font-weight: 600;
    }

    .sidebar-nav{
        flex-grow: 1;
        list-style: none;
        margin-bottom: 0;
        padding-left: 0;
        margin-left: 0;
    }

    .sidebar-header{
        color: #e9ecef;
        font-size: 20px;
        padding: 1.5rem 1.5rem 0.375rem;
    }

    a.sidebar-link{
        padding: .625rem 1.625rem;
        color: #e9ecef;
        position: relative;
        display: block;
        font-size: 17px;
    }

    .sidebar-link[data-bs-toggle="collapse"]::after{
        border: solid;
        border-width: 0 .075rem .075rem 0;
        content: "";
        display: inline-block;
        padding: 2px;
        position: absolute;
        right: 1.5rem;
        top: 1.4rem;
        transform: rotate(-135deg);
        transition: all .2s ease-out;
    }

    .sidebar-link[data-bs-toggle="collapse"].collapsed::after{
        transform: rotate(45deg);
        transition: all .2s ease-out;
    }
        /* the arrow beside page will rotate */
    
    .avatar{
        height: 40px;
        width: 40px;
    }

    .navbar-expand .navbar-nav{
        margin-left: auto;
    }

    .content{
        flex: 1;
        max-width: 100vw;
        width: 100vw;
    }

    @media (min-width:768px){
        .content{
            max-width: auto;
            width: auto;
        }
    }

    .card{
        box-shadow: 0 0 .875rem 0 rgba(34, 46, 60, .05);
        margin-bottom: 24px;
    }

    .illustration{
        background-color: var(--bs-primary-bg-subtle);
        color: var(--bs-emphasis-color);
    }

    .illustration-img{
        max-width: 150px;
        width: 100%;
    }

    /* Sidebar Toggle */
    #sidebar.collapsed{
        margin-left: -320px;
    }

    .error-msg{
        font-size : 12px;
        color: red;
    }

    .due-msg{
        font-size : 17px;
        color: #E509C3;
    }
    
    .submit-msg{
        font-size : 15px;
        color: #E509C3;
    }

    .prompt-msg {
            display: none;
    }

    #pdf-container {
        width: 90%;
        height: 600px; /* Adjust height as needed */
        border: 1px solid #000;
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
        overflow: auto;
        border-radius: 20px;
    }

    #pdf-render {
        width: 100%; /* Ensure the canvas doesn't overflow */
        height: 0px; /* Ensure the canvas doesn't overflow */
        margin: auto; /* Center the canvas within its parent */
        border-radius: 20px;
    }


    @media (max-width: 768px) {
        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
        *,::after,
        ::before{
            box-sizing: border-box;
        }

        body{
            font-family: 'Poppins',sans-serif;
            font-size: 15px;
            opacity: 1;
            overflow-y: scroll;
            margin: 0;
        }

        a{
            cursor: pointer;
            text-decoration: none;
            font-family: 'Poppins',sans-serif;
        }

        li{
            list-style: none;
        }

        h4{
            font-family: 'Poppins',sans-serif;
            font-size: 1.275rem;
            color: var(--bs-emphasis-color);
        }

        /* Layout for dashboard skeleton*/
        .wrapper{
            align-items: stretch;
            display: flex;
            width: 100%;
        }

        .custom-content-btn{
            background-color: #67F5E4;
            color: black;
        }

        .btn-custom-shadow {
            width: 150px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .custom-content-btn:hover {
            background-color: #7ED6CC; /* Set the darker hover color */
        }

        #sidebar{
            max-width: 200px;
            min-width: 200px;
            background: var(--bs-dark);
            transition: all 0.35s ease-in-out;

        }

        .main{
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
            overflow: hidden;
            transition: all 0.35s ease-in-out;
            width: 100%;
            background: var(--bs-dark-bg-subtle);
        }
        /* basic skeleton done */
        
        /* Sidebar Element Style */

        .sidebar-logo{
            padding: 1.15rem;
        }

        .sidebar-logo a{
            color: #e9ecef;
            font-size: 20px;
            font-weight: 600;
        }

        .sidebar-nav{
            flex-grow: 1;
            list-style: none;
            margin-bottom: 0;
            padding-left: 0;
            margin-left: 0;
        }

        .sidebar-header{
            color: #e9ecef;
            font-size: 16px;
            padding: 1.5rem 1.5rem 0.375rem;
        }

        a.sidebar-link{
            padding: .625rem 1.625rem;
            color: #e9ecef;
            position: relative;
            display: block;
            font-size: 13px;
        }

        .sidebar-link[data-bs-toggle="collapse"]::after{
            border: solid;
            border-width: 0 .075rem .075rem 0;
            content: "";
            display: inline-block;
            padding: 2px;
            position: absolute;
            right: 1.5rem;
            top: 1.4rem;
            transform: rotate(-135deg);
            transition: all .2s ease-out;
        }

        .sidebar-link[data-bs-toggle="collapse"].collapsed::after{
            transform: rotate(45deg);
            transition: all .2s ease-out;
        }
            /* the arrow beside page will rotate */
        
        .avatar{
            height: 40px;
            width: 40px;
        }

        .navbar-expand .navbar-nav{
            margin-left: auto;
        }

        .content{
            flex: 1;
            max-width: 100vw;
            width: 100vw;
        }

        @media (min-width:768px){
            .content{
                max-width: auto;
                width: auto;
            }
        }

        .card{
            box-shadow: 0 0 .875rem 0 rgba(34, 46, 60, .05);
            margin-bottom: 24px;
        }

        .illustration{
            background-color: var(--bs-primary-bg-subtle);
            color: var(--bs-emphasis-color);
        }

        .illustration-img{
            max-width: 200px;
            width: 100%;
        }

        /* Sidebar Toggle */
        #sidebar.collapsed{
            margin-left: -200px;
        }

        .error-msg{
            font-size : 12px;
            color: red;
        }

        .due-msg{
            font-size : 13px;
            color: #E509C3;
        }
        
        .submit-msg{
            font-size : 15px;
            color: #E509C3;
        }

        .prompt-msg {
            font-size: 12px;
            margin-top: 20px;
            display: block;
            font-weight: bold;
            margin-left: 20px;
        }

        #pdf-container {
            width: 100%;
            height: 600px;
            overflow: auto;
            border: 1px solid #000;
            border-radius: 20px;
        }
        #pdf-render {
            width: 100%;
            height: 0px;
            border-radius: 20px;
        }
    }
</style>
<!-- php on getting info status to show the notification indicator -->
<?php
    $alert_synopsis = 0; // flag for notification for each context
    $alert_minmeet = 0;
    $alert_proposal = 0;
    $alert_report = 0;
    $alert_vidposter = 0;

    if($infoUser[0]['grpid'] != ""){
        $flag_nogroup = 0;
        $grpidTemp = $infoUser[0]['grpid'];
        $grpid = mysqli_real_escape_string($conn, $grpidTemp);

        // check whether the synopsis's submitted = 0 but the content is there
        $sql = "SELECT * FROM idpsynopsis WHERE grpid='$grpid'";
        $resultAlert = mysqli_query($conn, $sql);
        if (mysqli_num_rows($resultAlert) > 0){
            $info = mysqli_fetch_all($resultAlert, MYSQLI_ASSOC);
            if($info[0]['approved'] == 0 && $info[0]['submitted'] == 1){
                $alert_synopsis = 1;
            }
        }

        // check whether the min's submitted = 0 but the content is there
        $sql = "SELECT * FROM minmeet WHERE grpid='$grpid'";
        $resultAlert = mysqli_query($conn, $sql);
        if (mysqli_num_rows($resultAlert) > 0){
            $info = mysqli_fetch_all($resultAlert, MYSQLI_ASSOC);
            foreach($info as $infosubmit){
                if($infosubmit['approved'] == 0 && $infosubmit['submitted'] == 1){
                    $alert_minmeet = 1;
                }
            }
        }

        // check whether the proposal's submitted = 0 but the content is there
        $sql = "SELECT * FROM idpfile WHERE type='proposal' AND grpid='$grpid'";
        $resultAlert = mysqli_query($conn, $sql);
        if (mysqli_num_rows($resultAlert) > 0){
            $info = mysqli_fetch_all($resultAlert, MYSQLI_ASSOC);
            if($info[0]['approved'] == 0 && $info[0]['submitted'] == 1){
                $alert_proposal = 1;
            }
        }

         // check whether the report's submitted = 0 but the content is there
         $sql = "SELECT * FROM idpfile WHERE type='finalreport' AND grpid='$grpid'";
         $resultAlert = mysqli_query($conn, $sql);
         if (mysqli_num_rows($resultAlert) > 0){
             $info = mysqli_fetch_all($resultAlert, MYSQLI_ASSOC);
             if($info[0]['approved'] == 0 && $info[0]['submitted'] == 1){
                 $alert_report = 1;
             }
         }

        // check whether the vidposter's submitted = 0 but the content is there
        $sql = "SELECT * FROM idpfile WHERE type='vidposter' AND grpid='$grpid'";
        $resultAlert = mysqli_query($conn, $sql);
        if(mysqli_num_rows($resultAlert) > 0){
            $info = mysqli_fetch_all($resultAlert, MYSQLI_ASSOC);
            if($info[0]['approved'] == 0 && $info[0]['submitted'] == 1){
                $alert_vidposter = 1;
            }
        }


        mysqli_free_result($resultAlert);
    }
?>
<body>
    <!-- Navigation bar in here -->
    <div class="wrapper">
        <aside id="sidebar">
            <!-- Content for Sidebar -->
            <div class="h-100">
                <div class="sidebar-logo">
                    <a href="./home.php">FKTEN IDP</a>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        MAIN NAVIGATION
                    </li>
                    <li class="sidebar-item">
                        <a href="./profile.php" class="sidebar-link">
                            <i class="fa-solid fa-user pe-2"></i>
                            User Profile
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="./groupinfo.php" class="sidebar-link">
                            <i class="fa-solid fa-people-group pe-2"></i>
                            Group Info
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="./synopsis.php" class="sidebar-link">
                            <i class="fa-solid fa-book-open pe-2"></i>
                            Synopsis
                            <?php if($alert_synopsis): ?>
                                <i class="fa-solid fa-circle ps-2" style="color: #00FFD4;"></i>
                            <?php endif ?>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="./minMeetHome.php" class="sidebar-link">
                            <i class="fa-solid fa-calendar-check pe-2"></i>
                            Minute Meeting
                            <?php if($alert_minmeet): ?>
                                <i class="fa-solid fa-circle ps-2" style="color: #00FFD4;"></i>
                            <?php endif ?>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="./proposal.php" class="sidebar-link">
                            <i class="fa-solid fa-note-sticky pe-2"></i>
                            Project Proposal
                            <?php if($alert_proposal): ?>
                                <i class="fa-solid fa-circle ps-2" style="color: #00FFD4;"></i>
                            <?php endif ?>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="./finalreport.php" class="sidebar-link">
                            <i class="fa-solid fa-book pe-2"></i>
                            Final Report
                            <?php if($alert_report): ?>
                                <i class="fa-solid fa-circle ps-2" style="color: #00FFD4;"></i>
                            <?php endif ?>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="./vidposter.php" class="sidebar-link">
                            <i class="fa-solid fa-photo-film pe-2"></i>
                            Video & Poster
                            <?php if($alert_vidposter): ?>
                                <i class="fa-solid fa-circle ps-2" style="color: #00FFD4;"></i>
                            <?php endif ?>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="./peer.php" class="sidebar-link">
                            <i class="fa-solid fa-clipboard-list pe-2"></i>
                            Peer Review
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="./reportevalHome.php" class="sidebar-link">
                            <i class="fa-solid fa-clipboard pe-2"></i>
                            Report evaluation
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="./idpexevalHome.php" class="sidebar-link">
                            <i class="fa-regular fa-clipboard pe-2"></i>
                            Exhibition evaluation
                        </a>
                    </li>                    
                    <li class="sidebar-item">
                        <a href="./logout.php" class="sidebar-link">
                        <i class="fa-solid fa-arrow-right-from-bracket pe-2"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </aside>