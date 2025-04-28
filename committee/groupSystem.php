<?php
include './logincheckComm.php';
include './sidebarComm.php';

if (isset($_SESSION['groupping']) && $_SESSION['groupping'] !== null) {
    $groupping = $_SESSION['groupping'];
}

if (isset($_SESSION['allgroup']) && $_SESSION['allgroup'] !== null) {
    $allgroup = $_SESSION['allgroup'];
}
if (isset($_SESSION['totalgrp']) && $_SESSION['totalgrp'] !== null) {
    $totalgrp = $_SESSION['totalgrp'];
}

if (isset($_SESSION['avgstud']) && $_SESSION['avgstud'] !== null) {
    $avgstud = $_SESSION['avgstud'];
}

if (isset($_SESSION['sv']) && $_SESSION['sv'] !== null) {
    $sv = $_SESSION['sv'];
}

//if new year pressed
if (isset($_POST['newYear'])) {
    $sql = "SELECT * FROM supervisor WHERE verify = '1'";
    $result = $conn->query($sql);
    $sv = [];
    if ($result->num_rows > 0) {
        while (($row = $result->fetch_assoc())) {
            $sv[] = $row;
        }
    }
    foreach ($sv as $x):
        $svidx = $x['svid'];
        $svy1 = $x['y0'];
        $svy2 = $x['y1'];
        $svy3 = $x['y2'];

        $sql = "UPDATE supervisor SET y0 = '' WHERE svid = $svidx";
        if ($conn->query($sql) === TRUE) {
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $sql = "UPDATE supervisor SET y1 = '$svy1' WHERE svid = $svidx";
        if ($conn->query($sql) === TRUE) {
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $sql = "UPDATE supervisor SET y2 = '$svy2' WHERE svid = $svidx";
        if ($conn->query($sql) === TRUE) {
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $sql = "UPDATE supervisor SET y3 = '$svy3' WHERE svid = $svidx";
        if ($conn->query($sql) === TRUE) {
        } else {
            echo "Error updating record: " . $conn->error;
        }
    endforeach;

    $sql = "UPDATE idpgroup SET active = 0";

    if ($conn->query($sql) === TRUE) {
        echo "Records updated successfully.";
    } else {
        echo "Error updating records: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    integrity="sha512-fDnh8MPtZZ2ayE8D6f6fvMm8eEaMYPeMgpIH93nUuO8hjO7w/JjrGzrj9oH9W/zB84r+JUtYW+lpPF9LCJLqKQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">
<style>
    
            .table-container {
            overflow-x: auto;
            
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
        border-radius: 20px;
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

    th {
        background-color: #f2f2f2;
    }

    .group-heading {
        font-weight: bold;
        background-color: #e0e0e0;
        padding: 10px;
    }

    .btn-end {
        vertical-align: right;
    }

    .student-table {
        margin-bottom: 20px;
    }

    .student-table td:first-child {
        font-weight: bold;
    }

    .action-btn {
        padding: 6px 12px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .action-btn:hover {
        background-color: #45a049;
    }
        .button-container {
            display: flex;
            flex-direction: row;
            justify-content: center;
            gap: 10px; /* Add space between the buttons */
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .title-container {
                width: fit-content;

            }
            .button-container {
                flex-direction: column; /* Arrange buttons vertically on mobile */
                align-items: center;
                gap: 2px; /* Add space between the buttons */
            }

            .button-container button {
                margin-bottom: 10px; /* Add margin below each button for additional spacing */
            }
        }
</style>

<head>
    <title>Student Grouping System</title>
</head>

<body>
<h1 class="text-center" style="margin-bottom:-10px;">Student</h1>
    <h1 class="text-center mb-3 title-container">Grouping System </h1>
    <a class="text-center mb-3"> Randomize Option:
    <div class="button-container">
        <button class="btn btn-secondary" onclick="window.location.href='./groupSystemShuffle.php'">Students</button>
        <button class="btn btn-secondary"
            onclick="window.location.href='./groupSystemShuffleSVT.php'">Supervisor</button>
        <button class="btn btn-primary" onclick="window.location.href='./groupSvTheme.php'">Add Supervisor
            Theme</button>
        <!-- <button type="submit" name="newYear"
            onclick="return confirm('All groups will be deactivated and CLEAR for new batch')"
            class="btn btn-end btn-danger">Start New Batch</button> -->
            </div>
    </a><h4 class="text-center mb-3">*Please make sure Theme is assigned to each Supervisor before locking</h4>

    <div class="table-container">
    <table border="1" id="studentTable" class="table table-striped">


        <?php
        $groupIndex = 1;
        $counter = 1;
        $arygrp;
        //check if fetched
        if (isset($_SESSION['avgstud']) && $_SESSION['avgstud'] !== null) {
            //print tables for each group
            for ($i = 1; $i <= $avgstud + $totalgrp; $i++) {


                //detect locked group
                if (isset($allgroup) && (in_array($i, $allgroup))) {
                    echo "</tr><th>Group</th><th>Students Lists</th>
                    <th></th>            
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Supervisor Name</th><th>Theme</th><th>Action</th>";
                    echo "<tr class=\"group_table\" id=\"group_";
                    echo $groupIndex;
                    echo "\"><td>";
                    echo $groupIndex;
                    echo "</td>";
                    echo "<td colspan=\"6\"><table border=\"1\"> ";
                    echo "<tr>
                <th>Student Name</th>
                <th>Gender</th>
                <th>Matric Number</th>
                <th>CGPA</th>
                <th>NRIC Number</th>
                <th>Program</th>
                </tr>";

                    //Fetch Groupped student
                    $sql = "SELECT student.*, supervisor.svid, supervisor.name AS svname, theme.title FROM student INNER JOIN idpgroup ON student.grpid = idpgroup.grpid INNER JOIN supervisor ON idpgroup.grpid = supervisor.grpid INNER JOIN theme ON idpgroup.themeid = theme.themeid WHERE student.verify = '1' AND idpgroup.grpnum = '$i' AND idpgroup.active = 1 ORDER BY cgpa DESC";
                    $result = $conn->query($sql);
                    $studentgp2 = [];
                    if ($result->num_rows > 0) {
                        while (($row = $result->fetch_assoc())) {
                            $studentgp2[] = $row;
                        }
                    }
                    foreach ($studentgp2 as $student):
                        echo "<tr><td>";
                        echo $student['name'];
                        echo "</td><td>";
                        echo $student['gender'];
                        echo "</td><td>";
                        echo $student['matric'];
                        echo "</td><td>";
                        echo $student['cgpa'];
                        echo "</td><td>";
                        echo $student['ic'];
                        echo "</td><td>";
                        echo $student['prog'];
                        echo "</td></tr>";


                    endforeach;
                    echo "</table><td>";
                    if (isset($studentgp2[0]['svname'])) {
                        echo $studentgp2[0]['svname'];
                    }
                    echo "</td><td>";
                    if (isset($studentgp2[0]['title'])) {
                        echo $studentgp2[0]['title'];
                    }
                    echo "</td><td><p class=\"\"></p>";
                    echo "<form id=\"myForm\" method=\"post\" action=\"groupSystemUnlock.php\">";
                    echo "<button type=\"submit\" name=\"unlock\" class=\"btn btn-danger\" onclick=\"return confirm('Are you sure you want to Unlock group?')\" value=\"";
                    echo $groupIndex;
                    echo "\"";
                    echo ">LOCKED<i class=\"bi bi-lock-fill\"></i></button></form>";
                    echo "</td></td>";
                    $counter++;
                    $arygrp[] = $groupIndex;
                } else {
                    //detect ungrouped group
                    echo "</tr><th>Group</th><th>Students Lists</th><th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Supervisor Name</th><th>Theme</th><th>Action</th>";
                    echo "<tr class=\"group_table\" id=\"group_";
                    echo $groupIndex;
                    echo "\"><td>";
                    echo $groupIndex;
                    echo "</td>";
                    echo "<td colspan=\"6\"><table border=\"1\"> ";
                    echo "<tr>
                <th>Student Name</th>
                <th>Gender</th>
                <th>Matric Number</th>
                <th>CGPA</th>
                <th>NRIC Number</th>
                <th>Program</th>
                </tr>";
                    if (isset($groupping[$i - $counter])) {
                        foreach ($groupping[$i - $counter] as $student):
                            if (!is_null($student)) {
                                echo "<tr><td>";
                                echo $student['name'];
                                echo "</td><td>";
                                echo $student['gender'];
                                echo "</td><td>";
                                echo $student['matric'];
                                echo "</td><td>";
                                echo $student['cgpa'];
                                echo "</td><td>";
                                echo $student['ic'];
                                echo "</td><td>";
                                echo $student['prog'];
                                echo "</td></tr>";
                            }
                        endforeach;
                    }
                    echo "</table><td>";
                    echo $sv[$i - $counter]['name'];
                    echo "</td><td>";
                    echo $sv[$i - $counter]['y00'];
                    echo "</td><td>";
                    if (isset($groupping[$i - $counter])) {
                        echo "<form id=\"myForm\" method=\"post\" action=\"groupSystemLock.php\">";
                        echo "<button type=\"submit\" name=\"fetch\" class=\"btn btn-success\" value=\"";
                        echo $groupIndex;
                        echo "\"";
                        echo "><i class=\"bi bi-unlock-fill\"></i></button></form>";
                    }
                    echo "</td></td>";
                }
                $groupIndex++;
            }
            $_SESSION['counter'] = $counter;
            if (isset($arygrp)) {
                $_SESSION['arygrp'] = $arygrp;
            }

        } else {
            //Sample table when not fetch data
            echo "</tr><th>Group</th><th>Students Lists</th><th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Supervisor Name</th><th>Theme</th><th>Action</th>";
            echo "<tr class=\"group_table\" id=\"group_";
            echo "XXX";
            echo "\"><td>";
            echo "PLEASE GET GROUPING";
            echo "</td>";
            echo "<td colspan=\"6\"><table border=\"1\"> ";
            echo "<tr>
                <th>Student Name</th>
                <th>Gender</th>
                <th>Matric Number</th>
                <th>CGPA</th>
                <th>NRIC Number</th>
                <th>Program</th>
                </tr></table><td>";
            echo "PLEASE GET GROUPING</td>";
            echo "</td><td>";
            echo "PLEASE GET GROUPING</td>";
            echo "</td><td>";
        }

        ?>



    </table>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Initialize notification dot and list
            const notificationDot = document.getElementById('notificationDot');
            const notificationList = document.getElementById('notificationList');

            // Check local storage for notifications
            const storedNotifications = JSON.parse(localStorage.getItem('notifications')) || [];
            updateNotificationList(storedNotifications);

            // Fetch notifications
            fetchNotifications();
            setInterval(fetchNotifications, 5000); // Poll every 5 seconds

            async function fetchNotifications() {
                try {
                    const response = await fetch('notificationFetch.php');

                    if (response.status === 204) {
                        return;
                    }

                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }

                    const newNotifications = await response.json();
                    console.log('Notifications fetched:', newNotifications);

                    if (newNotifications.length > 0) {
                        notificationDot.style.display = 'block';
                        const allNotifications = [...newNotifications, ...storedNotifications];
                        localStorage.setItem('notifications', JSON.stringify(allNotifications));
                        updateNotificationList(allNotifications);
                    }
                } catch (error) {
                    console.error('Error fetching notifications:', error);
                }
            }

            function updateNotificationList(notifications) {
                notificationList.innerHTML = '';
                if (notifications.length > 0) {
                    notifications.forEach(notification => {
                        const li = document.createElement('li');
                        li.className = 'dropdown-item';
                        li.textContent = notification.message;
                        notificationList.appendChild(li);
                    });
                } else {
                    const noNotification = document.createElement('li');
                    noNotification.className = 'dropdown-item';
                    noNotification.textContent = 'No notifications';
                    notificationList.appendChild(noNotification);
                }
            }

            document.getElementById('notificationDropdown').addEventListener('click', async function () {
                if (notificationList.innerHTML === '') {
                    const notifications = JSON.parse(localStorage.getItem('notifications')) || [];
                    updateNotificationList(notifications);
                }

                notificationDot.style.display = 'none';
                localStorage.removeItem('showNotificationDot');

                try {
                    // Mark notifications as read
                    const markReadResponse = await fetch('notificationMarkRead.php');
                    if (!markReadResponse.ok) {
                        throw new Error('Failed to mark notifications as read');
                    }
                } catch (error) {
                    console.error('Error marking notifications as read:', error);
                }
            });

            // Check if there is a message in the session and show notification
            <?php if (isset($_SESSION['message'])): ?>
                const message = "<?php echo $_SESSION['message']; ?>";
                storedNotifications.push({ message });
                localStorage.setItem('notifications', JSON.stringify(storedNotifications));
                updateNotificationList(storedNotifications);
                notificationDot.style.display = 'block';
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
        });
    </script>
</body>

</html>