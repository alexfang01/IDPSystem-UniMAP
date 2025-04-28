<?php
include('../config/db_connect.php');
include './logincheckComm.php';

if (isset($_SESSION['userview']) && $_SESSION['userview'] !== null) {
    $userview = $_SESSION['userview'];
}

//verify button
if (isset($_POST['verii'])) {
    $id = $_POST['verii'];
    $name = '';
    if ($userview == "Committee") {
        $record = "SELECT name, verify FROM committee WHERE cid = '$id'";
        $result = $conn->query($record);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $target = $row["verify"];
            $name = $row["name"];
        }
        if ($target == '1') {
            $sql = "UPDATE committee SET verify = 0 WHERE cid = '$id' ";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "Committee member $name is now unverified.";
            }

        } else if ($target == '0') {
            $sql = "UPDATE committee SET verify = 1 WHERE cid = '$id' ";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "Committee member $name is verified successfully.";
            }
        }

    } else if ($userview == "Supervisor") {
        $record = "SELECT name, verify FROM supervisor WHERE svid = '$id'";
        $result = $conn->query($record);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $target = $row["verify"];
            $name = $row["name"];
        }
        if ($target == '1') {
            $sql = "UPDATE supervisor SET verify = 0 WHERE svid = '$id' ";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "Supervisor $name is now unverified.";
            }
        } else if ($target == '0') {
            $sql = "UPDATE supervisor SET verify = 1 WHERE svid = '$id' ";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "Supervisor $name is verified successfully.";
            }
        }

    } else if ($userview == "Student") {
        $record = "SELECT name, verify FROM student WHERE studentid = '$id'";
        $result = $conn->query($record);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $target = $row["verify"];
            $name = $row["name"];
        }
        if ($target == '1') {
            $sql = "UPDATE student SET verify = 0 WHERE studentid = '$id' ";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "Student $name is now unverified.";
            }
        } else if ($target == '0') {
            $sql = "UPDATE student SET verify = 1 WHERE studentid = '$id' ";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "Student $name is verified successfully.";
            }
        }

    }
    else if ($userview == "Panel") {
        $record = "SELECT name, verify FROM panel WHERE panelid = '$id'";
        $result = $conn->query($record);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $target = $row["verify"];
            $name = $row["name"];
        }
        if ($target == '1') {
            $sql = "UPDATE panel SET verify = 0 WHERE panelid = '$id' ";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "Panel $name is now unverified.";
            }
        } else if ($target == '0') {
            $sql = "UPDATE panel SET verify = 1 WHERE panelid = '$id' ";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "Panel $name is verified successfully.";
            }
        }

    }
    $conn->close();
}

//delete button
if (isset($_POST['delete'])) {
    $id = $_POST['delete'];
    if ($userview == "Committee") {
        $record = "DELETE FROM committee WHERE cid = '$id'";
        $result = $conn->query($record);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        }

    } else if ($userview == "Supervisor") {
        $record = "DELETE FROM supervisor WHERE svid = '$id'";
        $result = $conn->query($record);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        }

    } else if ($userview == "Student") {
        $record = "DELETE FROM student WHERE studentid = '$id'";
        $result = $conn->query($record);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        }

    }
    else if ($userview == "Panel") {
        $record = "DELETE FROM panel WHERE panelid = '$id'";
        $result = $conn->query($record);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        }

    }
    $conn->close();

}

//edit button
if (isset($_POST['edit'])) {
    $id = $_POST['edit'];
    $_SESSION['editid'] = $id;
    $_SESSION['edit'] = 1;
}

//submit button
if (isset($_POST['submit'])) {
    $id = $_POST['submit'];

    if ($userview == "Committee") {
        $name = $_POST['name'];
        $staffnum = $_POST['staffnum'];
        $email = $_POST['email'];
        $sql = "UPDATE committee
        SET name = '$name', staffnum = '$staffnum', email = '$email'
        WHERE cid = $id";
        if ($conn->query($sql) === TRUE) {
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $conn->close();

    } else if ($userview == "Supervisor") {
        $name = $_POST['name'];
        $staffnum = $_POST['staffnum'];
        $email = $_POST['email'];
        $sql = "UPDATE supervisor
        SET name = '$name', staffnum = '$staffnum', email = '$email'
        WHERE svid = $id";
        if ($conn->query($sql) === TRUE) {
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $conn->close();

    } else if ($userview == "Student") {
        $name = $_POST['name'];
        $ic = $_POST['ic'];
        $matric = $_POST['matric'];
        $prog = $_POST['prog'];
        $race = $_POST['race'];
        $cgpa = $_POST['cgpa'];
        $phnum = $_POST['phnum'];
        $email = $_POST['email'];
        $sql = "UPDATE student
        SET name = '$name', ic = '$ic', matric = '$matric', prog = '$prog' , race = '$race' , cgpa = '$cgpa' , phnum = '$phnum' , email = '$email'
        WHERE studentid = $id";
        if ($conn->query($sql) === TRUE) {
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $conn->close();
    }
    else if ($userview == "Panel") {
        $name = $_POST['name'];
        $panelnum = $_POST['panelnum'];
        $email = $_POST['email'];
        $sql = "UPDATE panel
        SET name = '$name', panelnum = '$panelnum', email = '$email'
        WHERE panelid = $id";
        if ($conn->query($sql) === TRUE) {
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $conn->close();

    }

    $name = $_POST['name'];
    $_SESSION['editid'] = 0;
    $_SESSION['edit'] = 0;
}

//cancel button
if (isset($_POST['cancel'])) {
    $_SESSION['editid'] = 0;
    $_SESSION['edit'] = 0;
}


header("Location: commManage.php");
//echo '<script>alert("Username not exist")</script>';
//header("Refresh:20; url=./adminManage.php");
?>