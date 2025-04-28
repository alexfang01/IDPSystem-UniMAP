<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include ('../config/db_connect.php');
include './logincheckComm.php';
include './sidebarComm.php';

// Function to generate a random salt
function generate_salt() {
    return bin2hex(random_bytes(16)); // 16 bytes = 32 characters in hexadecimal
  }
  
  // Function to generate hashed password with salt
  function generate_hashed_password($password, $salt) {
    return password_hash($password . $salt, PASSWORD_BCRYPT);
  }

  // Function to verify password
function verify_password($input_password, $stored_hashed_password, $stored_salt) {
    return password_verify($input_password . $stored_salt, $stored_hashed_password);
}

// error
$flag_err = 0;
$pass_err = "";

// Getting user's information
$commid = $_SESSION['id'];
$sqlUser = "SELECT * FROM committee WHERE cid = '$commid'";
$resultUser = mysqli_query($conn, $sqlUser);
$infoUser = mysqli_fetch_all($resultUser, MYSQLI_ASSOC); // convert to associate array

if (mysqli_num_rows($resultUser) > 0){
    $name = htmlspecialchars($infoUser[0]['name']);
    $staffnum = htmlspecialchars($infoUser[0]['staffnum']);
    $email = htmlspecialchars($infoUser[0]['email']);
}

// Change password
if (isset($_POST['submit'])) {
    $oldpass = mysqli_real_escape_string($conn, $_POST['oldpass']);
    $newpass = mysqli_real_escape_string($conn, $_POST['newpass']);
    $confirmpass = mysqli_real_escape_string($conn, $_POST['confirmpass']);

    $stored_hashed_password = $infoUser[0]['pass'];
    $stored_salt = $infoUser[0]['salt'];

    // Check whether the old password is correct

    if(!verify_password($oldpass, $stored_hashed_password, $stored_salt)) {
        if(!$flag_err){
            $flag_err = 1;
            $pass_err = "Incorrect Password, Please try again";
        }
    }

    // Minimum of 8 characters in password
    if (strlen($newpass) < 8 || strlen($confirmpass) < 8) {
        
        if(!$flag_err){
            $flag_err = 1;
            $pass_err = "Password must be a minimum of 8 characters";
        }
    }

    // Check if new password matches confirm password
    if ($confirmpass != $newpass) {
        if(!$flag_err){
            $flag_err = 1;
            $pass_err = "Password Doesn't Match";
        }
    }

    // Ensure new password is different from old password
    if ($newpass === $oldpass) {
        if(!$flag_err){
            $flag_err = 1;
            $pass_err = "Please do not repeat the OLD password, Enter a NEW ones";
        }
    }

    // If there are errors, display them
    if(!$pass_err){

        // Generate salt
        $saltTemp = generate_salt();
        $salt = mysqli_real_escape_string($conn, $saltTemp);

        $hashed_password = generate_hashed_password($newpass, $salt);

        // Proceed to update the password
        $sql = "UPDATE committee SET pass = '$hashed_password', salt = '$salt' WHERE cid = '$commid'";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Password Updated Successfully');</script>";
            unset($_POST['oldpass'], $_POST['newpass'], $_POST['confirmpass']);
        } else {
            echo 'Query error: ' . mysqli_error($conn);
        }
    }
}


