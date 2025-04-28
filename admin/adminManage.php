<!DOCTYPE html>
<html lang="en">
<?php
include ('../config/db_connect.php');
include './logincheckAdmin.php';
include './sidebarAdmin.php';

if (isset($_POST['userList'])) {
    $_SESSION['userview'] = $_POST['userList'];
    $_SESSION['edit'] = 0;
    $_SESSION['editid'] = 0;
}
if (isset($_SESSION['userview']) && $_SESSION['userview'] !== null) {
    $userview = $_SESSION['userview'];
} 
if(isset($_SESSION['cpass'])){
    $cpass = $_SESSION['cpass'];
} else{
    $cpass = 0;
}

if (!isset($_SESSION['currentpage']) || isset($_POST['list_all'])) {
    $_SESSION['currentpage'] = 1;
    $_SESSION['filtered'] = 0;
    unset($_POST['list_all']);
}
if (!isset($_SESSION['edit'])) {
    $_SESSION['edit'] = 0;
}
if (!isset($_SESSION['editid'])) {
    $_SESSION['editid'] = 0;
}
if (!isset($_SESSION['filtered']) || $_SESSION['filtered'] == '0') {
    if (isset($_POST['filter'])) {
        $_SESSION['filtered'] = 1;
    } else {
        $_SESSION['filtered'] = 0;
    }
}
if (isset($_POST['cancelcpass'])) {
    $_SESSION['cpass'] = 0;
}

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    integrity="sha512-fDnh8MPtZZ2ayE8D6f6fvMm8eEaMYPeMgpIH93nUuO8hjO7w/JjrGzrj9oH9W/zB84r+JUtYW+lpPF9LCJLqKQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">

<html>
<style>
    .button-container {
    display: flex;
    justify-content: center;
}
    .box {
        background-color: #F3F6F4;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 20px 20px 20px 20px;
        text-align: center;
        margin: 4px;
    }

    .btn2 {
        width:12%;
        text-align: center;
        margin: 0 auto;
    }

    .rounded-table-container {
        width: 98%;
        margin: 0 auto;
        overflow-x: auto;
        /* Enable horizontal scrolling */
    }

    .rounded-table {
        margin: 0 auto;
        border-collapse: collapse;
        border-radius: 20px;
        overflow: hidden;
        width: 98%;
    }

    .rounded-table th {
        background-color: #f2f2f2;
        border: 1px solid #dddddd;
        padding: 8px;
        text-align: center;
        white-space: nowrap;
    }

    /* Style the table cells */
    .rounded-table td {

        border: 1px solid #dddddd;
        padding: 8px;
        text-align: left;
        white-space: nowrap;
    }

    .title-container {
        border-bottom: 3px solid black;
        text-align: center;
        margin-bottom: 20px;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .form-container {
    background-color: #F3F6F4;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 20px;
    padding: 50px;
    width: 100%;
    max-width: 500px;
    text-align: center;
    margin-top: -200px;
}

    .form-container label {
        font-size: 20px;
        margin-bottom: 8px;
        display: block;
    }

    .form-container input[type="text"] {
        width: 80%;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 10px;
        border: 1px solid #ccc;
        display: block;
        margin: 0 auto;
    }

    .form-container button {
        padding: 3px;
        border-radius: 20px;
        background-color: #F3F6F4;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        border: 2px solid black;
        color: #000;
        font-size: 20px;
        display: block;
        margin: 5px auto;
        margin-bottom: -25px;
    }

    .form-container button:hover {
        background-color: #e2e2e2;
    }

    .back-button {
        background-color: #0D6EFD;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .title-container {
            border-bottom: 3px solid black;
            width: fit-content;
        } 
        .form-container {
            width: 90%;
            padding: 30px;
        }

        .form-container label {
            font-size: 16px;
        }

        .form-container input[type="text"] {
            font-size: 14px;
            width: 100%;
        }

        .form-container button {
            font-size: 14px;
        }

        .back-button {
            font-size: 14px;
            padding: 8px 16px;
        }
    }
</style>

