<!DOCTYPE html>
<html lang="en">
<?php
include ('../config/db_connect.php');
include './logincheckAdmin.php';
include './sidebarAdmin.php';

if (isset($_POST['delete'])) {
    $id = $_POST['delete'];
    $record = "DELETE FROM theme WHERE themeid = '$id'";
    $result = $conn->query($record);
}

if (isset($_POST['submit'])) {
    $id = $_POST['submit'];
    $title = $_POST['title'];
    $sql = "UPDATE theme
        SET title = '$title' WHERE themeid = $id";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
if (isset($_POST['addtheme'])) {
    $newtitle = $_POST['newtitle'];
    $sql = "INSERT INTO theme (themeid, title) VALUES ('', '$newtitle') ";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$record = "SELECT * FROM theme";
        $result = $conn->query($record);
        while ($row = $result->fetch_assoc()) {
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
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    integrity="sha512-fDnh8MPtZZ2ayE8D6f6fvMm8eEaMYPeMgpIH93nUuO8hjO7w/JjrGzrj9oH9W/zB84r+JUtYW+lpPF9LCJLqKQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">

<html>
<style>
    .box {

        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 20px 20px 20px 20px;
        text-align: center;
    }
    .table { 
        border: 1px solid black;
        padding: 4px;
        text-align: left;
        margin: 0 auto;
        width: 60%;
        vertical-align: middle;
        border-collapse: collapse;
        border-radius: 20px;
        overflow: hidden;
    }
    button{
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 20px 20px 20px 20px;
        text-align: center;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .table th {
            background-color: #f2f2f2;
            text-align: center;
        }
    @media (max-width: 768px) {
        .title-container {
            width: fit-content;
            margin: 0 auto;
        }
    }
</style>
<body>
    <!-- Tablezzz -->
    <h1 class="text-center mb-3 title-container">Theme Editing</h1>
    <a class="text-center mb-3">
        <form name="add" action="./themeEdit.php" method="POST">
            <button type="submit" name="add">Add New Theme</button>
        </form>
    </a>

    <form class="text-center" method="GET" action="themeEdit.php">
        <input type="text" class="box" name="query" placeholder="Search...">
        <button type="submit" class="box">Search</button>
    </form>
    <br>

    <div class="table-responsive">
    <table class="table table-bordered table-light">
            <thead class=" thead-dark">
        <?php

        getHead();
        if(isset($_GET['query']) && $_GET['query'] != null){
            printz($filtered_data);
        }
        else{
            printz($data);
        }
        

        ?>
    </table>
    </div>

    <?php

    function getHead()
    {
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Title</th>";
        echo "<th>Edit</th>";
        echo "<th>Delete</th>";
        echo "</tr>";
        if (isset($_POST['add'])) {
            echo "<tr>";
            echo "<td></td>";
            echo "<form id=\"myForm\" method=\"post\" action=\"themeEdit.php\">";
            echo "<td><input type=\"text\" id=\"newtitle\" name=\"newtitle\" ></td>";
            echo "<td>";
            echo "<div class=\"row justify-content-center\">
            <div class=\"col-auto\">";
            echo "<button type=\"submit\" name=\"addtheme\" class=\"btn btn-success btn-lg\" onclick=\"return confirm('Are you sure want to Add new Theme?')\"><i class=\"bi bi-pencil-square\"></i>
            </button> ";
            echo "</div></div>";
            echo "</td><td>";
            echo "<div class=\"row justify-content-center\">
            <div class=\"col-auto\">";
            echo "<button type=\"submit\" name=\"cancel\" class=\"btn btn-danger btn-lg\"><i class=\"bi bi-x-octagon\"></i></button>";
            echo "</div></div>";
        }
    }

    function printz($data)
    {
        $i=1;
        foreach ($data as $row) {
            $id = $row['themeid'];
            $title = $row['title'];
            if (isset($_POST['edit'])) {
                $edit = 1;
                $editid = $_POST['edit'];
            } else {
                $edit = 0;
            }
            if ($edit == 1 && $editid == $id) {
                echo "<tr>";
                echo "<td>$i</td>";
                echo "<form id=\"myForm\" method=\"post\" action=\"themeEdit.php\">";
                echo "<td><input type=\"text\" id=\"title\" name=\"title\" value=\"$title\"></td>";
                echo "<td>";
                editButton($id);
                echo "</td></tr>";
            } else {
                echo "<tr>";
                echo "<td>$i</td>";
                echo "<td>$title</td>";
                echo "<td>";
                editButton($id);
                echo "</td><td>";
                delButton($id);
                echo "</td></tr>";
                
            }
            $i++;


        }
    }


    function delButton($id)
    {
        echo "<div class=\"row justify-content-center\">
                <div class=\"col-auto\">
                    <form id=\"myForm\" method=\"post\" action=\"themeEdit.php\">
                    <button type=\"submit\" name=\"delete\" class=\"btn btn-danger btn-lg\" onclick=\"return confirm('Are you sure you want to delete?')\" value=\"";
        echo $id;
        echo "\"><i class=\"bi bi-trash\"></i>
                    </button>
                </div>
            </div>";
    }

    function editButton($id)
    {
        if (isset($_POST['edit'])) {
            $edit = 1;
            $editid = $_POST['edit'];
        } else {
            $edit = 0;
        }
        if ($edit == 1 && $id == $editid) {
            echo "<div class=\"row justify-content-center\">
                    <div class=\"col-auto\">
                        <form id=\"myForm\" method=\"post\" action=\"themeEdit.php\">
                        <button type=\"submit\" name=\"submit\" class=\"btn btn-success btn-lg\" onclick=\"return confirm('Are you sure want to edit?')\" value=\"";
            echo $id;
            echo "\"><i class=\"bi bi-wrench\"></i>
                        </button>
                    </div>
                </div>";
            echo "</td><td>";
            echo "<div class=\"row justify-content-center\">
                    <div class=\"col-auto\">
                        <form id=\"myForm\" method=\"post\" action=\"themeEdit.php\">
                        <button type=\"submit\" name=\"cancel\" class=\"btn btn-danger btn-lg\"  value=\"";
            echo $id;
            echo "\"><i class=\"bi bi-x-octagon\"></i>
                        </button>
                    </div>
                </div>";
        } else {
            echo "<div class=\"row justify-content-center\">
                    <div class=\"col-auto\">
                        <form id=\"myForm\" method=\"post\" action=\"themeEdit.php\">
                        <button type=\"submit\" name=\"edit\" class=\"btn btn-primary btn-lg\" value=\"";
            echo $id;
            echo "\"><i class=\"fas fa-pencil\"></i>
                        </button>
                    </div>
                </div>";
        }

    }

    ?>

    </div>
    </div>
</body>

</html>