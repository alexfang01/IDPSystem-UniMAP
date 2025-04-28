<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include ('../config/db_connect.php');
include './logincheckAdmin.php';
include './sidebarAdmin.php';
?>

<style>
    .title-container {
        border-bottom: 3px solid black;
    }

    .table-container {

        overflow-x: auto;
        /* Enables horizontal scrolling */
        margin: 0 auto;
        width: 90%;
    }

    .table {
        border: 1px solid black;
        padding: 4px;
        margin: 0 auto;
        width: 90%;
        vertical-align: middle;
        border-collapse: collapse;
        border-radius: 20px;
        overflow: hidden;

    }

    th,
    td {
        border: 1px solid black;
        padding: 10px;
        text-align: center;
        /* Align text horizontally */
        vertical-align: middle;
        /* Align text vertically */
    }

    .btn2 {
        display: block;
        margin: 20px auto;
        width: 20%;
    }

    .details-container {
        margin-left: 15%;
    }

    h2 {
        font-weight: bold;
        font-size: 25px;
    }

    h3 {
        margin-left: 15%;
        margin-right: 15%;
        font-size: 21px;
        margin-bottom: 5px;
    }

    .search-container {
        display: flex;
        align-items: center;
        justify-content: center;
        /* Center the content horizontally */
        margin-bottom: 20px;
        width: 100%;
        /* Let the container take full width */
        max-width: 600px;
        /* Optional: Set a maximum width */
        margin: 0 auto;
        /* Center the container itself */
    }

    .search-container .box {
        border: 2px solid #ccc;
        border-radius: 20px;
        font-size: 20px;
        font-family: 'Poppins', sans-serif;
        text-align: center;
        border: 2px solid black;
        margin: 0 10px;
        /* Optional: Add margin between elements */
    }

    .search-container .box::placeholder {
        color: #999;
    }

    .search-container button {
        border: 2px solid black;
        border-radius: 20px;
        background-color: #F3F6F4;
        color: black;
        font-size: 16px;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
    }

    .search-container button:hover {
        background-color: #45a049;
    }

    .table th {
        background-color: #f2f2f2;
    }

    @media (max-width: 768px) {
        .title-container {
            width: fit-content;
            margin: 0 auto;
        }

        .btn2 {
            width: 50%;
        }

        .details-container {
            margin-left: 10px;
            font-size: 1.2rem;
        }

        h2 {
            font-size: 1rem;
            text-align: left;
        }

        h3 {
            font-size: 0.9rem;
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
    }
</style>
<html>

<body>
    <h1 class="text-center" style="margin-bottom:-10px;"> View All</h1>
    <h1 class="text-center mb-3 title-container"> Report & Files </h1>
    <?php
    $highmin = 0;
    $data = [];
    if (isset($_POST['grpid'])) { //view students
        echo "<br><button type=\"submit\" class=\"btn btn2 btn-primary\" onclick=\"window.location.href='./viewAll.php'\">Back to List";
        echo "</button></td></tr><br>";
        echo "<div class=\"table-container\"><table class=\"table table-bordered table-light\">
            <thead class=\" thead-dark\">";
        getHeadStudent();
        $grpid = $_POST['grpid'];
        $sql = "SELECT idpgroup.grpnum, supervisor.name AS svname, theme.title AS theme, idpsynopsis.title ,idpsynopsis.synopsis FROM idpgroup 
                INNER JOIN supervisor ON idpgroup.grpid = supervisor.grpid INNER JOIN theme ON idpgroup.themeid = theme.themeid LEFT JOIN idpsynopsis ON idpgroup.grpid = idpsynopsis.grpid 
                WHERE idpgroup.grpid = '$grpid'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $grpnoz = $row['grpnum'];
            $svnamez = $row['svname'];
            $titlez = $row['title'];
            $themez = $row['theme'];
        }

        echo "<h2>Group Number : &nbsp;&nbsp;$grpnoz </h2>";
        echo "<h2>Supervisor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;$svnamez</h2>";
        echo "<h2>Title &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;$titlez </h2>";
        echo "<h2>Theme &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;$themez</h2>";

        echo "<br>";
        $data = [];
        $record = "SELECT * FROM `student` WHERE grpid = '$grpid' ORDER BY cgpa DESC";
        $result = $conn->query($record);
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $i = 1;
        foreach ($data as $row) {
            $name = $row['name'];
            $matric = $row['matric'];
            $ic = $row['ic'];
            $cgpa = $row['cgpa'];
            $prog = $row['prog'];
            $phnum = $row['phnum'];
            $email = $row['email'];
            $lead = $row['leader'];
            echo "<tr>";
            echo "<td>$i</td>";
            if ($lead == 1) {
                echo "<td class=\"fw-bold\">$name (Leader)</td>";
            } else {
                echo "<td>$name</td>";
            }
            echo "<td>$matric</td>";
            echo "<td>$ic</td>";
            echo "<td>$cgpa</td>";
            echo "<td>$prog</td>";
            echo "<td>$phnum</td>";
            echo "<td>$email</td>";
            echo "</tr>";
            $i++;
        }
        echo "</table></div>";
    } elseif (isset($_POST['viewsynopsis'])) { //view synopsis
        echo "<br><button type=\"submit\" class=\"btn btn2 btn-primary\" onclick=\"window.location.href='./viewAll.php'\">Back to List";
        echo "</button></td></tr><br>";

        $grpid = $_POST['viewsynopsis'];
        $sql = "SELECT idpgroup.grpnum, supervisor.name AS svname, theme.title AS theme, idpsynopsis.title ,idpsynopsis.synopsis FROM idpgroup 
                INNER JOIN supervisor ON idpgroup.grpid = supervisor.grpid INNER JOIN theme ON idpgroup.themeid = theme.themeid LEFT JOIN idpsynopsis ON idpgroup.grpid = idpsynopsis.grpid 
                WHERE idpgroup.grpid = '$grpid'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $grpnoz = $row['grpnum'];
            $svnamez = $row['svname'];
            $titlez = $row['title'];
            $themez = $row['theme'];
        }

        echo "<div class=\"details-container\">";
        echo "<h2>Group &nbsp;&nbsp;: &nbsp;&nbsp;$grpnoz </h2>";
        echo "<h2>Title  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;$titlez </h2>";
        echo "</div>";
        $data = [];
        $record = "SELECT * FROM `idpsynopsis` WHERE grpid = '$grpid'";
        $result = $conn->query($record);
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        foreach ($data as $row) {
            $synopsis = $row['synopsis'];
            $objectives = $row['objectives'];
            echo "<br>";
            echo "<div class=\"details-container\">";
            echo "<h2>Synopsis&nbsp;&nbsp;:</h2>";
            echo "<h3>$synopsis</h3>";
            echo "<br>";
            echo "<h2>Objectives&nbsp;&nbsp;:</h2>";
            echo "<h3>$objectives</h3>";
            echo "<br>";
            echo "<br>";
            echo "</div>";
        }

    } elseif (isset($_POST['viewmm'])) { //view min meetings
        echo "<br><button type=\"submit\" class=\"btn btn2 btn-primary\" onclick=\"window.location.href='./viewAll.php'\">Back to List";
        echo "</button></td></tr><br>";
        echo "<div class=\"table-container\"><table class=\"table table-bordered table-light\">
            <thead class=\" thead-dark\">";
        echo "<tr>";
        echo "<th>WeekNo</th>";
        echo "<th>Taker</th>";
        echo "<th>Attendees</th>";
        echo "<th>Summary</th>";
        echo "<th>Submit Time</th>";
        echo "</tr>";
        $grpid = $_POST['viewmm'];
        $sql = "SELECT idpgroup.grpnum, supervisor.name AS svname, theme.title AS theme, idpsynopsis.title ,idpsynopsis.synopsis FROM idpgroup 
                INNER JOIN supervisor ON idpgroup.grpid = supervisor.grpid INNER JOIN theme ON idpgroup.themeid = theme.themeid LEFT JOIN idpsynopsis ON idpgroup.grpid = idpsynopsis.grpid 
                WHERE idpgroup.grpid = '$grpid'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $grpnoz = $row['grpnum'];
            $svnamez = $row['svname'];
            $titlez = $row['title'];
            $themez = $row['theme'];
        }

        echo "<div class=\"details-container\">";
        echo "<h2>Group &nbsp;&nbsp;: &nbsp;&nbsp;$grpnoz </h2>";
        echo "<h2>Title  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;$titlez </h2>";
        echo "</div>";

        $sql = "SELECT name FROM student WHERE grpid = '$grpid' ORDER BY name ASC";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $grpmem[] = $row;
        }
        foreach ($grpmem as $gpm) {
            // Fetch total grouped student
            $nname = $gpm['name'];
            // Count meetings for each student
            $sql_count_meetings = "SELECT COUNT(*) as total_mm FROM minmeet WHERE taker = ? AND grpid = ?";
            $stmt_count_meetings = $conn->prepare($sql_count_meetings);
            $stmt_count_meetings->bind_param("ss", $nname, $grpid);
            $stmt_count_meetings->execute();
            $result_count_meetings = $stmt_count_meetings->get_result();
            $row_count_meetings = $result_count_meetings->fetch_assoc();

            // Output the result
            if ($row_count_meetings['total_mm'] > 0) {
                echo "<div class=\"details-container\"><h3>$nname : {$row_count_meetings['total_mm']} meets taken</h3></div>";
            } else {
                echo "<div class=\"details-container\"><h3>$nname : -</h3></div>";
            }
            // if (isset($mm1)) {
            //     echo "<h2>$nname  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;$mm1 meet done</h2>";
            // } else{
            //     echo "<h2>$nname  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;0 meet done</h2>";
            // }
    
        }

        echo "<br>";
        $data = [];
        $record = "SELECT * FROM `minmeet` WHERE grpid = '$grpid' ORDER BY weekNo ASC";
        $result = $conn->query($record);
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $attendees[$i][] = $row['attendee1'];
            $attendees[$i][] = $row['attendee2'];
            $attendees[$i][] = $row['attendee3'];
            $data[] = $row;
            $i++;
        }
        $i = 0;
        foreach ($data as $row) {
            $weekNo = $row['weekNo'];
            $taker = $row['taker'];
            $summary = $row['summary'];
            $submitTime = $row['submitTime'];
            echo "<tr>";
            echo "<td>$weekNo</td>";
            echo "<td>$taker</td><td>";
            foreach ($attendees[$i] as $atd) {
                echo "$atd";
                echo "<br>";
            }
            echo "</td><td>$summary</td>";
            echo "<td>$submitTime</td>";
            echo "</tr>";
            $i++;
        }
        echo "</table></div>";
    } else {
        $record = "SELECT idpgroup.*, supervisor.name AS svname, theme.title AS theme, idpsynopsis.title, idpsynopsis.synopsis FROM idpgroup 
            INNER JOIN supervisor ON idpgroup.grpid = supervisor.grpid INNER JOIN theme ON idpgroup.themeid = theme.themeid LEFT JOIN idpsynopsis ON idpgroup.grpid = idpsynopsis.grpid 
            WHERE idpgroup.active = 1 AND idpgroup.themeid is NOT NULL ORDER BY grpnum ASC";
        $result = $conn->query($record);
        while ($row = $result->fetch_assoc()) {
            if ($row['title'] == null) {
                $row['title'] = "No Title Submmited";
            }
            if ($row['synopsis'] == null) {
                $row['synopsis'] = "N/A";
            }
            $row['grpnum'] = "G" . $row['grpnum'];
            $data[] = $row;
        }

        if (isset($_GET['query']) && $_GET['query'] != null) {

            $search_query = $_GET['query'];
            $filtered_data = array_filter($data, function ($item) use ($search_query) {
                foreach ($item as $value) {
                    if (stripos($value, $search_query) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        // $record = "SELECT weekNo FROM minmeet ORDER BY weekNo DESC";
        // $result = $conn->query($record);
        // if ($row = $result->fetch_assoc()) {
        //     $highmin = $row['weekNo'];
        // }

        if ($data != null) {
            echo " 
        <form class=\"search-container\" method=\"GET\" action=\"viewAll.php\">
            <input type=\"text\" class=\"box\" name=\"query\" placeholder=\"Search...\">
            <button type=\"submit\" class=\"box\" style=\"border-radius:20px;\">Search</button>
        </form>
        <br>
        <div class=\"table-container\"><table class=\"table table-bordered table-light\">
            <thead class=\" thead-dark\">";
            getHead();

            echo "<form id=\"grpid\" method=\"post\" action=\"viewAll.php\">";
            if (isset($_GET['query']) && $_GET['query'] != null) {  //search filter func
                foreach ($filtered_data as $row) {
                    $finame = '';
                    $syno = [];
                    $grpid = $row['grpid'];
                    $grpno = $row['grpnum'];
                    $title = $row['title'];
                    $synopsis = $row['synopsis'];
                    $theme = $row['theme'];
                    $svname = $row['svname'];
                    echo "<tr>";
                    echo "<td>$grpno</td>";
                    echo "<td>$title</td>";

                    $record = "SELECT * FROM idpsynopsis WHERE grpid = $grpid";
                    $result = $conn->query($record);
                    while ($row = $result->fetch_assoc()) {
                        $syno[] = $row;
                    }
                    if ($syno != null) {
                        echo "<td><button type=\"submit\" name=\"viewsynopsis\" class=\"btn btn-primary bi bi-eye\" id=\"grpid\"  value=\" ";
                        echo $grpid;
                        echo "\">";
                        echo "</button></td>";
                    } else {
                        echo "<td>N/A</td>";
                    }

                    $sql = "SELECT COUNT(*) AS nummm FROM minmeet INNER JOIN idpgroup ON minmeet.grpid = idpgroup.grpid WHERE idpgroup.grpid = '$grpid' ";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $stmt->bind_result($totalmm);
                    $stmt->fetch();
                    $stmt->close();
                    if ($totalmm>$highmin){
                        $highmin = $totalmm;
                    }
                    if ($totalmm < $highmin) {
                        $classmin = "btn btn-danger";
                    } else {
                        $classmin = "btn btn-success";
                    }
                    echo "<td><button type=\"submit\" name=\"viewmm\" class=\"$classmin\" id=\"grpid\"  value=\" ";
                    echo $grpid;
                    echo "\">";
                    echo $totalmm;
                    echo "</button></td>";
                    $context = "proposal";
                    $record = "SELECT * FROM idpfile WHERE type = '$context' AND grpid = $grpid";
                    $result = $conn->query($record);
                    while ($row = $result->fetch_assoc()) {
                        $finame = $row['filename'];
                    }
                    if ($finame != null) {
                        $file_path = "../fileuploaded/" . $context . "/" . $grpid . $context . $finame;
                        echo "<td><a href=\"";
                        echo $file_path;
                        echo " \" class=\"btn btn-sm btn-custom-action\" style=\"background-color: #2FAB74;\" 
                    target=\"_blank\"><i class=\"fa-solid fa-file-arrow-down pe-1\"></i><span class=\"custom-display\">View</span></a></td>";
                    } else {
                        echo "<td>N/A</td>";
                    }
                    $finame = '';
                    $context = "finalreport";
                    $record = "SELECT * FROM idpfile WHERE type = '$context' AND grpid = $grpid";
                    $result = $conn->query($record);
                    while ($row = $result->fetch_assoc()) {
                        $finame = $row['filename'];
                    }
                    if ($finame != null) {
                        $file_path = "../fileuploaded/" . $context . "/" . $grpid . $context . $finame;
                        echo "<td><a href=\"";
                        echo $file_path;
                        echo " \" class=\"btn btn-sm btn-custom-action\" style=\"background-color: #2FAB74;\" 
                    target=\"_blank\"><i class=\"fa-solid fa-file-arrow-down pe-1\"></i><span class=\"custom-display\">View</span></a></td>";
                    } else {
                        echo "<td>N/A</td>";
                    }
                    $finame = '';
                    $context = "vidposter";
                    $record = "SELECT * FROM idpfile WHERE type = '$context' AND grpid = $grpid";
                    $result = $conn->query($record);
                    while ($row = $result->fetch_assoc()) {
                        $finame = $row['filename'];
                    }
                    if ($finame != null) {
                        $file_path = "../fileuploaded/" . $context . "/" . $grpid . $context . $finame;
                        echo "<td><a href=\"";
                        echo $file_path;
                        echo " \" class=\"btn btn-sm btn-custom-action\" style=\"background-color: #2FAB74;\" 
                    target=\"_blank\"><i class=\"fa-solid fa-file-arrow-down pe-1\"></i><span class=\"custom-display\">View</span></a></td>";
                    } else {
                        echo "<td>N/A</td>";
                    }
                    if ($finame != null) {
                        $file_path = "../fileuploaded/" . $context . "/" . $grpid . $context . $finame;
                        echo "<td><a href=\"";
                        echo $file_path;
                        echo " \" class=\"btn btn-sm btn-custom-action\" style=\"background-color: #2FAB74;\" 
                    target=\"_blank\"><i class=\"fa-solid fa-file-arrow-down pe-1\"></i><span class=\"custom-display\">View</span></a></td>";
                    } else {
                        echo "<td>N/A</td>";
                    }
                    echo "<td>";
                    $sql = "SELECT COUNT(*) AS numstud FROM student INNER JOIN idpgroup ON student.grpid = idpgroup.grpid WHERE student.grpid = '$grpid' ";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $stmt->bind_result($totalstu);
                    $stmt->fetch();
                    $stmt->close();
                    echo "<button type=\"submit\" name=\"grpid\" class=\"btn btn-primary\" id=\"grpid\"  value=\" ";
                    echo $grpid;
                    echo "\">";
                    echo $totalstu;
                    echo "</button></td></tr>";
                }
            } else {   //print func
                foreach ($data as $row) {
                    $finame = '';
                    $syno = [];
                    $grpid = $row['grpid'];
                    $grpno = $row['grpnum'];
                    $title = $row['title'];
                    $synopsis = $row['synopsis'];
                    $theme = $row['theme'];
                    $svname = $row['svname'];
                    echo "<tr>";
                    echo "<td>$grpno</td>";
                    echo "<td>$title</td>";

                    $record = "SELECT * FROM idpsynopsis WHERE grpid = $grpid";
                    $result = $conn->query($record);
                    while ($row = $result->fetch_assoc()) {
                        $syno[] = $row;
                    }
                    if ($syno != null) {
                        echo "<td><button type=\"submit\" name=\"viewsynopsis\" class=\"btn btn-primary bi bi-eye\" id=\"grpid\"  value=\" ";
                        echo $grpid;
                        echo "\">";
                        echo "</button></td>";
                    } else {
                        echo "<td>N/A</td>";
                    }

                    $sql = "SELECT COUNT(*) AS nummm FROM minmeet INNER JOIN idpgroup ON minmeet.grpid = idpgroup.grpid WHERE idpgroup.grpid = '$grpid' ";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $stmt->bind_result($totalmm);
                    $stmt->fetch();
                    $stmt->close();
                    if ($totalmm>$highmin){
                        $highmin = $totalmm;
                    }
                    if ($totalmm < $highmin) {
                        $classmin = "btn btn-danger";
                    } else {
                        $classmin = "btn btn-success";
                    }
                    echo "<td><button type=\"submit\" name=\"viewmm\" class=\"$classmin\" id=\"grpid\"  value=\" ";
                    echo $grpid;
                    echo "\">";
                    echo $totalmm;
                    echo "</button></td>";
                    $context = "proposal";
                    $record = "SELECT * FROM idpfile WHERE type = '$context' AND grpid = $grpid";
                    $result = $conn->query($record);
                    while ($row = $result->fetch_assoc()) {
                        $finame = $row['filename'];
                    }
                    if ($finame != null) {
                        $file_path = "../fileuploaded/" . $context . "/" . $grpid . $context . $finame;
                        echo "<td><a href=\"";
                        echo $file_path;
                        echo " \" class=\"btn btn-sm btn-custom-action\" style=\"background-color: #2FAB74;\" 
                    target=\"_blank\"><i class=\"fa-solid fa-file-arrow-down pe-1\"></i><span class=\"custom-display\">View</span></a></td>";
                    } else {
                        echo "<td>N/A</td>";
                    }
                    $finame = '';
                    $context = "finalreport";
                    $record = "SELECT * FROM idpfile WHERE type = '$context' AND grpid = $grpid";
                    $result = $conn->query($record);
                    while ($row = $result->fetch_assoc()) {
                        $finame = $row['filename'];
                    }
                    if ($finame != null) {
                        $file_path = "../fileuploaded/" . $context . "/" . $grpid . $context . $finame;
                        echo "<td><a href=\"";
                        echo $file_path;
                        echo " \" class=\"btn btn-sm btn-custom-action\" style=\"background-color: #2FAB74;\" 
                    target=\"_blank\"><i class=\"fa-solid fa-file-arrow-down pe-1\"></i><span class=\"custom-display\">View</span></a></td>";
                    } else {
                        echo "<td>N/A</td>";
                    }
                    $finame = '';
                    $context = "vidposter";
                    $record = "SELECT * FROM idpfile WHERE type = '$context' AND grpid = $grpid";
                    $result = $conn->query($record);
                    while ($row = $result->fetch_assoc()) {
                        $finame = $row['filename'];
                    }
                    if ($finame != null) {
                        $file_path = "../fileuploaded/" . $context . "/" . $grpid . $context . $finame;
                        echo "<td><a href=\"";
                        echo $file_path;
                        echo " \" class=\"btn btn-sm btn-custom-action\" style=\"background-color: #2FAB74;\" 
                    target=\"_blank\"><i class=\"fa-solid fa-file-arrow-down pe-1\"></i><span class=\"custom-display\">View</span></a></td>";
                    } else {
                        echo "<td>N/A</td>";
                    }
                    $vidlink = '';
                    $record = "SELECT videolink FROM videolink WHERE grpid = $grpid";
                    $result = $conn->query($record);
                    while ($row = $result->fetch_assoc()) {
                        $vidlink = $row['videolink'];
                    }
                    if ($vidlink != null) {
                        echo "<td><a href=\"";
                        echo $vidlink;
                        echo " \" class=\"btn btn-sm btn-custom-action\" style=\"background-color: #FF0000;\" 
                    target=\"_blank\"><i class=\"bi bi-youtube pe-1\"></i><span class=\"custom-display\">Video</span></a></td>";
                    } else {
                        echo "<td>N/A</td>";
                    }
                    echo "<td>";
                    $sql = "SELECT COUNT(*) AS numstud FROM student INNER JOIN idpgroup ON student.grpid = idpgroup.grpid WHERE student.grpid = '$grpid' ";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $stmt->bind_result($totalstu);
                    $stmt->fetch();
                    $stmt->close();
                    echo "<button type=\"submit\" name=\"grpid\" class=\"btn btn-primary\" id=\"grpid\"  value=\" ";
                    echo $grpid;
                    echo "\">";
                    echo $totalstu;
                    echo "</button></td></tr>";
                }
            }
            echo "</form>";
        }

    }
    ?>
    </table>

    <?php
    function getHead()
    {
        echo "<tr>";
        echo "<th>Group No</th>";
        echo "<th>Title</th>";
        echo "<th>Synopsis</th>";
        echo "<th>Minute Meeting</th>";
        echo "<th>Proposal</th>";
        echo "<th>Final Report</th>";
        echo "<th>Poster</th>";
        echo "<th>Video Link</th>";
        echo "<th>Students</th>";
        echo "</tr>";
    }
    function getHeadStudent()
    {
        echo "<tr>";
        echo "<th>No</th>";
        echo "<th>Student Name</th>";
        echo "<th>Matric Number</th>";
        echo "<th>NRIC Number</th>";
        echo "<th>CGPA</th>";
        echo "<th>Program</th>";
        echo "<th>Phone Number</th>";
        echo "<th>Email</th>";
        echo "</tr>";
    }

    ?>
    </div>
    </div>
</body>

</html>