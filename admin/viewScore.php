<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include ('../config/db_connect.php');
include './logincheckAdmin.php';
include './sidebarAdmin.php';
?>

<html>

<style>
    .box {

        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 20px 20px 20px 20px;
        text-align: center;
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
        vertical-align: middle;
        /* Vertically center text */
        height: 50px;
        /* Set a height for proper vertical centering */
        border: 1px solid black;
    }

    .btn2 {
        margin: 0 auto;
        width: 20%;
        margin-top: -10px;
    }

    h2 {
        margin-left: 15%;
        font-weight: bold;
    }
    h3 {
        font-weight: bold;
        font-size: 18px;
        margin: 0;
    }
    h4 {
        font-size: 15px;
        margin: 0;
    }

    .details-container {
        margin-bottom: 10px;
        margin-left: -170px;
    }

    .table th {
        background-color: #f2f2f2;
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
    <h1 class="text-center mb-3 title-container">Review Final Score</h1>
    <h4 class="text-center mb-3">*The remaining 15% mark depend on each student's peer review score</h4>
    <?php
    $data = [];
    $record = "SELECT idpgroup.*, supervisor.name AS svname, theme.title AS theme, idpsynopsis.title FROM idpgroup 
            INNER JOIN supervisor ON idpgroup.grpid = supervisor.grpid INNER JOIN theme ON idpgroup.themeid = theme.themeid LEFT JOIN idpsynopsis ON idpgroup.grpid = idpsynopsis.grpid 
            WHERE idpgroup.active = 1 AND idpgroup.themeid is NOT NULL ORDER BY grpnum ASC";
    $result = $conn->query($record);
    while ($row = $result->fetch_assoc()) {
        if ($row['title'] == null) {
            $row['title'] = "N/A";
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
    if (isset($_POST['grpid'])) {
        echo "<br><button type=\"submit\" class=\"btn btn2 btn-primary\" onclick=\"window.location.href='./viewScore.php'\">Back</button>";
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
        $record = "SELECT * FROM `student` WHERE grpid = '$grpid' ORDER BY leader DESC";
        $result = $conn->query($record);
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $i = 1;
        foreach ($data as $row) {
            $idstu = $row['studentid'];
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
            $record = "SELECT * FROM peermark WHERE studentid = '$idstu'";
            $result = $conn->query($record);
            if ($row = $result->fetch_assoc()) {
                if ($row['peersvMark'] == null) {
                    $peersv = "0";
                } else {
                    $peersv = $row['peersvMark'];
                }
                if ($row['peerstuMark'] == null) {
                    $peerstu = "0";
                } else {
                    $peerstu = $row['peerstuMark'];
                }
            } else {
                $peersv = "0";
                $peerstu = "0";
            }
            echo "<td>$peerstu</td>";
            echo "<td>$peersv</td><td>";
            $dataz = [];
            $record = "SELECT * FROM `peerstudent` WHERE member = $idstu";
            $result = $conn->query($record);
            while ($row = $result->fetch_assoc()) {
                $dataz[] = $row;
            }

            foreach ($dataz as $row) {
                if ($row['comment'] != null) {
                    $grader = [];
                    $c1 = $row['comment'];
                    $giver = $row['grader'];
                    $record2 = "SELECT * FROM `student` WHERE studentid = $giver";
                    $result2 = $conn->query($record2);
                    if ($row2 = $result2->fetch_assoc()) {
                        $grader = $row2['name'];
                    }
                    echo "<h3>\"$c1\"</h3>";
                    echo "<h4>-$grader</h4><br>";
                }
            }
            $record = "SELECT * FROM `peersv` WHERE studentid = $idstu";
            $result = $conn->query($record);
            if ($row = $result->fetch_assoc()) {
                $svcomm = $row['comment'];
                echo "<h3>\"$svcomm\"</h3>";
                echo "<h4>-$svnamez</h4>";
            }

            $record = "SELECT * FROM grpmark WHERE grpid = '$grpid'";
            $result = $conn->query($record);
            if ($row = $result->fetch_assoc()) {
                if ($row['reportMarkSV'] == null) {
                    $repSV = "0";
                } else {
                    $repSV = $row['reportMarkSV'];
                }
                if ($row['reportMarkExaminer'] == null) {
                    $repEX = "0";
                } else {
                    $repEX = $row['reportMarkExaminer'];
                }
                if ($row['idpexMarkSV'] == null) {
                    $exSV = "0";
                } else {
                    $exSV = $row['idpexMarkSV'];
                }
                if ($row['idpexMarkPanel'] == null) {
                    $exPanel = "0";
                } else {
                    $exPanel = $row['idpexMarkPanel'];
                }
                if ($row['proposalMark'] == null) {
                    $propo = "0";
                } else {
                    $propo = $row['proposalMark'];
                }
                if ($row['mmMarkComm'] == null) {
                    $mm = "0";
                } else {
                    $mm = $row['mmMarkComm'];
                }
            } else {
                $repSV = "0";
                $repEX = "0";
                $exSV = "0";
                $exPanel = "0";
                $propo = "0";
                $mm = "0";
            }
            $grpscore = $repSV + $repEX + $exSV + $exPanel + $propo + $mm;
            echo "</td><td>$grpscore</td>";
            $final = $grpscore + $peerstu + $peersv;
            echo "<td class=\"fw-bold\">$final</td>";
            echo "</tr>";
            $i++;
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo " 
        <form class=\"search-container text-center\" method=\"GET\" action=\"viewScore.php\">
            <input type=\"text\" class=\"box\" name=\"query\" placeholder=\"Search...\">
            <button type=\"submit\" class=\"box\">Search</button>
        </form>
        <br>
        <div class=\"table-container\">
        <table class=\"table table-bordered table-light\">
            <thead class=\" thead-dark\">";
        getHead();

        echo "<form id=\"grpid\" method=\"post\" action=\"viewScore.php\">";
        if (isset($_GET['query']) && $_GET['query'] != null) {
            $data = $filtered_data;
        } else {
            foreach ($data as $row) {
                $grpid = $row['grpid'];
                $grpno = $row['grpnum'];
                $title = $row['title'];
                $theme = $row['theme'];
                $svname = $row['svname'];
                echo "<tr>";
                echo "<td>$grpno</td>";
                echo "<td>$title</td>";
                echo "<td>$svname</td>";
                $record = "SELECT * FROM grpmark WHERE grpid = '$grpid'";
                $result = $conn->query($record);
                if ($row = $result->fetch_assoc()) {
                    if ($row['reportMarkSV'] == null) {
                        $repSV = "0";
                    } else {
                        $repSV = $row['reportMarkSV'];
                    }
                    if ($row['reportMarkExaminer'] == null) {
                        $repEX = "0";
                    } else {
                        $repEX = $row['reportMarkExaminer'];
                    }
                    if ($row['idpexMarkSV'] == null) {
                        $exSV = "0";
                    } else {
                        $exSV = $row['idpexMarkSV'];
                    }
                    if ($row['idpexMarkPanel'] == null) {
                        $exPanel = "0";
                    } else {
                        $exPanel = $row['idpexMarkPanel'];
                    }
                    if ($row['proposalMark'] == null) {
                        $propo = "0";
                    } else {
                        $propo = $row['proposalMark'];
                    }
                    if ($row['mmMarkComm'] == null) {
                        $mm = "0";
                    } else {
                        $mm = $row['mmMarkComm'];
                    }
                } else {
                    $repSV = "0";
                    $repEX = "0";
                    $exSV = "0";
                    $exPanel = "0";
                    $propo = "0";
                    $mm = "0";
                }
                $final = $repSV + $repEX + $exSV + $exPanel + $propo + $mm;
                echo "<td>$propo</td>";
                echo "<td>$mm</td>";
                echo "<td>$repSV</td>";
                echo "<td>$repEX</td>";
                echo "<td>$exSV</td>";
                echo "<td>$exPanel</td>";
                echo "<td class=\"fw-bold\">$final</td><td>";
                $sql = "SELECT COUNT(*) AS numstud FROM student INNER JOIN idpgroup ON student.grpid = idpgroup.grpid WHERE student.grpid = '$grpid' ";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $stmt->bind_result($totalstu);
                $stmt->fetch();
                $stmt->close();
                echo "<button type=\"submit\" name=\"grpid\" class=\"btn btn-primary btn-lg bi bi-person-circle\" id=\"grpid\"  value=\" ";
                echo $grpid;
                echo "\"> ";
                echo $totalstu;
                echo "</button></td></tr>";
            }
        }
        echo "</form>";
        echo "</table>";
        echo "</div>";
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
        echo "<th>Proposal 10%</th>";
        echo "<th>Minute Meeting 5%</th>";
        echo "<th>Report (SV) 20%</th>";
        echo "<th>Report (Examiner) 20%</th>";
        echo "<th>Exhibition (SV) 15%</th>";
        echo "<th>Exhibition (Panel) 15%</th>";
        echo "<th>Group Mark /85%</th>";
        echo "<th>Individual Mark /100%</th>";
        echo "</tr>";
    }
    function getHeadStudent()
    {
        echo "<tr>";
        echo "<th>No</th>";
        echo "<th>Student Name</th>";
        echo "<th>Matric Number</th>";
        echo "<th>NRIC Number</th>";
        echo "<th>PeerScore (Students)</th>";
        echo "<th>PeerScore (Supervisor)</th>";
        echo "<th>Comments</th>";
        echo "<th>GroupScore /%85</th>";
        echo "<th>FinalScore /%100</th>";
        echo "</tr>";
    }


    ?>

</body>

</html>