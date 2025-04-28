<?php

include('./config/db_connect.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is already logged in
if (isset($_SESSION['id']) && isset($_SESSION['loggedin']) && isset($_SESSION['userType'])) {
    // Destroy the session
    session_destroy();
    // Redirect to the login page
    header("Location: ./login.php");
    exit;
}

// Function to verify password
function verify_password($input_password, $stored_hashed_password, $stored_salt) {
    return password_verify($input_password . $stored_salt, $stored_hashed_password);
}

//initialized variable
$nameEmail=$pass="";
$nameEmail_err="";
$acc_err = "";

if (isset($_POST['login'])){
    $nameEmail = mysqli_real_escape_string($conn, $_POST['nameEmail']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);

    // validate for whether the email or name is correct or not
    if (filter_var($nameEmail, FILTER_VALIDATE_EMAIL)) {

        // checking the user level
        // Student
        $sqlStudent = "SELECT * FROM student WHERE email = '$nameEmail'";
        $sqlSV = "SELECT * FROM supervisor WHERE email = '$nameEmail'";
        $sqlCom = "SELECT * FROM committee WHERE email = '$nameEmail'";
        $sqlPanel = "SELECT * FROM panel WHERE email = '$nameEmail'";
        $sqlAdmin = "SELECT * FROM admin WHERE email = '$nameEmail'";
        

        // to check whether it exist or not
        $resultStudent = mysqli_query($conn, $sqlStudent);
        $resultSV = mysqli_query($conn, $sqlSV);
        $resultCom = mysqli_query($conn, $sqlCom);
        $resultPanel = mysqli_query($conn, $sqlPanel);
        $resultAdmin = mysqli_query($conn, $sqlAdmin);

        // to access the data using associate array
        $infoStudent = mysqli_fetch_all($resultStudent, MYSQLI_ASSOC);
        $infoSV = mysqli_fetch_all($resultSV, MYSQLI_ASSOC);
        $infoCom = mysqli_fetch_all($resultCom, MYSQLI_ASSOC);
        $infoPanel = mysqli_fetch_all($resultPanel, MYSQLI_ASSOC);
        $infoAdmin = mysqli_fetch_all($resultAdmin, MYSQLI_ASSOC);

        if (mysqli_num_rows($resultStudent) > 0) {
            if($infoStudent[0]['verify'] == 1){
                
                $stored_hashed_password = $infoStudent[0]['pass'];
                $stored_salt = $infoStudent[0]['salt'];

                if(verify_password($pass, $stored_hashed_password, $stored_salt)) {
                    $_SESSION['id'] = $infoStudent[0]['studentid'];
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['userType'] = "Student";
                    header("Location: ./student/home.php");
                }else{
                    $acc_err = "Wrong Password, Please enter a correct one";
                }

            }else{
                $acc_err = "Account not verified, Please wait for approval";
            }
        }else if(mysqli_num_rows($resultSV) > 0){
            if($infoSV[0]['verify'] == 1){
                
                $stored_hashed_password = $infoSV[0]['pass'];
                $stored_salt = $infoSV[0]['salt'];

                if(verify_password($pass, $stored_hashed_password, $stored_salt)) {
                    $_SESSION['id'] = $infoSV[0]['svid'];
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['userType'] = "Supervisor";
                    header("Location: ./supervisor/home.php");
                }else{
                    $acc_err = "Wrong Password, Please enter a correct one";
                }
            }else{
                $acc_err = "Account not verified, Please wait for approval";
            }
        }else if (mysqli_num_rows($resultCom) > 0){
            if($infoCom[0]['verify'] == 1){
                
                $stored_hashed_password = $infoCom[0]['pass'];
                $stored_salt = $infoCom[0]['salt'];

                if(verify_password($pass, $stored_hashed_password, $stored_salt)) {
                    $_SESSION['id'] = $infoCom[0]['cid'];
                    $_SESSION['username'] = $infoCom[0]['name'];
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['userType'] = "Committee";
                    header("Location: ./committee/home.php");
                }else{
                    $acc_err = "Wrong Password, Please enter a correct one";
                }
            }else{
                $acc_err = "Account not verified, Please wait for approval";
            }
        }else if (mysqli_num_rows($resultPanel) > 0){
            if($infoPanel[0]['verify'] == 1){
                
                $stored_hashed_password = $infoPanel[0]['pass'];
                $stored_salt = $infoPanel[0]['salt'];

                if(verify_password($pass, $stored_hashed_password, $stored_salt)) {
                    $_SESSION['id'] = $infoPanel[0]['panelid'];
                    $_SESSION['username'] = $infoPanel[0]['name'];
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['userType'] = "Panel";
                    header("Location: ./panel/home.php");
                }else{
                    $acc_err = "Wrong Password, Please enter a correct one";
                }
            }else{
                $acc_err = "Account not verified, Please wait for approval";
            }
        }else if (mysqli_num_rows($resultAdmin) > 0){
            
            $stored_hashed_password = $infoAdmin[0]['pass'];
            $stored_salt = $infoAdmin[0]['salt'];

            if(verify_password($pass, $stored_hashed_password, $stored_salt)) {
                $_SESSION['id'] = $infoAdmin[0]['adminid'];
                $_SESSION['username'] = $infoAdmin[0]['name'];
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['userType'] = "Admin";
                header("Location: ./admin/home.php");
            }else{
                $acc_err = "Wrong Password, Please enter a correct one";
            }
        }else{
                $acc_err = "Wrong Email or Password, Please try again";
        }
        mysqli_free_result($resultStudent);
        mysqli_free_result($resultSV);
        mysqli_free_result($resultCom);
        mysqli_free_result($resultPanel);
        mysqli_free_result($resultAdmin);

    }else if (preg_match("/^[a-zA-Z ]+$/", $nameEmail)){ // if it use name rather than email
        $sqlStudent = "SELECT * FROM student WHERE name = '$nameEmail'";
        $sqlSV = "SELECT * FROM supervisor WHERE name = '$nameEmail'";
        $sqlCom = "SELECT * FROM committee WHERE name = '$nameEmail'";
        $sqlPanel = "SELECT * FROM panel WHERE name = '$nameEmail'";
        $sqlAdmin = "SELECT * FROM admin WHERE name = '$nameEmail'";
            
            // to check whether account exist or not
            $resultStudent = mysqli_query($conn, $sqlStudent);
            $resultSV = mysqli_query($conn, $sqlSV);
            $resultCom = mysqli_query($conn, $sqlCom);
            $resultPanel = mysqli_query($conn, $sqlPanel);
            $resultAdmin = mysqli_query($conn, $sqlAdmin);

            // to access the data using associate array
            $infoStudent = mysqli_fetch_all($resultStudent, MYSQLI_ASSOC);
            $infoSV = mysqli_fetch_all($resultSV, MYSQLI_ASSOC);
            $infoCom = mysqli_fetch_all($resultCom, MYSQLI_ASSOC);
            $infoPanel = mysqli_fetch_all($resultPanel, MYSQLI_ASSOC);
            $infoAdmin = mysqli_fetch_all($resultAdmin, MYSQLI_ASSOC);

            if (mysqli_num_rows($resultStudent) > 0) {
                if($infoStudent[0]['verify'] == 1){
                    
                    $stored_hashed_password = $infoStudent[0]['pass'];
                    $stored_salt = $infoStudent[0]['salt'];

                    if(verify_password($pass, $stored_hashed_password, $stored_salt)) {
                        $_SESSION['id'] = $infoStudent[0]['studentid'];
                        $_SESSION['loggedin'] = TRUE;
                        $_SESSION['userType'] = "Student";
                        header("Location: ./student/home.php");
                    }else{
                        $acc_err = "Wrong Password, Please enter a correct one";
                    }
    
                }else{
                    $acc_err = "Account not verified, Please wait for approval";
                }
            }else if(mysqli_num_rows($resultSV) > 0){
                if($infoSV[0]['verify'] == 1){

                    $stored_hashed_password = $infoSV[0]['pass'];
                    $stored_salt = $infoSV[0]['salt'];

                    if(verify_password($pass, $stored_hashed_password, $stored_salt)) {
                        $_SESSION['id'] = $infoSV[0]['svid'];
                        $_SESSION['loggedin'] = TRUE;
                        $_SESSION['userType'] = "Supervisor";
                        header("Location: ./supervisor/home.php");
                    }else{
                        $acc_err = "Wrong Password, Please enter a correct one";
                    }
                    
                }else{
                    $acc_err = "Account not verified, Please wait for approval";
                }
            }else if (mysqli_num_rows($resultCom) > 0){
                if($infoCom[0]['verify'] == 1){
                    
                    $stored_hashed_password = $infoCom[0]['pass'];
                    $stored_salt = $infoCom[0]['salt'];

                    if(verify_password($pass, $stored_hashed_password, $stored_salt)) {
                        $_SESSION['id'] = $infoCom[0]['cid'];
                        $_SESSION['username'] = $infoCom[0]['name'];
                        $_SESSION['loggedin'] = TRUE;
                        $_SESSION['userType'] = "Committee";
                        header("Location: ./committee/home.php");
                    }else{
                        $acc_err = "Wrong Password, Please enter a correct one";
                    }
                    
                }else{
                    $acc_err = "Account not verified, Please wait for approval";
                }
            }else if (mysqli_num_rows($resultPanel) > 0){
                if($infoPanel[0]['verify'] == 1){
                    
                    $stored_hashed_password = $infoPanel[0]['pass'];
                    $stored_salt = $infoPanel[0]['salt'];

                    if(verify_password($pass, $stored_hashed_password, $stored_salt)) {
                        $_SESSION['id'] = $infoPanel[0]['panelid'];
                        $_SESSION['username'] = $infoPanel[0]['name'];
                        $_SESSION['loggedin'] = TRUE;
                        $_SESSION['userType'] = "Panel";
                        header("Location: ./panel/home.php");
                    }else{
                        $acc_err = "Wrong Password, Please enter a correct one";
                    }
                }else{
                    $acc_err = "Account not verified, Please wait for approval";
                }
            }else if (mysqli_num_rows($resultAdmin) > 0){

                $stored_hashed_password = $infoAdmin[0]['pass'];
                $stored_salt = $infoAdmin[0]['salt'];

                if(verify_password($pass, $stored_hashed_password, $stored_salt)) {
                    $_SESSION['id'] = $infoAdmin[0]['adminid'];
                    $_SESSION['username'] = $infoAdmin[0]['name'];
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['userType'] = "Admin";
                    header("Location: ./admin/home.php");
                }else{
                    $acc_err = "Wrong Password, Please enter a correct one";
                }

            }else{
                    $acc_err = "Wrong name or Password, Please try again";
            }

             // $infos = mysqli_fetch_all($result, MYSQLI_ASSOC); // create associate array
             mysqli_free_result($resultStudent);
             mysqli_free_result($resultSV);
             mysqli_free_result($resultCom);
             mysqli_free_result($resultAdmin);
             mysqli_free_result($resultPanel);
    }else{
            $nameEmail_err = "Invalid Username or Email address";
    }
    
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <title>IDP Management System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
        <style>
            
            .box {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 0px 0px 40px 40px;
            background-color: white;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            }

            body {
                overflow-y: scroll;
            }

            .custom-textbox{
                font-size: 13px;
            }

            .fade-in {
                opacity: 0;
                transition: opacity 0.7s ease-in-out; /* Adjust the duration and timing function as needed */
            }

            .fade-in.loaded {
                opacity: 1;
            }

            .login-box-image {
                max-width: 500px;
                margin: auto;
                margin-top: 100px;
                padding: 120px;
                border: 0px solid #ccc;
                border-radius: 40px 40px 0px 0px;
                background-image: url('assets/c2/loginImage.png');
                background-size: cover;
                background-position: center;
                box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            }

            .login-pic{
            background-color: #C5C6C7;
            }

            footer {
            margin-top: auto; 
            padding: 20px 0;
            }

            .btn-custom-shadow {
                width: 150px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            }

            .error-msg{
              font-size : 14px;
              color: red;
            }

            .glowing-text {
            font-weight: bold;
            color: black; /* Setting the font color to black */
            text-shadow: 0 0 10px white, 0 0 20px white, 0 0 30px white, 0 0 40px white;
            animation: glowing 1.5s infinite alternate;
            }

            @keyframes glowing {
            from {
                text-shadow: 0 0 10px white, 0 0 20px white, 0 0 30px white, 0 0 40px white;
            }
            to {
                text-shadow: 0 0 20px white, 0 0 30px white, 0 0 40px white, 0 0 50px white;
            }
            }

            .title-container{
                margin-top: 48px;
            }
        @media(max-width: 768px){
            
            .box {
                max-width: none; 
                width: 100%;
                border: 1px solid #ccc;
                border-radius: 0px 0px 40px 40px;
                background-color: white;
                box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
                max-width: none;
                font-size: 12px;
                margin-bottom: 1px;
            }

            body {
                overflow-y: scroll;
            }

            .custom-textbox{
                font-size: 13px;
            }

            .fade-in {
                opacity: 0;
                transition: opacity 0.7s ease-in-out; /* Adjust the duration and timing function as needed */
            }

            .fade-in.loaded {
                opacity: 1;
            }

            .login-box-image {
                width: 100%;
                border: 0px solid #ccc;
                padding: 80px;
                border-radius: 40px 40px 0px 0px;
                background-image: url('assets/c2/loginImage.png');
                background-size: cover;
                background-position: center;
                box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
                margin-top: 10px;
                
            }

            .login-pic{
                background-color: #C5C6C7;
            }

            footer{
                padding: 12px 0;
                margin-bottom: 0;
            }

            .btn-custom-shadow {
                width: 150px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            }

            .error-msg{
              font-size : 14px;
              color: red;
            }

            .glowing-text {
                font-weight: bold;
                font-size: 25px;
                color: black; /* Setting the font color to black */
                text-shadow: 0 0 10px white, 0 0 20px white, 0 0 30px white, 0 0 40px white;
                animation: glowing 1.5s infinite alternate;
            }

            .title-container{
                margin-top: 12px;
            }

            .footer-size{
                font-size: 11px;
            }

            .custom-btn-size{
                width: 120px;
                font-size: 15px;
            }

            h4{
              font-size: 15px;
            }
        }

        </style>
    </head>

	<body class="login-pic fade-in">
    <div class="container text-center title-container">
        <h2 class="glowing-text">FKTEN IDP</h2>
    </div>
    <div class="container text-center">
        <div class="row">
            <div class="col-md-12">
                <div class="login-box-image">
                </div>
                <div class="box">
                    <h4 class="text-center mb-3">Please Login to Proceed</h4>
                    
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                        <div class="form-floating mb-3 ms-5 me-5 custom-textbox">
                            <input type="text" class="form-control " id="nameEmail" name="nameEmail" placeholder="Enter username or email" value="<?php echo isset($_POST['nameEmail']) ? htmlspecialchars($_POST['nameEmail']) : ''; ?>">
                            <label for="nameEmail">Username or Email</label>
                        </div>
                        <!-- error message --><p><span class="error-msg"><?php echo $nameEmail_err ?></span></p>
                        <div class="form-floating mb-3 ms-5 me-5 custom-textbox">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
                            <label for="password">Password</label>
                        </div>
                        <!-- error message --><p><span class="error-msg"><?php echo $acc_err ?></span></p>
                        <div>
                            <button type="submit" id="login" name="login" class="btn btn-success btn-block login-btn mt-2 btn-custom-shadow custom-btn-size">LOGIN</button>
                        </div>
                        <div>
                            <a href="./signup/signup.php" class="btn btn-primary btn-block mt-2 btn-custom-shadow custom-btn-size">SIGN UP</a>
                        </div>
                    </form>
                    
                    <a href="./forgotPass.php" style="text-decoration: none;"><p class="mt-3" style="color: red;">Forgot your password?</p></a>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="container text-center">
		<div class="glowing-text footer-size"> Copyright 2024 FKTEN(COMPUTER ENGINEERING) UniMAP </div>
	</footer>

    <script>
        // fade-in transition
        document.addEventListener('DOMContentLoaded', function() {
        const body = document.querySelector('body');
        body.classList.add('loaded');
    });
    </script>
</body>
</html>