<body>
    <!-- Tablezzz -->
    <?php
    if ($cpass == '1') {
        echo "<h1 class=\"text-center mb-3 title-container\">Reset User Password</h1>";
    } else {
        echo '
        <h1 class="text-center mb-3 title-container">Manage User Detail</h1>
        <div class="button-container" style="display: flex; justify-content: center; margin-top: 20px;">
            <button style="margin-top:-20px; margin-bottom:10px;" class="btn btn-primary btn-lg" type="button" onclick="window.location.href=\'./generateAdminAccount.php\'">
                Create New Admin
            </button>
        </div>
        ';
        echo "<form action=\"adminManage.php\" method=\"POST\">
        <p class=\"text-center mb-3\">Below show the list of
            <select name=\"userList\" class=\"box\">";
        if ($userview)
            echo "<option value=";
        echo $userview;
        echo ">";
        echo $userview;
        echo "</option>";

        if ($userview != "Student") {
            echo "<option value=\"Student\">Student</option>";
        }
        if ($userview != "Committee") {
            echo "<option value=\"Committee\">Committee</option>";
        }
        if ($userview != "Supervisor") {
            echo "<option value=\"Supervisor\">Supervisor</option>";
        }
        if ($userview != "Panel") {
            echo "<option value=\"Panel\">Panel</option>";
        }

        echo "</select>
        <form method=\"post\" action=\"adminManage.php\">
        <input type=\"submit\" name=\"list_all\" class=\"box\" value=\"List all\">
        <input type=\"submit\" name=\"filter\" class=\"box\" value=\"Pending Verify\">
        </form>
        
        
        </p>
        </form>
        <form class=\"text-center\" method=\"GET\" action=\"adminManage.php\">
        <input type=\"text\" class=\"box\" name=\"query\" placeholder=\"Search...\">
        <button type=\"submit\" class=\"box\">Search</button>
        </form>
        <div class=\"rounded-table-container\">
        <table class=\"table rounded-table\">
            <thead class=\" thead-dark\">";
    }
    ?>
    <br>
                <?php
                if ($cpass == '1') {
                    $cpassid = $_SESSION['cpassid'];
                    if ($userview == "Committee") {
                        $record = "SELECT * FROM committee WHERE cid = '$cpassid'";
                        $result = $conn->query($record);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $name = $row["name"];
                            $unum = $row["staffnum"];
                            $email = $row["email"];
                        }
                    } else if($userview == "Supervisor") {
                        $record = "SELECT * FROM supervisor WHERE svid = '$cpassid'";
                        $result = $conn->query($record);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $name = $row["name"];
                            $unum = $row["staffnum"];
                            $email = $row["email"];
                        }
                    } else if($userview == "Student") {
                        $record = "SELECT * FROM student WHERE studentid = '$cpassid'";
                        $result = $conn->query($record);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $name = $row["name"];
                            $unum = $row["matric"];
                            $email = $row["email"];
                        }

                    } else if($userview == "Panel") {
                        $record = "SELECT * FROM panel WHERE panelid = '$cpassid'";
                        $result = $conn->query($record);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $name = $row["name"];
                            $unum = $row["panelnum"];
                            $email = $row["email"];
                        }
                    }
                    echo "<div class=\"container\"><div class=\"form-container\">
                    <form id=\"userForm\" method=\"post\" action=\"adminManageFunc.php\">
                    <label for=\"id\">ID:</label>
                    <input type=\"text\" id=\"id\" name=\"idc\" value=\"$cpassid\" disabled>
                    <label for=\"name\">Name:</label>
                    <input type=\"text\" id=\"namec\" name=\"namec\" value=\"$name\" disabled>
                     <label for=\"name\">User Number:</label>
                    <input type=\"text\" id=\"unumc\" name=\"unumc\" value=\"$unum\" disabled>
                     <label for=\"name\">Email:</label>
                    <input type=\"text\" id=\"emailc\" name=\"emailc\" value=\"$email\" disabled>
                    <label for=\"password\">New Password:</label>";

                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen($characters);
                    $randPass = '';
                    for ($i = 0; $i < 8; $i++) {
                        $randPass .= $characters[rand(0, $charactersLength - 1)];
                    }
                    echo "<input type=\"text\" id=\"newpassc\" name=\"newpassc\" value=\"$randPass\" required>
                        <button type=\"button\" class=\"btn btn-primary\" id=\"copyButton\">Copy</button><br><br>
                        <button type=\"submit\" class=\"btn btn-primary\">Submit</button>
                        </form>
                        <form method=\"post\" action=\"./adminManageFunc.php\">
                        <button class=\"back-button\" name=\"cancelcpass\" type=\"submit\" style=\"margin-top:60px;\">Back</button>
                    </form>
                    </div></div>";

                } else {
                    if (!isset($userview)) {
                    }
                    // Check if Search pressed
                    else if (isset($_GET['query']) && $_GET['query'] != null) {

                        $data = $_SESSION['dataq'];
                        $search_query = $_GET['query'];
                        $filtered_data = array_filter($data, function ($item) use ($search_query) {
                            foreach ($item as $value) {
                                if (stripos($value, $search_query) !== false) {
                                    return true;
                                }
                            }
                            return false;
                        });

                        if ($userview == "Committee") {
                            getCommitteeHead();
                            printCommittee($filtered_data);
                        } else if ($userview == "Supervisor") {
                            getSupervisorHead();
                            printSupervisor($filtered_data);
                        } else if ($userview == "Student") {
                            getStudentHead();
                            printStudent($filtered_data);
                        } else if ($userview == "Panel") {
                            getPanelHead();
                            printPanel($filtered_data);
                        }

                    } else if ($userview == "Committee") {
                        //check list down Committee
                        getCommitteeHead();
                        if ($_SESSION['filtered'] == 1) {
                            $record = "SELECT cid,name,staffnum,email,verify FROM committee WHERE verify = '0'";
                            $result = $conn->query($record);
                            while ($row = $result->fetch_assoc()) {
                                $data[] = $row;
                            }
                        } else {
                            $record = "SELECT cid,name,staffnum,email,verify FROM committee";
                            $result = $conn->query($record);
                            while ($row = $result->fetch_assoc()) {
                                $data[] = $row;
                            }

                        }
                        //store data and page number
                        if (isset($data)) {
                            $paginated_data = saveUser($data);
                            $total_pages = $_SESSION['totalpage'];
                            printCommittee($paginated_data);

                        }


                    } else if ($userview == "Supervisor") {
                        getSupervisorHead();
                        if ($_SESSION['filtered'] == 1) {
                            $record = "SELECT svid,name,staffnum,email,verify FROM supervisor WHERE verify = '0'";
                            $result = $conn->query($record);
                            while ($row = $result->fetch_assoc()) {
                                $data[] = $row;
                            }
                        } else {
                            $record = "SELECT svid,name,staffnum,email,verify FROM supervisor";
                            $result = $conn->query($record);
                            while ($row = $result->fetch_assoc()) {
                                $data[] = $row;
                            }
                        }
                        //store data and page number
                        if (isset($data)) {
                            $paginated_data = saveUser($data);
                            $total_pages = $_SESSION['totalpage'];
                            printSupervisor($paginated_data);
                        }

                    } else if ($userview == "Student") {
                        getStudentHead();
                        if ($_SESSION['filtered'] == 1) {
                            $record = "SELECT studentid,name,ic,matric,prog,race,cgpa,phnum,email,verify FROM student WHERE verify = '0'";
                            $result = $conn->query($record);
                            while ($row = $result->fetch_assoc()) {
                                $data[] = $row;
                            }
                        } else {
                            $record = "SELECT studentid,name,ic,matric,prog,race,cgpa,phnum,email,verify FROM student";
                            $result = $conn->query($record);
                            while ($row = $result->fetch_assoc()) {
                                $data[] = $row;
                            }

                        }
                        //store data and page number
                        if (isset($data)) {
                            $paginated_data = saveUser($data);
                            $total_pages = $_SESSION['totalpage'];
                            printStudent($paginated_data);
                        }
                    } else if ($userview == "Panel") {
                        getPanelHead();
                        if ($_SESSION['filtered'] == 1) {
                            $record = "SELECT panelid,name,panelnum,email,verify FROM panel WHERE verify = '0'";
                            $result = $conn->query($record);
                            while ($row = $result->fetch_assoc()) {
                                $data[] = $row;
                            }
                        } else {
                            $record = "SELECT panelid,name,panelnum,email,verify FROM panel";
                            $result = $conn->query($record);
                            while ($row = $result->fetch_assoc()) {
                                $data[] = $row;
                            }
                        }
                        //store data and page number
                        if (isset($data)) {
                            $paginated_data = saveUser($data);
                            $total_pages = $_SESSION['totalpage'];
                            printPanel($paginated_data);
                        }

                    }
                }
                ?>
        </table>
    </div>

    <?php

    function getCommitteeHead()
    {
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Name</th>";
        echo "<th>Staff Number</th>";
        echo "<th>Email</th>";
        echo "<th>Verify</th>";
        echo "<th>Edit</th>";
        echo "<th>Reset</th>";
        echo "<th>Delete</th>";
        echo "</tr>";
    }
    function getSupervisorHead()
    {
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Name</th>";
        echo "<th>Staff Number</th>";
        echo "<th>Email</th>";
        echo "<th>Verify</th>";
        echo "<th>Edit</th>";
        echo "<th>Reset</th>";
        echo "<th>Delete</th>";
        echo "</tr>";
    }

    function getStudentHead()
    {
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Name</th>";
        echo "<th>IC number</th>";
        echo "<th>Matric Number</th>";
        echo "<th>Program</th>";
        echo "<th>Race</th>";
        echo "<th>CGPA</th>";
        echo "<th>Phone Number</th>";
        echo "<th>Email</th>";
        echo "<th>Verify</th>";
        echo "<th>Edit</th>";
        echo "<th>Reset</th>";
        echo "<th>Delete</th>";
        echo "</tr>";
    }

    function getPanelHead()
    {
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Name</th>";
        echo "<th>Panel Number</th>";
        echo "<th>Email</th>";
        echo "<th>Verify</th>";
        echo "<th>Edit</th>";
        echo "<th>Reset</th>";
        echo "<th>Delete</th>";
        echo "</tr>";
    }

    function saveUser($data)
    {
        $_SESSION['dataq'] = $data;
        $items_per_page = 10;
        $total_items = count($data);
        $total_pages = ceil($total_items / $items_per_page);
        $_SESSION['totalpage'] = $total_pages;
        $current_page = isset($_GET['page']) ? $_GET['page'] : $_SESSION['currentpage'];
        $_SESSION['currentpage'] = $current_page;

        $start_index = ($current_page - 1) * $items_per_page;
        $paginated_data = array_slice($data, $start_index, $items_per_page);
        return ($paginated_data);
    }

    function printCommittee($paginated_data)
    {
        foreach ($paginated_data as $row) {
            $cid = $row['cid'];
            $name = $row['name'];
            $staffnum = $row['staffnum'];
            $email = $row['email'];
            $verify = $row['verify'];
            $edit = $_SESSION['edit'];
            $editid = $_SESSION['editid'];
            if ($edit == 1 && $editid == $cid) {
                echo "<tr>";
                echo "<td>$cid</td>";
                echo "<form id=\"myForm\" method=\"post\" action=\"adminManageFunc.php\">";
                echo "<td><input type=\"text\" id=\"name\" name=\"name\" value=\"$name\"></td>";
                echo "<td><input type=\"text\" id=\"staffnum\" name=\"staffnum\" value=\"$staffnum\"></td>";
                echo "<td><input type=\"text\" id=\"email\" name=\"email\" value=\"$email\"></td>";
                echo "<td>";
                echo "</td><td>";
                editButton($cid);
                echo "</td><td>";
                echo "</td></tr>";
            } else {
                echo "<tr>";
                echo "<td>$cid</td>";
                echo "<td>$name</td>";
                echo "<td>$staffnum</td>";
                echo "<td>$email</td>";
                echo "<td>";
                veriButton($verify, $cid);
                echo "</td><td>";
                editButton($cid);
                echo "</td><td>";
                cPassButton($cid);
                echo "</td><td>";
                delButton($cid);

                echo "</td></tr>";
            }


        }
    }
    function printSupervisor($paginated_data)
    {
        foreach ($paginated_data as $row) {
            $svid = $row['svid'];
            $name = $row['name'];
            $staffnum = $row['staffnum'];
            $email = $row['email'];
            $verify = $row['verify'];
            $edit = $_SESSION['edit'];
            $editid = $_SESSION['editid'];
            if ($edit == 1 && $editid == $svid) {
                echo "<tr>";
                echo "<td>$svid</td>";
                echo "<form id=\"myForm\" method=\"post\" action=\"adminManageFunc.php\">";
                echo "<td><input type=\"text\" id=\"name\" name=\"name\" value=\"$name\"></td>";
                echo "<td><input type=\"text\" id=\"staffnum\" name=\"staffnum\" value=\"$staffnum\"></td>";
                echo "<td><input type=\"text\" id=\"email\" name=\"email\" value=\"$email\"></td>";
                echo "<td>";
                echo "</td><td>";
                editButton($svid);
                echo "</td><td>";
                echo "</td></tr>";
            } else {
                echo "<tr>";
                echo "<td>$svid</td>";
                echo "<td>$name</td>";
                echo "<td>$staffnum</td>";
                echo "<td>$email</td>";
                echo "<td>";
                veriButton($verify, $svid);
                echo "</td><td>";
                editButton($svid);
                echo "</td><td>";
                cPassButton($svid);
                echo "</td><td>";
                delButton($svid);
                echo "</td></tr>";
            }
        }
    }
    function printStudent($paginated_data)
    {
        foreach ($paginated_data as $row) {

            $studentid = $row['studentid'];
            $name = $row['name'];
            $ic = $row['ic'];
            $matric = $row['matric'];
            $program = $row['prog'];
            $race = $row['race'];
            $cgpa = $row['cgpa'];
            $phnum = $row['phnum'];
            $email = $row['email'];
            $verify = $row['verify'];
            $edit = $_SESSION['edit'];
            $editid = $_SESSION['editid'];
            if ($edit == 1 && $editid == $studentid) {
                echo "<tr>";
                echo "<td>$studentid</td>";
                echo "<form id=\"myForm\" method=\"post\" action=\"adminManageFunc.php\">";
                echo "<td><input type=\"text\"  class=\"form-control\" id=\"name\" name=\"name\" value=\"$name\"></td>";
                echo "<td><input type=\"text\" class=\"form-control\" id=\"ic\" name=\"ic\" value=\"$ic\"></td>";
                echo "<td><input type=\"text\" class=\"form-control\" id=\"matric\" name=\"matric\" value=\"$matric\"></td>";
                echo "<td><input type=\"text\" class=\"form-control\" id=\"prog\" name=\"prog\" value=\"$program\"></td>";
                echo "<td><input type=\"text\" class=\"form-control\" id=\"race\" name=\"race\" value=\"$race\"></td>";
                echo "<td><input type=\"text\" class=\"form-control\" id=\"cgpa\" name=\"cgpa\" value=\"$cgpa\"></td>";
                echo "<td><input type=\"text\" class=\"form-control\" id=\"phnum\" name=\"phnum\" value=\"$phnum\"></td>";
                echo "<td><input type=\"text\" class=\"form-control\" id=\"email\" name=\"email\" value=\"$email\"></td>";
                echo "<td>";
                echo "</td><td>";
                editButton($studentid);
                echo "</td><td>";
                echo "</td></tr>";
                echo "</div>";
            } else {
                echo "<tr>";
                echo "<td>$studentid</td>";
                echo "<td>$name</td>";
                echo "<td>$ic</td>";
                echo "<td>$matric</td>";
                echo "<td>$program</td>";
                echo "<td>$race</td>";
                echo "<td>$cgpa</td>";
                echo "<td>$phnum</td>";
                echo "<td>$email</td>";
                echo "<td>";
                veriButton($verify, $studentid);
                echo "</td><td>";
                editButton($studentid);
                echo "</td><td>";
                cPassButton($studentid);
                echo "</td><td>";
                delButton($studentid);
                echo "</td></tr>";
            }
        }
    }
    function printPanel($paginated_data)
    {
        foreach ($paginated_data as $row) {
            $panelid = $row['panelid'];
            $name = $row['name'];
            $panelnum = $row['panelnum'];
            $email = $row['email'];
            $verify = $row['verify'];
            $edit = $_SESSION['edit'];
            $editid = $_SESSION['editid'];
            if ($edit == 1 && $editid == $panelid) {
                echo "<tr>";
                echo "<td>$panelid</td>";
                echo "<form id=\"myForm\" method=\"post\" action=\"adminManageFunc.php\">";
                echo "<td><input type=\"text\" id=\"name\" name=\"name\" value=\"$name\"></td>";
                echo "<td><input type=\"text\" id=\"panelnum\" name=\"panelnum\" value=\"$panelnum\"></td>";
                echo "<td><input type=\"text\" id=\"email\" name=\"email\" value=\"$email\"></td>";
                echo "<td>";
                echo "</td><td>";
                editButton($panelid);
                echo "</td><td>";
                echo "</td></tr>";
            } else {
                echo "<tr>";
                echo "<td>$panelid</td>";
                echo "<td>$name</td>";
                echo "<td>$panelnum</td>";
                echo "<td>$email</td>";
                echo "<td>";
                veriButton($verify, $panelid);
                echo "</td><td>";
                editButton($panelid);
                echo "</td><td>";
                cPassButton($panelid);
                echo "</td><td>";
                delButton($panelid);
                echo "</td></tr>";
            }
        }
    }

    function veriButton($verify, $id)
    {
        if ($verify == '1') {
            echo "<div class=\"row justify-content-center\">
                    <div class=\"col-auto\">
                        <form id=\"myForm\" method=\"post\" action=\"adminManageFunc.php\">
                        <button type=\"submit\" name=\"verii\" class=\"btn btn-success\" value=\"";
            echo $id;
            echo "\"><i class=\"bi bi-person-check-fill\"></i>
                        </button>
                    </div>
                </div>";
        } else if ($verify == '0') {
            echo "<div class=\"row justify-content-center\">
                    <div class=\"col-auto\">
                        <form id=\"myForm\" method=\"post\" action=\"adminManageFunc.php\">
                        <button type=\"submit\" name=\"verii\" class=\"btn btn-danger\" onclick=\"return confirm('Proceed to verify this user? (Please double check data is correct)')\" value=\"";
            echo $id;
            echo "\"><i class=\"bi bi-person-dash\"></i>
                        </button>
                    </div>
                </div>";
        }
    }

    function delButton($id)
    {
        echo "<div class=\"row justify-content-center\">
                <div class=\"col-auto\">
                    <form id=\"myForm\" method=\"post\" action=\"adminManageFunc.php\">
                    <button type=\"submit\" name=\"delete\" class=\"btn btn-danger\" onclick=\"return confirm('Are you sure you want to delete?')\" value=\"";
        echo $id;
        echo "\"><i class=\"bi bi-trash\"></i>
                    </button>
                </div>
            </div>";
    }

    function editButton($id)
    {
        $edit = $_SESSION['edit'];
        $editid = $_SESSION['editid'];
        if ($edit == 1 && $id == $editid) {
            echo "<div class=\"row justify-content-center\">
                    <div class=\"col-auto\">
                        <form id=\"myForm\" method=\"post\" action=\"adminManageFunc.php\">
                        <button type=\"submit\" name=\"submit\" class=\"btn btn-success\" onclick=\"return confirm('Are you sure want to edit?')\" value=\"";
            echo $id;
            echo "\"><i class=\"bi bi-wrench\"></i>
                        </button>
                    </div>
                </div>";
            echo "</td><td>";
            echo "<div class=\"row justify-content-center\">
                    <div class=\"col-auto\">
                        <form id=\"myForm\" method=\"post\" action=\"adminManageFunc.php\">
                        <button type=\"submit\" name=\"cancel\" class=\"btn btn-danger\"  value=\"";
            echo $id;
            echo "\"><i class=\"bi bi-x-octagon\"></i>
                        </button>
                    </div>
                </div>";
        } else {
            echo "<div class=\"row justify-content-center\">
                    <div class=\"col-auto\">
                        <form id=\"myForm\" method=\"post\" action=\"adminManageFunc.php\">
                        <button type=\"submit\" name=\"edit\" class=\"btn btn-primary\" value=\"";
            echo $id;
            echo "\"><i class=\"fas fa-pencil\"></i>
                        </button>
                    </div>
                </div>";
        }

    }

    function cPassButton($id)
    {
        echo "<div class=\"row justify-content-center\">
                <div class=\"col-auto\">
                    <form id=\"myForm\" method=\"post\" action=\"adminManageFunc.php\">
                    <button type=\"submit\" name=\"cpass\" class=\"btn btn-danger\"  value=\"";
        echo $id;
        echo "\"><i class=\"bi bi-key-fill\"></i>
                    </button></form>
                </div>
            </div>";
    }

    //Generate page number
    if (!isset($_GET['query']) || $_GET['query'] == null) {
        //Generate pagination links except filter
        if (isset($total_pages)) {
            echo "<div class='text-center'>";
            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<a href='?page=$i'>$i</a> ";
            }
            echo "</div>";
        }
    }

    ?>

    </div>
    </div>

    <script>
        document.getElementById('copyButton').addEventListener('click', function() {
            // Get the text field
            var copyText = document.getElementById('newpassc');
            
            // Select the text field
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            
            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.value)
                .then(() => {
                    // Display success message
                    var copyMessage = document.getElementById('copyMessage');
                    copyMessage.style.display = 'inline';
                    setTimeout(() => { copyMessage.style.display = 'none'; }, 2000);
                })
                .catch(err => {
                    console.error('Failed to copy text: ', err);
                });
        });
    </script>
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