mysqli_free_result($resultUser);

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <style>

        .title-container{
            border-bottom : 3px solid black;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            /* border: 3px solid red; */
        }

        .container-custom{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50%;
        }

        .profile-form {
            width: 90%; /* Adjust the width of the form */
            max-width: 1100px; /* Set a maximum width if needed */
        }

        .member-textbox{
            /* width: 70%;
            margin-left: 195px;
            right: 0; */
        
        }

        .custom-textbox{
            font-size: 15px; /*for the floating label */
        }

        .sticky-textbox {
            right: 0;
            width: calc(100% - 53px - 208px); /* Adjust as needed */
            margin-left: 53px; /* Adjust as needed */
            margin-right: 208px; /* Adjust as needed */
        }

        .sticky-textbox-2{
            right: 0;
            width: calc(100% - 124px - 208px); /* Adjust as needed */
            margin-left: 124px; /* Adjust as needed */
            margin-right: 208px; /* Adjust as needed */
        }

        .sticky-textbox-3{
            right: 0;
            width: calc(100% - 130px - 208px); /* Adjust as needed */
            margin-left: 130px; /* Adjust as needed */
            margin-right: 208px; /* Adjust as needed */
        }
        
        .sticky-textbox-11{
            right: 0;
            width: calc(100% - 208px - 208px); /* Adjust as needed */
            margin-left: 208px; /* Adjust as needed */
            margin-right: 208px; /* Adjust as needed */
        }

        .sticky-textbox-12{
            right: 0;
            width: calc(100% + 148px); /* Adjust as needed */
            margin-left: -148px; /* Adjust as needed */
        }

        .sticky-textbox-13{
            right: 0;
            width: calc(100% + 161px); /* Adjust as needed */
            margin-left: -161px; /* Adjust as needed */
        }

        .sticky-textbox-14{
            right: 0;
            width: calc(100% - 1px - 208px); /* Adjust as needed */
            margin-left: 1px; /* Adjust as needed */
            margin-right: 208px; /* Adjust as needed */
        }

        .btn-custom-size{
            width:20%;
        }
    @media (max-width: 768px){
        .title-container{
            border-bottom : 3px solid black;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            /* border: 3px solid red; */
        }

        .profile-form {
            width: 100%; /* Adjust the width of the form */
            max-width: 700px; /* Set a maximum width if needed */
        }

        .member-textbox{
            /* width: 70%;
            margin-left: 195px;
            right: 0; */
        
        }

        .custom-textbox{
            font-size: 12px; /*for the floating label */
        }

        .sticky-textbox {
            width: calc(100%);
            margin-left: 0px;
            margin-right: 0px;
            font-size: 13px;
        }

        .sticky-textbox-2{
            width: calc(100%);
            margin-left: 0px;
            margin-right: 0px;
            font-size: 13px;
        }

        .sticky-textbox-3{
            width: calc(100%);
            margin-left: 0px;
            margin-right: 0px;
            font-size: 13px;
        }

        
        .sticky-textbox-11{
            width: calc(100%);
            margin-left: 0px;
            margin-right: 0px;
            font-size: 13px;
        }

        .sticky-textbox-12{
            width: calc(100%);
            margin-left: 0px;
            margin-right: 0px;
            font-size: 13px;
        }

        .sticky-textbox-13{
            width: 100%;
            margin-left: 10px;
            margin-right: auto;
            font-size: 13px;
        }

        .sticky-textbox-14{
            width: 100%;
            margin-left: 0;
            margin-right: auto;
            font-size: 13px;
        }

        .btn-custom-size{
            width:40%;
        }

        .custom-display{
            display: none;
        }

        .form-control{
            font-size: 13px;
        }

    .password-container {
                flex-direction: column;
            }

            .col-auto {
                width: 100%;
            }
    }
    </style>
        <!-- Main content in here -->
        <div class="main">
            <main class="content px-3 py-2">
            
            <div class="container">
            <form class="profile-form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                <div class="title-container">
                    <h1 class="text-center">User Profile</h1>
                </div>
                <!-- due date message --><p class="mt-2 text-center"><span class="due-msg">Double Check your data</span></p>
                <div class="row g-3 align-items-center py-2">
                    <div class="col-auto">
                        <label for="username" class="col-form-label">Staff Number :</label>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control sticky-textbox" disabled readonly id="username" placeholder="Info not completed, please inform your supervisor" value="<?php echo isset($staffnum)?htmlspecialchars($staffnum):'';?>">
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col-auto">
                        <label for="ic" class="col-form-label">Name :</label>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control sticky-textbox-2" disabled readonly id="ic" placeholder="Info not completed, please inform your supervisor" value="<?php echo isset($name)?htmlspecialchars($name):'';?>">
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col-auto">
                        <label for="matrix" class="col-form-label">Email :</label>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control sticky-textbox-3" disabled readonly id="matrix" placeholder="Info not completed, please inform your supervisor" value="<?php echo isset($email)?htmlspecialchars($email):'';?>">
                    </div>
                </div>
                
                <div class="row g-3 align-items-center py-2 password-container">
                    <div class="col-auto">
                        <label for="pass" class="col-form-label">Change Password :</label>
                    </div>
                    <div class="col form-floating sticky-textbox-14 custom-textbox">
                        <input type="password" class="form-control " id="oldpass" name="oldpass" placeholder="Enter old Password" value="<?php echo isset($_POST['oldpass'])? htmlspecialchars($_POST['oldpass']):'';?>">
                        <label for="oldpass" class="ms-3"><span class="custom-display">Enter Your</span> Old Password</label>
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col form-floating sticky-textbox-11 custom-textbox">
                        <input type="password" class="form-control " id="newpass" name="newpass" placeholder="Enter new Password" value="<?php echo isset($_POST['newpass'])? htmlspecialchars($_POST['newpass']):'';?>">
                        <label for="newpass" class="ms-3">New Password</label>
                    </div>
                </div>
                <div class="row g-3 align-items-center py-2">
                    <div class="col form-floating sticky-textbox-11 custom-textbox">
                        <input type="password" class="form-control " id="confirmpass" name="confirmpass" placeholder="Enter again the Password" value="<?php echo isset($_POST['confirmpass'])?htmlspecialchars($_POST['confirmpass']):'';?>">
                        <label for="confirmpass" class="ms-3">Confirm Password</label>
                    </div>
                </div>
                <!-- error message--><p style="text-align: center;"><span class="due-msg center"><?php echo $pass_err ?></span></p>
                <div class="py-2 d-flex justify-content-center" >
                    <button type="submit" id="submit" name="submit" class="btn btn-block mt-2 btn-custom-shadow custom-content-btn btn-custom-size btn-primary">SUBMIT</button>
                </div>
            </form>
            </div>
            </main>
        </div>
    </div>
</body>
</html>