<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include ('../config/db_connect.php');
include './logincheckComm.php';
include './sidebarComm.php';
?>

<html>

<body>
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

        .btn2 {
            margin: 0 auto;
            width: 20%;
            margin-top: -10px;
        }

        h2 {
            margin-left: 15%;
            font-weight: bold;
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
        <h1 class="text-center mb-3 title-container">List of Grouping</h1>


        <?php
        $data = [];
        if (isset($_POST['grpid'])) {
            echo "<br><button type=\"submit\" class=\"btn btn2 btn-primary\" onclick=\"window.location.href='./listGroup.php'\">Back to List</button>";
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
        <form class=\"search-container text-center\" method=\"GET\" action=\"listGroup.php\">
            <input type=\"text\" class=\"box\" name=\"query\" placeholder=\"Search...\">
            <button type=\"submit\" class=\"box\">Search</button>
        </form>
        <br>
        <div class=\"table-container\">
        <table class=\"table table-bordered table-light\">
            <thead class=\" thead-dark\">";
                getHead();

                echo "<form id=\"grpid\" method=\"post\" action=\"listGroup.php\">";
                if (isset($_GET['query']) && $_GET['query'] != null) {
                    foreach ($filtered_data as $row) {
                        $grpid = $row['grpid'];
                        $sql = "SELECT COUNT(*) AS numstud FROM student INNER JOIN idpgroup ON student.grpid = idpgroup.grpid WHERE student.grpid = '$grpid' ";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $stmt->bind_result($totalstu);
                        $stmt->fetch();
                        $stmt->close();
                        $grpid = $row['grpid'];
                        $grpno = $row['grpnum'];
                        $title = $row['title'];
                        $theme = $row['theme'];
                        $svname = $row['svname'];
                        echo "<tr>";
                        echo "<td>$grpno</td>";
                        echo "<td>$title</td>";
                        echo "<td>$theme</td>";
                        echo "<td>$svname</td><td>";
                        echo "<button type=\"submit\" name=\"grpid\" class=\"btn btn-primary\" id=\"grpid\"  value=\" ";
                        echo $grpid;
                        echo "\">";
                        echo $totalstu;
                        echo "</button></td></tr>";
                    }
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
                        echo "<td>$theme</td>";
                        echo "<td>$svname</td><td>";
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
            echo "<th>Theme</th>";
            echo "<th>Supervisor</th>";
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

    </body>

</html>