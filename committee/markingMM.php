<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include ('../config/db_connect.php');
include './logincheckComm.php';
include './sidebarComm.php';

if (isset($_POST['idGet'])) {
    $grpidmark = $_POST['idGet'];
    $marks = $_POST['mmmark'];
    $sql_check = "SELECT 1 FROM grpmark WHERE grpid = $grpidmark";
    $result = $conn->query($sql_check);
    if($marks!=null){
        if ($result->num_rows > 0) {
            $sql = "UPDATE grpmark
            SET mmMarkComm = '$marks' WHERE grpid = $grpidmark";
            $conn->query($sql);
        } else {
            $sql = "INSERT INTO grpmark(grpid,mmMarkComm) VALUES ($grpidmark,$marks)";
            $conn->query($sql);
        }
    }   
}
?>

<html>

<style>
    .box {

        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 20px 20px 20px 20px;
        text-align: center;
    }

    .table th {
        background-color: #f2f2f2;
    }

    .table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        /* For smooth scrolling on iOS */
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

    td,
    th {
        text-align: center;
        /* Horizontally center text */
        vertical-align: top;
        /* Vertically center text */
        height: 50px;
        /* Set a height for proper vertical centering */
        border: 1px solid black;
    }

    .btn2 {
        margin: 0 auto;
        width: 20%;
        margin-top: -10px;
        border: 2px groove black;
        border-radius: 50px;
    }

    h2 {
        margin-left: 15%;
        font-weight: bold;
    }

    .details-container {
        margin-bottom: 10px;
        margin-left: -170px;
    }

    @media (max-width: 768px) {
        .details-container {
            margin-left: 10px;
        }

        .title-container {
            border-bottom: 3px solid black;
            width: fit-content;
        }

        h2 {
            margin-left: 10px;
            font-size: 1.2rem;
        }

        .btn2 {
            width: fit-content;
            margin: 10px auto;
        }

        .table {
            width: 100%;
        }

        .search-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .search-container input,
        .search-container button {
            width: 80%;
            margin-bottom: 10px;
        }
    }
</style>

