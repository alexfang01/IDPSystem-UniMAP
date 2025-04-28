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

        .btn2 {
            margin: 0 auto;
            width: 20%;
        }

        h2 {
            margin-left: 15%;
            font-weight: bold;
        }

        select{
            width: 100%;
        }
        @media (max-width: 768px) {
            .title-container {
                border-bottom: 3px solid black;
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

            .table-container {
                padding: 0 1rem;
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
    <h1 class="text-center" style="margin-bottom:-10px;">Assign</h1>
        <h1 class="text-center mb-3 title-container">Internal Examiner</h1>

        <div class="table-container">
        <?php
        if (isset($_POST['idGet'])) {
            $evaGrp = $_POST['idGet']; //grpid
            $svtarg = $_POST['svget']; //updatesv
        
            if ($svtarg != NULL) {
                $sql = "UPDATE supervisor
            SET evalid = '$evaGrp' WHERE svid = $svtarg";
                if ($conn->query($sql) === TRUE) {
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            } else {
                $record = "SELECT * FROM supervisor WHERE evalid = '$evaGrp' ";
                $result = $conn->query($record);
                if ($row = $result->fetch_assoc()) {
                    $flag = 1;
                    $svidz = $row['svid'];
                } else {
                    $flag = 0;
                }
                if ($flag == 1) {
                    $sql = "UPDATE supervisor
                    SET evalid = null WHERE svid = $svidz";
                    if ($conn->query($sql) === TRUE) {
                    } else {
                        echo "Error updating record: " . $conn->error;
                    }
                }

            }


        }

        $data = [];
        $record = "SELECT idpgroup.*, supervisor.name AS svname, supervisor.svid AS svid, theme.title AS theme, idpsynopsis.title FROM idpgroup 
            INNER JOIN supervisor ON idpgroup.grpid = supervisor.grpid INNER JOIN theme ON idpgroup.themeid = theme.themeid LEFT JOIN idpsynopsis ON idpgroup.grpid = idpsynopsis.grpid 
            WHERE idpgroup.active = 1 AND idpgroup.themeid is NOT NULL ORDER BY grpnum ASC";
        $result = $conn->query($record);
        while ($row = $result->fetch_assoc()) {
            if ($row['title'] == null) {
                $row['title'] = "No Title Submmited";
            }
            $data[] = $row;
        }

        if ($data != null) {
            echo " <table class=\"table table-bordered table-light\">
            <thead class=\" thead-dark\">";
            getHead();


            foreach ($data as $row) {
                $svsel = [];
                $evalid = [];
                $grpid = $row['grpid'];
                $grpno = $row['grpnum'];
                $title = $row['title'];
                $theme = $row['theme'];
                $svname = $row['svname'];

                $record = "SELECT * FROM student INNER JOIN idpgroup ON student.grpid = idpgroup.grpid WHERE student.leader = '1' AND student.grpid = '$grpid' ";
                $result = $conn->query($record);
                if ($row = $result->fetch_assoc()) {
                    $leader = $row['name'];
                } else {
                    $leader = "No Leader Elected";
                }
                $record = "SELECT * FROM supervisor WHERE evalid = '$grpid' ";
                $result = $conn->query($record);
                if ($row = $result->fetch_assoc()) {
                    $evalid = $row;
                    $svid = $evalid['svid'];
                }

                $record = "SELECT * FROM supervisor WHERE grpid != '$grpid' AND evalid is NULL";
                $result = $conn->query($record);
                while ($row = $result->fetch_assoc()) {
                    $svsel[] = $row;
                }

                echo "<tr>";
                echo "<td>$grpno</td>";
                echo "<td>$title</td>";
                echo "<td>$svname</td>";
                echo "<td>$leader</td><td>";

                echo "<form id=\"grpid\" method=\"post\" action=\"interExaminer.php\">";
                echo "<select name=\"svget\">";

                if ($evalid != null) {
                    echo "<option value=";
                    echo $evalid['svid'];
                    echo ">";
                    echo $evalid['name'];
                }
                echo "<option value=\"\">-</option>";
                foreach ($svsel as $b):
                    echo "<option value=\" ";
                    echo $b['svid'];
                    echo "\">";
                    echo $b['name'];
                    echo "</option>";
                endforeach;
                echo "</select>";
                echo "</td><td>";
                echo "<button type=\"submit\" name=\"idGet\" class=\"btn btn-success\" value=\"";
                echo $grpid;
                echo "\"";
                echo "><i class=\"bi bi-person-workspace\"></i></button>";
                echo "</td></tr>";
                echo "</form>";
            }


        }


        ?>
        </table>
        </div>

        <?php
        function getHead()
        {
            echo "<tr>";
            echo "<th>Group No</th>";
            echo "<th>Title</th>";
            echo "<th>Supervisor</th>";
            echo "<th>Student Leader</th>";
            echo "<th>Internal Examiner</th>";
            echo "<th>Assign</th>";
            echo "</tr>";
        }

        ?>

        </div>
        </div>
    </body>

</html>