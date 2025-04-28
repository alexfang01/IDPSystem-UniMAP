<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include ("../config/db_connect.php");
include './logincheckAdmin.php';
include './sidebarAdmin.php';

// group information
$sql = "SELECT * FROM idpgroup WHERE active = '1'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $infoGroup = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// supervisor information
$sql = "SELECT * FROM supervisor WHERE verify = '1'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $infoSV = mysqli_fetch_all($result, MYSQLI_ASSOC);
}



if (isset($_POST['submit'])) {
    $groupNum = mysqli_real_escape_string($conn, $_POST['groupNum']);
    $svid = mysqli_real_escape_string($conn, $_POST['hiddenSVid']);

    // check whether the group number exist or not
    $sql = "SELECT * FROM idpgroup WHERE grpnum = '$groupNum'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // got means valid group number
        $infoValidGrp = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // check this group whether already assign for other supevisor
        if (!$infoValidGrp[0]['idpexSV']) {
            // check whether the this supervisor id got assign group ady or not
            $sql = "SELECT groupAssign FROM supervisor WHERE svid='$svid' AND verify='1'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                // got means need to update
                $infoSVSpecific = mysqli_fetch_all($result, MYSQLI_ASSOC);
                // getting current assigned group information
                $currentGroupNum = mysqli_real_escape_string($conn, $infoSVSpecific[0]['groupAssign']);
                $newGroupNum = $currentGroupNum . 'G' . $groupNum;
                $groupAssign = mysqli_real_escape_string($conn, $newGroupNum);
                $sql = "UPDATE supervisor set groupAssign = '$groupAssign' WHERE svid = '$svid'";
                if (mysqli_query($conn, $sql)) {
                    $sql = "UPDATE idpgroup set idpexSV = '1' WHERE grpnum = '$groupNum' AND active = '1'";
                    if (mysqli_query($conn, $sql)) {
                        echo "<script>alert('Group added');</script>";
                        echo "<script>window.location.href = 'groupAssignSV.php';</script>";
                    } else {
                        echo 'query error' . mysqli_error($conn);
                    }
                } else {
                    echo 'query error' . mysqli_error($conn);
                }
            } else {
                $groupAssign = 'G' . $groupNum;
                $sql = "INSERT INTO supervisor (groupAssign) VALUES ('$groupAssign') WHERE svid = '$svid'";
                if (mysqli_query($conn, $sql)) {
                    $sql = "UPDATE idpgroup set idpexSV = '1' WHERE grpnum = '$groupNum' AND active = '1'";
                    if (mysqli_query($conn, $sql)) {
                        echo "<script>alert('New group assigned');</script>";
                        echo "<script>window.location.href = 'groupAssignSV.php';</script>";
                    } else {
                        echo 'query error' . mysqli_error($conn);
                    }
                } else {
                    echo 'query error' . mysqli_error($conn);
                }
            }
        } else {
            echo "<script>alert('Group assigned to Other SV');</script>";
            echo "<script>window.location.href = 'groupAssignSV.php';</script>";
        }
    } else {
        echo "<script> alert('Group number not exist!'); </script>";
    }

}

if (isset($_POST['delete'])) {
    $svid = mysqli_real_escape_string($conn, $_POST['hiddenSVid']);

    // info to decode the group number
    $sql = "SELECT * FROM supervisor WHERE svid='$svid'";
    $result = mysqli_query($conn, $sql);
    $info = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $sql = "UPDATE supervisor set groupAssign = '' WHERE svid = '$svid'";
    if (mysqli_query($conn, $sql)) {
        // to set back to group's idpexsupervisor to 0 again

        // decode the group informations
        $grpnumCode = $info[0]['groupAssign'];
        preg_match_all('/\d+/', $grpnumCode, $matches);
        // now numbers is stored in the array
        $numbers = $matches[0];
        foreach ($numbers as $grpnum) {
            $sql = "UPDATE idpgroup set idpexSV = '0' WHERE grpnum = '$grpnum' AND active = '1'";
            if (!mysqli_query($conn, $sql)) {
                echo 'query error' . mysqli_error($conn);
            }
        }
        echo "<script>alert('Group assigned removed');</script>";
        echo "<script>window.location.href = 'groupAssignSV.php';</script>";

    } else {
        echo 'query error' . mysqli_error($conn);
    }
}

mysqli_free_result($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Group for Examiner</title>
        <h1 class="text-center" style="margin-bottom:-10px;">Assign</h1>
        <h1 class="text-center mb-3 title-container">Group for Examiner</h1>
        <div class="button-container">
    <button style="margin-bottom:10px;" class="btn btn-primary btn-lg" type="button" onclick="window.location.href='./groupAssignPanel.php'">
        Switch to Panel
</button>
</div>
    <h4 class="text-center mb-3">*Only numbers required eg: 1,2,3.. </h4>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/4c7903acb1.js" crossorigin="anonymous"></script> -->
    <style>
        .button-container {
    display: flex;
    justify-content: center;
}

        .container {
            align-items: center;
            justify-content: center;
            margin-top: 45px;
        }

        .box {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 20px 20px 20px 20px;
            text-align: center;
        }

        .table-container {
            overflow-x: auto; /* Enable horizontal scrolling */
        }

        .table {
            border: 1px solid black;
            padding: 4px;
            text-align: center;
            margin: 0 auto;
            width: 90%;
            vertical-align: middle;
            border-collapse: collapse;
            border-radius: 20px;
            overflow: hidden;
        }

        .table th {
            background-color: #f2f2f2;
        }
        @media (max-width: 768px) {
            .title-container {
                border-bottom: 3px solid black;
                width: fit-content;
                margin: 0 auto;
            }
            .mobile-margin {
                margin-top: 5px !important;
            }
        }
    </style>
</head>

<body>
<div class="table-container">
    <div action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <table class="table container box">
            <thead>
                <tr>
                    <th>Number </th>
                    <th>Examiner name</th>
                    <th>Assigned Group</th>
                    <th>New Group Number</th>
                    <th>Submit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <?php $i = 1; ?>
            <tbody class="table-group-divider">
                <?php foreach ($infoSV as $row): ?>
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['groupAssign']); ?></td>
                            <td>
                                <input type="hidden" name="hiddenSVid" value="<?php echo htmlspecialchars($row['svid']) ?>">
                                <input type="text" name="groupNum" placeholder="eg: 1, 2, 3.. ">
                            </td>
                            <td>
                                <button type="submit" name="submit" class="btn btn-success mobile-margin" value="<?php echo htmlspecialchars($row['svid']) ?>">
                                    <i class="bi bi-person-workspace"></i>
                                </button>
                            </td>
                            <td><button type="submit" name="delete" class="btn btn-danger bi bi-trash" id="delete"></td>
                            <?php $i++; ?>
                        </tr>
                    </form>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </div>

</body>
<?php mysqli_close($conn); ?>

</html>