<body>
    <h1 class="text-center mb-3 title-container">Marking Minute Meeting</h1>
    <h4 class="text-center mb-3">*Each Committee can give 0 to 5 Contingency mark to each group</h4>

    <?php
    $data = [];
    if (isset($_POST['grpid'])) {
        echo "<br><button type=\"submit\" class=\"btn btn2 btn-primary\" onclick=\"window.location.href='./markingMM.php'\">Back to List</button>";
        echo "<div class=\"details-container\">";

        $grpid = $_POST['grpid'];
        $sql = "SELECT idpgroup.grpnum, supervisor.name AS svname, theme.title AS theme, idpsynopsis.title FROM idpgroup 
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
        echo "</div>";

        echo "<div class=\"table-container\">";
        echo "<table class=\"table table-bordered table-light\">
            <thead class=\" thead-dark\">";
        getHeadStudent();
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
        echo "</table>";
        echo "</div>";
    } elseif (isset($_POST['mmweek'])) { //view min meetings
        echo "<br><button type=\"submit\" class=\"btn btn2 btn-primary\" onclick=\"window.location.href='./markingMM.php'\">Back to List";
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
        $input = $_POST['mmweek'];
        // Use preg_split to split the string by non-digit characters
        $numbers = preg_split('/\D+/', $input, -1, PREG_SPLIT_NO_EMPTY);
        $weekid = $numbers[0];
        $grpid = $numbers[1];
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
        echo "<h2>WeekNo &nbsp: &nbsp;&nbsp;$weekid </h2>";
        echo "<h2>Group &nbsp&nbsp&nbsp;&nbsp&nbsp;: &nbsp;&nbsp;$grpnoz </h2>";
        echo "<h2>Title  &nbsp&nbsp&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;$titlez </h2>";
        $data = [];
        $record = "SELECT * FROM `minmeet` WHERE grpid = '$grpid' AND weekNo = $weekid ORDER BY weekNo ASC";
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
        $status = $data[0]['approved'];
        echo "<h2>Status  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;";
        if($status==1){
            echo "Approved";
        } else{
            echo "In Review";
        }
        echo "</h2></div>";
        echo "<br>";
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
        $record = "SELECT idpgroup.*, supervisor.name AS svname, theme.title AS theme, idpsynopsis.title FROM idpgroup 
            INNER JOIN supervisor ON idpgroup.grpid = supervisor.grpid INNER JOIN theme ON idpgroup.themeid = theme.themeid LEFT JOIN idpsynopsis ON idpgroup.grpid = idpsynopsis.grpid 
            WHERE idpgroup.active = 1 AND idpgroup.themeid is NOT NULL ORDER BY grpnum ASC";
        $result = $conn->query($record);
        while ($row = $result->fetch_assoc()) {
            if ($row['title'] == null) {
                $row['title'] = "No Title Submmited";
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

        if ($data != null) {
            echo " 
            <form class=\"search-container text-center\" method=\"GET\" action=\"markingMM.php\">
            <input type=\"text\" class=\"box\" name=\"query\" placeholder=\"Search...\">
            <button type=\"submit\" class=\"box\">Search</button>
            </form>
            <br>
            <div class=\"table-container\">
            <table class=\"table table-bordered table-light\">
            <thead class=\" thead-dark\">";
            getHead();

            echo "<form id=\"grpid\" method=\"post\" action=\"markingMM.php\">";
            if (isset($_GET['query']) && $_GET['query'] != null) {
                $data = $filtered_data;
            }
            foreach ($data as $row) {
                echo "<form id=\"grpid\" method=\"post\" action=\"markingMM.php\">";
                $grpid = $row['grpid'];
                $grpno = $row['grpnum'];
                $title = $row['title'];
                $theme = $row['theme'];
                $svname = $row['svname'];
                echo "<tr>";
                echo "<td>$grpno</td>";
                echo "<td>$title</td>";
                echo "<td>$svname</td><td>";
                $mmdata = [];
                $record = "SELECT * FROM minmeet WHERE grpid = '$grpid' ORDER BY weekNo ASC";
                $result = $conn->query($record);
                while ($row = $result->fetch_assoc()) {
                    $mmdata[] = $row;
                }
                if ($mmdata != NULL) {
                    foreach ($mmdata as $data) {
                        $weeks = $data['weekNo'];
                        $aprv = $data['approved'];
                        if($aprv==1){
                            echo "<button type=\"submit\" name=\"mmweek\" class=\"btn btn2 btn-success m-1\" value=\"$weeks";
                            echo "G$grpid\">$weeks</button>";
                        } else{
                            echo "<button type=\"submit\" name=\"mmweek\" class=\"btn btn2 btn-warning m-1\" value=\"$weeks";
                            echo "G$grpid\">$weeks</button>";
                        }
                        
                        
                    }
                }
                $sql = "SELECT COUNT(*) AS numstud FROM student INNER JOIN idpgroup ON student.grpid = idpgroup.grpid WHERE student.grpid = '$grpid' ";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $stmt->bind_result($totalstu);
                $stmt->fetch();
                $stmt->close();
                echo "</td><td><button type=\"submit\" name=\"grpid\" class=\"btn btn-primary\" id=\"grpid\"  value=\" ";
                echo $grpid;
                echo "\">";
                echo $totalstu;
                echo "</button></td>";
                $record = "SELECT mmMarkComm FROM grpmark WHERE grpid = '$grpid'";
                $result = $conn->query($record);
                if ($row = $result->fetch_assoc()) {
                    $mark = $row['mmMarkComm'];
                } else {
                    $mark = "-";
                }
                //<input type=\"number\" id=\"number-input\" name=\"number\" min=\"0\" max=\"5\" step=\"1\" value=\"0\">
                echo "<td><input type=\"number\" id=\"mmmark\" name=\"mmmark\" min=\"0\" max=\"5\" step=\"1\" value=\"$mark\"</td>";
                echo "<td><button type=\"submit\" name=\"idGet\" class=\"btn btn-success\" value=\"";
                echo $grpid;
                echo "\"";
                echo "><i class=\"bi bi-clipboard-plus\"></i></button></td></tr></form>";
            }
            echo "</form>";
            echo "</table>";
            echo "</div>";
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
        echo "<th>Supervisor</th>";
        echo "<th>Minute Meeting</th>";
        echo "<th>Students</th>";
        echo "<th>Score 5%</th>";
        echo "<th>Submit</th>";
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

</body>

</html>