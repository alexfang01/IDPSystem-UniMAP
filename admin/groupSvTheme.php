<!DOCTYPE html>
<html lang="en">
<?php
include ('../config/db_connect.php');
include './logincheckAdmin.php';
include './sidebarAdmin.php';
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

//if edit pressed
if (isset($_POST['idGet'])) {
    if (isset($_POST['themeGet'])) {
        $newTheme = $_POST['themeGet'];
        $targetSV = $_POST['idGet'];
        $sql = "UPDATE supervisor
        SET y0 = '$newTheme' WHERE svid = $targetSV";
        if ($conn->query($sql) === TRUE) {
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
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
}

// Fetch all verify supervisor 
$sql = "SELECT * FROM supervisor WHERE verify = '1'";
$result = $conn->query($sql);
$sv = [];
if ($result->num_rows > 0) {
    while (($row = $result->fetch_assoc())) {
        $sv[] = $row;
    }
}

//fetch all theme
$sql = "SELECT * FROM theme ";
$result = $conn->query($sql);
$themeAll = [];
if ($result->num_rows > 0) {
    while (($row = $result->fetch_assoc())) {
        $themeAll[] = $row;
    }
}

//change id to theme
$counterz = 0;
foreach ($sv as $a):
    // Fetch Theme
    $y3 = $a['y3'];
    $y2 = $a['y2'];
    $y1 = $a['y1'];
    $y0 = $a['y0'];
    $sql = "SELECT title FROM theme WHERE themeid = '$y3' ";
    $result = $conn->query($sql);
    $theme = $result->fetch_assoc();
    if ($theme != NULL) {
        $sv[$counterz]['y33'] = $theme['title'];
    } else {
        $sv[$counterz]['y33'] = '';
    }
    $sql = "SELECT title FROM theme WHERE themeid = '$y2' ";
    $result = $conn->query($sql);
    $theme = $result->fetch_assoc();
    if ($theme != NULL) {
        $sv[$counterz]['y22'] = $theme['title'];
    } else {
        $sv[$counterz]['y22'] = '';
    }
    $sql = "SELECT title FROM theme WHERE themeid = '$y1' ";
    $result = $conn->query($sql);
    $theme = $result->fetch_assoc();
    if ($theme != NULL) {
        $sv[$counterz]['y11'] = $theme['title'];
    } else {
        $sv[$counterz]['y11'] = '';
    }
    $sql = "SELECT title FROM theme WHERE themeid = '$y0' ";
    $result = $conn->query($sql);
    $theme = $result->fetch_assoc();
    if ($theme != NULL) {
        $sv[$counterz]['y00'] = $theme['title'];
    } else {
        $sv[$counterz]['y00'] = '';
    }
    $counterz++;
endforeach;


?>

<html>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    integrity="sha512-fDnh8MPtZZ2ayE8D6f6fvMm8eEaMYPeMgpIH93nUuO8hjO7w/JjrGzrj9oH9W/zB84r+JUtYW+lpPF9LCJLqKQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">
<style>
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
        .btn-back{
            margin-left: 10px;
            border-radius: 20px 0px 0px 20px;
        }
</style>
<body>
<h1 class="text-center mb-3 title-container"> Add Supervisor Theme</h1>
    <a class="text-left mb-3">
        <button onclick="window.location.href='./groupSystemClear.php'" class="btn btn-primary btn-back">Back to
            Grouping</button></a>
            
    <!-- <a class="text-center mb-3">
        <form name="newYear" action="./groupSvTheme.php" method="POST">
            <button type="submit" name="newYear" onclick="return confirm('Are you sure you want move new year?')"
                class="btn btn-danger btn-lg">End current year</button>
        </form>
    </a>
    <br> -->
    <table border="1" id="studentTable" class="table table-striped">
        <tr>
            <th>ID</th>
            <th>Staff Number</th>
            <th>Supervisor Name</th>
            <th>Past Theme 1</th>
            <th>Past Theme 2</th>
            <th>Past Theme 3</th>
            <th>Theme Current Year</th>
            <th>Action</th>
        </tr>

        <?php
        foreach ($sv as $a):
            echo "<tr><td>";
            echo $a['svid'];
            echo "</td><td>";
            echo $a['staffnum'];
            echo "</td><td>";
            echo $a['name'];
            echo "</td><td>";
            echo $a['y33'];
            echo "</td><td>";
            echo $a['y22'];
            echo "</td><td>";
            echo $a['y11'];
            echo "</td><td>";
            echo "<form action=\"groupSvTheme.php\" method=\"POST\">";
            echo "<select name=\"themeGet\">theme";
            echo "<option value=";
            echo $a['y0'];
            echo ">";
            echo $a['y00'];
            $themeFilter = [];
            foreach ($themeAll as $c):
                if ($a['y1'] != $c['themeid'] && $a['y2'] != $c['themeid'] && $a['y3'] != $c['themeid']) {
                    $themeFilter[] = $c;
                }
            endforeach;
            echo "<option value=\"\">-</option>";
            foreach ($themeFilter as $b):
                echo "<option value=\" ";
                echo $b['themeid'];
                echo "\">";
                echo $b['title'];
                echo "</option>";
            endforeach;
            echo "</select>";
            echo "</td><td>";
            echo "<button type=\"submit\" name=\"idGet\" class=\"btn btn-success\" value=\"";
            echo $a['svid'];
            echo "\"";
            echo "><i class=\"bi bi-save\"></i></form>";
            echo "</td></tr>";
        endforeach;
        ?>

    </table>

</body>

</html>