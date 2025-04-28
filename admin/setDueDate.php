<?php
include('../config/db_connect.php');
include './logincheckAdmin.php';
include './sidebarAdmin.php';

// Extracting context data from db
$sqlcontext = "SELECT context FROM context";
$resultContext = mysqli_query($conn, $sqlcontext);
$contexts = mysqli_fetch_all($resultContext, MYSQLI_ASSOC);
mysqli_free_result($resultContext);

// Error message
$dateTime_err = $context_err = "";

// Show the due date list and context
$sqlDuelist = "SELECT duedate.dueDateTime, context.context FROM duedate INNER JOIN context ON duedate.contid = context.contid";
$resultDuelist = mysqli_query($conn, $sqlDuelist);

if (isset($_POST['setTime'])) {
    $dateTime_str = $_POST['dateTime'];
    $context = $_POST['duecontext'];

    if (empty($dateTime_str)) {
        $dateTime_err = "<div>Please select a date and time.</div>";
        echo $dateTime_err;
    } else {
        $dateTime_str = str_replace(',', '', $dateTime_str);
        $dateTime = DateTime::createFromFormat('m/d/Y g:i A', $dateTime_str);
        if (!$dateTime || $dateTime->format('m/d/Y g:i A') !== $dateTime_str) {
            $dateTime_err = "Invalid date and time format. Please use the format MM/DD/YYYY h:i A (e.g., 04/18/2024 5:38 PM).";
            echo $dateTime_err;
        }
    }

    if (empty($context)) {
        $context_err = "Please select context";
        echo $context_err;
    }

    if (!$dateTime_err && !$context_err) {
        $formattedDateTime = $dateTime->format('m/d/Y g:i A');
        $dateTime = mysqli_real_escape_string($conn, $formattedDateTime);
        $context = mysqli_real_escape_string($conn, $context);

        $sqlFindContextId = "SELECT contid FROM context WHERE context = '$context'";
        $resultID = mysqli_query($conn, $sqlFindContextId);
        $arrayResultID = mysqli_fetch_all($resultID, MYSQLI_ASSOC);
        $infoContid = $arrayResultID[0]['contid'];
        $contid = mysqli_real_escape_string($conn, $infoContid);

        $sql = "SELECT * FROM duedate WHERE contid = '$contid'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $sqlUpdate = "UPDATE duedate SET dueDateTime = '$dateTime' WHERE contid = '$contid'";
            if (mysqli_query($conn, $sqlUpdate)) {
                echo "<script>alert('Records Updated');</script>";
                echo "<script>window.location.href = 'setDueDate.php';</script>";
                exit;
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            $sqlInsert = "INSERT INTO duedate(dueDateTime, contid) VALUES ('$dateTime','$contid')";
            if (mysqli_query($conn, $sqlInsert)) {
                echo "<script>alert('Records inserted successfully');</script>";
                echo "<script>window.location.href = 'setDueDate.php';</script>";
                exit;
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }

    mysqli_free_result($result);
    mysqli_free_result($resultID);
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Datepicker</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Tempus Dominus CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempus-dominus/6.0.0/css/tempus-dominus.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <!-- Tempus Dominus JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempus-dominus/6.0.0/js/tempus-dominus.min.js"></script>
    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> <!-- notification bell -->
    <style>
        .custom-container {
            margin: 20px;
            font-family: Poppins;
            width: 60%;
            margin: 0 auto;
        }
        .container{
            margin: 50px;
            text-align: center;
        }

        header img {
            display: block;
            margin: 0 auto;
        }

        header span {
            position: absolute;
            top: 36%;
            left: 18%;
            font-size: 28px;
            color: #fff;
        }

        .para {
            margin-left: 13%;
            width: 75%;
        }

        h2 {
            color: #218838;
        }

        section {
            margin-top: 20px;
        }

        /* Tempus Dominus specific styles */
        .datepicker-container .input-group.date {
            margin-bottom: 1rem;
            width: 363px;
            margin-left:-51px;
            
        }

        .datepicker-container .tempus-dominus-widget {
            z-index: 9999;
        }

        /* Isolate Tempus Dominus styles */
        .datepicker-container .tempus-dominus-widget .td-overlay {
            background: rgba(0, 0, 0, 0.5);
        }

        .datepicker-container .tempus-dominus-widget .td-header {
            background-color: #007bff;
            color: white;
        }

        .datepicker-container .tempus-dominus-widget .td-calendar {
            background-color: white;
        }

        .datepicker-container .tempus-dominus-widget .td-days td,
        .datepicker-container .tempus-dominus-widget .td-months td,
        .datepicker-container .tempus-dominus-widget .td-years td,
        .datepicker-container .tempus-dominus-widget .td-decades td {
            color: black;
        }

        .datepicker-container .tempus-dominus-widget .td-selected {
            background-color: #007bff;
            color: white;
        }

        .datepicker-container .tempus-dominus-widget .td-hover {
            background-color: #e9ecef;
        }

        .datepicker-container .tempus-dominus-widget .td-today {
            border-color: #007bff;
        }

        /* Prevent interference with sidebar */
        #sidebar a {
            text-decoration: none;
        }
        table {
        border-collapse: collapse;
        width: 100%;
        border: 10px solid black;
        padding: 4px;
        text-align: left;
        margin: 0 auto;
        vertical-align: middle;
        border-collapse: collapse;
        border-radius: 30px;
        overflow: hidden;
    }

    th,
    td {
        font-size: 17px;
        border: 1px solid #ddd;
        padding: 4px;
        text-align: center;
        vertical-align: middle;
    }
    .dropdown-menu {
    width: auto;
    max-height: 250px;
    overflow-y: auto;
    top: 50px !important; /* Adjust this based on the actual position */
    font-family: Poppins;
}
@media (max-width: 768px) {
        .title-container {
            width: fit-content;
            margin: 0 auto;
        }

        h2 {
            margin-left: 0;
            font-size: 1.2rem;
            text-align: center;
        }

        .btn2 {
            width: 50%;
        }

        .table {
            width: 100%;
        }

        .search-container .box {
            width: 80%;
            margin-bottom: 10px;
        }

        .search-container {
            flex-direction: column;
        }

        .datepicker-container .input-group.date {
        max-width: 100%;
        justify-content: center; /* Centering the input group */
    }

    .datepicker-container button.btn {
        max-width: 100%;
        width: 100%;
        margin-left: 0 !important; /* Removing the margin for mobile view */
    }

        select.form-select {
            width: 100%;
            margin-left: 0;
        }

        button {
            width: 100%;
            margin-left: 0;
        }
        button#setTime {
        margin-left: -37px !important; /* Removing the margin-left for mobile view */
        width: 100%; /* Making the button take full width on mobile */
    }
    @media (max-width: 768px) {
    .navbar {
        flex-direction: row; /* Keep elements in a row */
        align-items: center;
        justify-content: space-between; /* Distribute space between items */
        width: 100%; /* Ensure the navbar takes the full width */
        padding: 0 10px; /* Add some padding for spacing */
    }

    .navbar-collapse {
        width: auto; /* Let it auto-adjust */
        display: flex;
        justify-content: flex-end; /* Align items to the right */
        align-items: center;
        flex-grow: 1; /* Allow it to grow to take remaining space */
    }

    .navbar-nav {
        display: flex;
        flex-direction: row;
        align-items: center;
        padding: 0; /* Remove any extra padding */
    }

    .nav-item {
        margin: 0 5px; /* Reduce margins to fit items */
    }

    .bell-icon {
        font-size: 20px; /* Adjusted for smaller screens */
        margin-right:-10px; /* Remove margin */
    }

    .profile-link {
        display: flex;
        align-items: center;
        padding-left: 0; /* Remove padding for better fit */
        font-family: 'Poppins', sans-serif;
        font-size: 16px; /* Ensure this font size matches your desired size */
        color: #0D6EFD; /* Ensure this color matches your desired color */
        text-decoration: none; /* Ensure this prevents text underline */
        margin: 0; /* Remove margin */
    }

    .profile-link img {
        margin-left: -20px; /* Smaller margin for better fit */
        height: 30px; /* Adjust height if necessary */
        width: 30px; /* Adjust width if necessary */
        margin-top: 10px;
        margin-bottom: 10px;
    }

    #sidebar-toggle {
        margin-left: -120px; /* Add margin to separate from other elements */
    }
}

        }


    </style>
