<?php
if (!isset($_SESSION)) {
    session_start();
    $host = "localhost"; // Your MySQL server hostname
    $user = "root";   // Your MySQL username
    $pass = ""; // Your MySQL password
    $db = "idpsystem";       // Your MySQL database name

    // Create a connection
    $conn = mysqli_connect($host, $user, $pass, $db);

    // Check if the connection was successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
}

if (isset($_SESSION['groupping']) && $_SESSION['groupping'] !== null) {
    $groupping = $_SESSION['groupping'];
}
if (isset($_SESSION['arygrp']) && $_SESSION['arygrp'] !== null) {
    $arygrp = $_SESSION['arygrp'];
}
if (isset($_SESSION['student1']) && $_SESSION['student1'] !== null) {
    $student1 = $_SESSION['student1'];
}
if (isset($_SESSION['student2']) && $_SESSION['student2'] !== null) {
    $student2 = $_SESSION['student2'];
}
if (isset($_SESSION['student3']) && $_SESSION['student3'] !== null) {
    $student3 = $_SESSION['student3'];
}
if (isset($_SESSION['student4']) && $_SESSION['student4'] !== null) {
    $student4 = $_SESSION['student4'];
}
if (isset($_SESSION['sv']) && $_SESSION['sv'] !== null) {
    $sv = $_SESSION['sv'];
}
if (isset($_SESSION['counter']) && $_SESSION['counter'] !== null) {
    $counter = $_SESSION['counter'];
}

//fetch insert group    
if (isset($_POST['fetch'])) {
    // After processing the group, set a session message
    $_SESSION['notifications'][] = "Group {$_POST['fetch']} has been locked successfully";

    // Limit the number of messages to 10
    if (count($_SESSION['notifications']) > 10) {
        array_shift($_SESSION['notifications']);
    }

    $group = $_POST['fetch'];
    $greater = 0;
    if (isset($arygrp)) {
        foreach ($arygrp as $numb):
            if ($numb > $group) {
                $greater++;
            }
        endforeach;
    }


    $sql = "SELECT grpid FROM idpgroup WHERE grpnum = '$group' AND active = 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the result
        $row = $result->fetch_assoc();
        $grpid = $row['grpid'];

        $studentid1 = $student1[$group - $counter + $greater]['studentid'];
        $studentid2 = $student2[$group - $counter + $greater]['studentid'];
        $studentid3 = $student3[$group - $counter + $greater]['studentid'];
        if (isset($student4[$group - $counter + $greater]['studentid'])) {
            $studentid4 = $student4[$group - $counter + $greater]['studentid'];
        }
        $svid = $sv[$group - $counter + $greater]['svid'];
        $themeid = $sv[$group - $counter + $greater]['y0'];
        if ($themeid != 0) {
            // Prepare and bind the SQL statement
            $sql = "UPDATE student SET grpid = ? WHERE studentid IN (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $grpid, $studentid1, $studentid2, $studentid3, $studentid4);
            // Execute the statement
            if ($stmt->execute()) {
                $sql = "UPDATE supervisor SET grpid = ? WHERE svid = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $grpid, $svid);
                // Execute the statement
                if ($stmt->execute()) {
                    $sql = "UPDATE idpgroup SET themeid = ? WHERE grpid = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $themeid, $grpid);

                    // Execute the statement
                    if ($stmt->execute()) {
                        // Set the notification message
                        $_SESSION['message'] = "Group $group has been locked successfully.";
                    } else {
                        echo "Error updating records: " . $conn->error;
                    }
                } else {
                    echo "Error updating records: " . $conn->error;
                }
            }
            // Close statement
            $stmt->close();
        } else {
            echo '<script language="javascript">';
            echo 'alert("Please Add Supervisor Theme! (Theme cannot be empty)")';
            echo '</script>';
            header('Refresh:0; url=./groupSystemClear.php');
            exit;
        }

    } else {
        $themeid = $sv[$group - $counter + $greater]['y0'];
        if ($themeid != 0) {
            $sql = "INSERT INTO idpgroup (grpnum, active) VALUES ('$group', '1') ";
            if ($conn->query($sql) === TRUE) {
            } else {
                echo "Error updating record: " . $conn->error;
            }

            $sql = "SELECT grpid FROM idpgroup WHERE grpnum = '$group' AND active = 1";
            $result = $conn->query($sql);
            // Fetch the result
            $row = $result->fetch_assoc();
            $grpid = $row['grpid'];

            $studentid1 = $student1[$group - $counter + $greater]['studentid'];
            $studentid2 = $student2[$group - $counter + $greater]['studentid'];
            $studentid3 = $student3[$group - $counter + $greater]['studentid'];
            if (isset($student4[$group - $counter + $greater]['studentid'])) {
                $studentid4 = $student4[$group - $counter + $greater]['studentid'];
            }
            $svid = $sv[$group - $counter + $greater]['svid'];
            $themeid = $sv[$group - $counter + $greater]['y0'];
            if ($themeid != 0) {
                // Prepare and bind the SQL statement
                $sql = "UPDATE student SET grpid = ? WHERE studentid IN (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $grpid, $studentid1, $studentid2, $studentid3, $studentid4);
                // Execute the statement
                if ($stmt->execute()) {
                    $sql = "UPDATE supervisor SET grpid = ? WHERE svid = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $grpid, $svid);
                    // Execute the statement
                    if ($stmt->execute()) {
                        $sql = "UPDATE idpgroup SET themeid = ? WHERE grpid = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ss", $themeid, $grpid);

                        // Execute the statement
                        if ($stmt->execute()) {
                            // Set the notification message
                            $_SESSION['message'] = "Group $group has been locked successfully.";
                        } else {
                            echo "Error updating records: " . $conn->error;
                        }
                    } else {
                        echo "Error updating records: " . $conn->error;
                    }
                }
                // Close statement
                $stmt->close();
            } else {
                echo '<script language="javascript">';
                echo 'alert("Please Add Supervisor Theme! (Theme cannot be empty)")';
                echo '</script>';
                header('Refresh:0; url=./groupSystemClear.php');
                exit;
            }
        }
    }
}
header("Location: ./groupSystemClear.php");


?>