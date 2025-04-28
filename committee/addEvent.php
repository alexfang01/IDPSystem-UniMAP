<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include ('../config/db_connect.php');
include './logincheckComm.php';
include './sidebarComm.php';


// displaying event information
$sql = "SELECT * FROM event ORDER BY weekNo ASC, date ASC";
$resultWeek = mysqli_query($conn, $sql);

if (isset($_POST['submit'])) {
    $weekNo = mysqli_real_escape_string($conn, $_POST['weekNumber']);
    $event = mysqli_real_escape_string($conn, $_POST['eventName']);
    $date = mysqli_real_escape_string($conn, $_POST['eventDate']);
    $startTime = mysqli_real_escape_string($conn, $_POST['startTime']);
    $endTime = mysqli_real_escape_string($conn, $_POST['endTime']);
    $venue = mysqli_real_escape_string($conn, $_POST['venue']);

    $sql = "INSERT INTO event (weekNo, event, date, startTime, endTime, venue) VALUES ('$weekNo', '$event', '$date', '$startTime', '$endTime', '$venue')";
    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Event successfully added');</script>";
        echo "<script>window.location.href = 'addEvent.php';</script>";
    } else {
        echo 'query error' . mysqli_error($conn);
    }
}

if (isset($_POST['delete'])) {
    if ($_POST['weekNumber'] != null) {
        $weekNo = mysqli_real_escape_string($conn, $_POST['weekNumber']);
        $sql = "DELETE FROM event WHERE weekNo = '$weekNo'";
        // Execute the query
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Event Week " . $weekNo . " successfully Removed');</script>";
            echo "<script>window.location.href = 'addEvent.php';</script>";
        } else {
            echo 'query error' . mysqli_error($conn);
        }
    }
}

if (isset($_POST['deleteAll'])) {
    $weekNo = mysqli_real_escape_string($conn, $_POST['weekNumber']);
    $sql = "DELETE FROM event";
    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('All Event Week " . $weekNo . " successfully Removed');</script>";
        echo "<script>window.location.href = 'addEvent.php';</script>";
    } else {
        echo 'query error' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.7.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            border-bottom: 3px solid black;
            text-align: center;
            margin-top: 10px;
        }

        .title-container {
            border-bottom: 3px solid black;
        }

        .container {
            align-items: center;

        }

        .grid-container {
            display: grid;
            grid-template-columns: 2.5fr 1fr;
            gap: 20px;
        }

        .form-container {
            max-width: 500px;
            width: 100%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;

        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border-radius: 10px;
        }

        .form-group input[type="time"] {
            width: auto;
        }

        .submit-button {
            display: flex;
            justify-content: center;
        }

        .submit-button input {
            padding: 10px 20px;
            background-color: #28a745;;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border: 1px solid black;
            border-radius: 20px;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        .submit-button input:hover {
            background-color: #218838;
        }

        .table-container {
            width: 100%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 20px;
            overflow: hidden;
        }

        .table th,
        .table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .delete-button {
            background-color: #d9534f;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .delete-button i {
            margin-right: 5px;
        }

        .delete-button:hover {
            background-color: #c9302c;
        }

        .back-button {
            background-color: #007bff;
            /* Use #0d6efd for Bootstrap 5 */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            justify-content: center;
            margin: 10px auto;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .grid-container {
                grid-template-columns: 1fr;
            }

            .form-container {
                margin-top: 20px;
            }
        }
    </style>
</head>

<body>

    <main class="container">

        <h1 class="title-container">Add New Event</h1>
        <button class="back-button" type="button" onclick="window.location.href='./home.php'">Back to Home</button>
        <div class="grid-container">
            <div class="table-container">
                <table class="table mt-2">
                    <tr>
                        <th>Week Number</th>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Venue</th>
                    </tr>
                    <?php
                    if ($resultWeek) {
                        // Output data rows
                        while ($row = mysqli_fetch_assoc($resultWeek)) {
                            echo "<tr>";
                            echo "<td>" . $row['weekNo'] . "</td>";
                            echo "<td>" . $row['event'] . "</td>";
                            echo "<td>" . $row['date'] . "</td>";
                            echo "<td>" . $row['startTime'] . "</td>";
                            echo "<td>" . $row['endTime'] . "</td>";
                            echo "<td>" . $row['venue'] . "</td>";
                            echo "</tr>";
                            echo "</br>";
                        }
                        // Free result set
                        mysqli_free_result($resultWeek);
                    } else {
                        echo "No record found";
                    }

                    ?>
                </table>
                
            </div>

            <div class="form-container">
                <h2>Event Form</h2>
                <form id="eventForm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                    <div class="form-group">
                        <label for="weekNumber">Week Number:</label>
                        <select id="weekNumber" name="weekNumber" required>
                            <option value="">Select Week</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="eventName">Event Name:</label>
                        <input type="text" id="eventName" name="eventName" required>
                    </div>
                    <div class="form-group">
                        <label for="eventDate">Event Date:</label>
                        <input type="date" id="eventDate" name="eventDate" required>
                    </div>
                    <div class="form-group">
                        <label for="startTime">Start Time:</label>
                        <input type="time" type="text" id="startTime" name="startTime" required>
                    </div>
                    <div class="form-group">
                        <label for="endTime">End Time:</label>
                        <input type="time" type="text" id="endTime" name="endTime" required>
                    </div>
                    <div class="form-group">
                        <label for="venue">Venue:</label>
                        <input type="text" id="venue" name="venue" required>
                    </div>
                    <div class="form-group submit-button">
                        <input type="submit" value="Submit" name="submit">
                    </div>
                </form>
                <div class="form-container">
                    <h2 class="">Delete Option</h2>
                    <form id="deleteForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                        <div class="form-group">
                            <label for="weekNumber">Week Number:</label>
                            <select id="weekNumber" name="weekNumber">
                                <option value="">Select Week</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                            </select>
                        </div>
                        <div class="form-group submit-button">
                            <button type="submit" class="delete-button" name="delete">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                        <div class="form-group submit-button">
                            <button type="submit" class="delete-button"
                                onclick="return confirm('Are you sure to DELETE ALL events')" name="deleteAll">
                                <i class="bi bi-trash"></i> Delete All
                            </button>
                        </div>
                    </form>
                </div>
            </div>


        </div>

    </main>



</body>
<?php mysqli_close($conn); ?>

</html>