</head>

<body>
    <div class="wrapper ftken-idp-system-wrapper">
        <!-- Main content in here -->
        <div class="main ftken-idp-system-main">
            <main class="content px-3 py-2">
                <div class="custom-container">
                    <h1 class="title-container">
                        Due Date Setting
                    </h1>
                        <div class="container datepicker-container">
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                                <div class="row form-group">
                                    <div class="col-sm-12">
                                        <div class="input-group date" id="datetimepicker1">
                                            <input type="text" id="dateTime" name="dateTime" class="form-control datetimepicker-input" placeholder="MM/DD/YYYY h:mm A"/>
                                            <span class="input-group-text">
                                            <span class="fa fa-calendar"></span>
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <!-- drop down menu for context -->
                            <div class="row">
                                <select id="duecontext" name="duecontext" class="form-select mb-3 ms-6 me-5" style="width: 363px; padding: 17px; font-size: 14px; margin-left: -39px;" aria-label="Select Context">
                                    <option selected value="">Select Context</option>
                                    <?php if($contexts): ?>
                                        <?php foreach($contexts as $context): ?>
                                            <option value="<?php echo htmlspecialchars($context['context']) ?>"><?php echo htmlspecialchars($context['context']) ?></option> 
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </select>
                            </div>
                            <div class="row">
                                <button type="submit" id="setTime" name="setTime" class="btn" 
                                style="width: 150px; 
                                        margin-top: 3px; 
                                        margin-left: -39px; 
                                        background-color: #F3F6F4; 
                                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
                                        border-radius: 20px 20px 20px 20px; 
                                        text-align: center;
                                        border: 2px solid black;
                                        ">Set</button> 
                            </div>
                        </form>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="background-color:#F2F2F2;">DateTime</th>
                                <th style="background-color:#F2F2F2;">Context</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultDuelist) {
                                // Output data rows
                                while ($row = mysqli_fetch_assoc($resultDuelist)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['dueDateTime'] . "</td>";
                                    echo "<td>" . $row['context'] . "</td>";
                                    echo "</tr>";
                                }
                                // Free result set
                                mysqli_free_result($resultDuelist);
                            } else {
                                echo "<tr><td colspan='2'>No record found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
            </div>
            </div>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const picker = new tempusDominus.TempusDominus(document.getElementById('datetimepicker1'), {
                display: {
                    components: {
                        decades: true,
                        year: true,
                        month: true,
                        date: true,
                        hours: true,
                        minutes: true,
                        seconds: false,
                        useTwentyfourHour: false
                    },
                    icons: {
                        time: 'fa fa-clock',
                        date: 'fa fa-calendar',
                        up: 'fa fa-chevron-up',
                        down: 'fa fa-chevron-down',
                        previous: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        today: 'fa fa-calendar-check',
                        clear: 'fa fa-trash',
                        close: 'fa fa-times'
                    }
                },
                localization: {
                    locale: 'en',
                    format: 'MM/DD/YYYY h:mm A'
                }
        });
        picker.subscribe(tempusDominus.Namespace.events.change, function (e) {
                    const selectedDate = e.detail.date;
                    const formattedDate = selectedDate.format('MM/DD/YYYY h:mm A').replace(",", "");
                    document.getElementById('dateTime').value = formattedDate;
        });
        });
    </script>
</body>

</html>
