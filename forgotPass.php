<?php

// Function to generate a temporary password
function password_generate($chars) {
    $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($data), 0, $chars);
}

// Function to generate a random salt
function generate_salt() {
    return bin2hex(random_bytes(16)); // 16 bytes = 32 characters in hexadecimal
  }
  
  // Function to generate hashed password with salt
  function generate_hashed_password($password, $salt) {
    return password_hash($password . $salt, PASSWORD_BCRYPT);
  }

if(isset($_POST['submit'])){
    // Start the session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // to ensure user not spamming the reset email thing
    // Rate limiting logic
    $max_requests = 3;
    $time_frame = 3600; // 1 hour in seconds

    if (!isset($_SESSION['reset_requests'])) {
        $_SESSION['reset_requests'] = [];
    }
    // Clean up old requests
    $_SESSION['reset_requests'] = array_filter($_SESSION['reset_requests'], function ($timestamp) use ($time_frame) {
        return $timestamp > (time() - $time_frame);
    });
    // Check if the number of requests exceeds the maximum allowed
    if (count($_SESSION['reset_requests']) >= $max_requests) {
        die('Too many password reset requests. Please try again later.');
    }
    // Log the current request
    $_SESSION['reset_requests'][] = time();
    // rate limiting logic end

    // Connect to database
    include('./config/db_connect.php');


    $nameEmail = mysqli_real_escape_string($conn, $_POST['nameEmail']);
    $matricStaff = mysqli_real_escape_string($conn, $_POST['matricStaff']);
    $matricStaff = mysqli_real_escape_string($conn, $_POST['matricStaff']);

    // check which user type match
    $sql = "SELECT * FROM student WHERE (name = '$nameEmail' OR email = '$nameEmail') AND matric='$matricStaff'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) < 0){
        // means not student
        $sql = "SELECT * FROM supervisor WHERE (name = '$nameEmail' OR email = '$nameEmail') AND staffnum='$matricStaff'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) < 0){
            // means not supervisor, student
            $sql = "SELECT * FROM committee WHERE (name = '$nameEmail' OR email = '$nameEmail') AND staffnum='$matricStaff'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) < 0){
                // means not supervisor, student, committee
                $sql = "SELECT * FROM panel WHERE (name = '$nameEmail' OR email = '$nameEmail') AND panelnum='$matricStaff'";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) < 0){
                    // user not exist
                    header('Location:forgotPass.php?error=No such user found for the entered username or email address');
                    exit();
                }else{
                    // is a panel
                    // generate password
                    $temp_pwd = password_generate(8);
                    // Generate salt
                    $saltTemp = generate_salt();
                    $salt = mysqli_real_escape_string($conn, $saltTemp);
                    // password hashing
                    $hashed_password = generate_hashed_password($temp_pwd, $salt);

                    $info = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    $sql = "UPDATE panel SET pass='$hashed_password', salt='$salt' WHERE (name = '$nameEmail' OR email = '$nameEmail') AND panel='$matricStaff'";
                
                    $conn->close();

                    $to_email = $data['email'];
                    $subject = "IDP System Account Password Reset";
                    $body = "Dear Panel ,<br><br>Your password has been reset to " . $temp_pwd;
                    $body .= ".<br> Please use this new temporary password to login to the IDP System. ";
                    $body .= "<br><br>Thank you.<br>Yours sincerely,<br>IDP Team";
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
                    $headers .= "from: fypsccesv@gmail.com"; // change to IDP ?

                    // only can be done if php.ini able to mail the email with proper server
                    if (mail($to_email, $subject, $body, $headers)) {
                        $emailMsg = "New Password Successfully Sent to $to_email.";
                    } else {
                        $emailMsg = "New Password Sending Failed.";
                    }
                    header('Location:login.php?error='.$emailMsg);
                    exit();
                }

            }else{
                // is a committee
                // generate password
                $temp_pwd = password_generate(8);
                // Generate salt
                $saltTemp = generate_salt();
                $salt = mysqli_real_escape_string($conn, $saltTemp);
                // password hashing
                $hashed_password = generate_hashed_password($temp_pwd, $salt);

                $info = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $sql = "UPDATE committee SET pass='$hashed_password', salt='$salt' WHERE (name = '$nameEmail' OR email = '$nameEmail') AND staffnum='$matricStaff'";
            
                $conn->close();

                $to_email = $data['email'];
                $subject = "IDP System Account Password Reset";
                $body = "Dear Committee,<br><br>Your password has been reset to " . $temp_pwd;
                $body .= ".<br> Please use this new temporary password to login to the IDP System. ";
                $body .= "<br><br>Thank you.<br>Yours sincerely,<br>IDP Team";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
                $headers .= "from: fypsccesv@gmail.com"; // change to IDP ?

                // only can be done if php.ini able to mail the email with proper server
                if (mail($to_email, $subject, $body, $headers)) {
                    $emailMsg = "New Password Successfully Sent to $to_email.";
                } else {
                    $emailMsg = "New Password Sending Failed.";
                }
                header('Location:login.php?error='.$emailMsg);
                exit();
            }

        }else{
            // is a supervisor
            // generate password
            $temp_pwd = password_generate(8);
            // Generate salt
            $saltTemp = generate_salt();
            $salt = mysqli_real_escape_string($conn, $saltTemp);
            // password hashing
            $hashed_password = generate_hashed_password($temp_pwd, $salt);

            $info = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $sql = "UPDATE supervisor SET pass='$hashed_password', salt='$salt' WHERE (name = '$nameEmail' OR email = '$nameEmail') AND staffnum='$matricStaff'";
        
            $conn->close();

            $to_email = $data['email'];
            $subject = "IDP System Account Password Reset";
            $body = "Dear Supervisor,<br><br>Your password has been reset to " . $temp_pwd;
            $body .= ".<br> Please use this new temporary password to login to the IDP System. ";
            $body .= "<br><br>Thank you.<br>Yours sincerely,<br>IDP Team";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
            $headers .= "from: fypsccesv@gmail.com"; // change to IDP ?

            // only can be done if php.ini able to mail the email with proper server
            if (mail($to_email, $subject, $body, $headers)) {
                $emailMsg = "New Password Successfully Sent to $to_email.";
            } else {
                $emailMsg = "New Password Sending Failed.";
            }
            header('Location:login.php?error='.$emailMsg);
            exit();
        }
    }
    else{
        // is a student
        // generate password
        $temp_pwd = password_generate(8);
        // Generate salt
        $saltTemp = generate_salt();
        $salt = mysqli_real_escape_string($conn, $saltTemp);
        // password hashing
        $hashed_password = generate_hashed_password($temp_pwd, $salt);

        $info = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $sql = "UPDATE student SET pass='$hashed_password', salt='$salt' WHERE (name = '$nameEmail' OR email = '$nameEmail') AND matric='$matricStaff'";
    
        $conn->close();

        $to_email = $data['email'];
        $subject = "IDP System Account Password Reset";
        $body = "Dear Student,<br><br>Your password has been reset to " . $temp_pwd;
        $body .= ".<br> Please use this new temporary password to login to the IDP System. ";
        $body .= "<br><br>Thank you.<br>Yours sincerely,<br>IDP Team";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
        $headers .= "from: fypsccesv@gmail.com"; // change to IDP ?

        // only can be done if php.ini able to mail the email with proper server
        if (mail($to_email, $subject, $body, $headers)) {
            $emailMsg = "New Password Successfully Sent to $to_email.";
        } else {
            $emailMsg = "New Password Sending Failed.";
        }
        header('Location:login.php?error='.$emailMsg);
        exit();
    }
        
}


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

            .custom-forget-msg{
                text-align: left;
                display: block;
                margin-bottom: 30px;
                margin-right: 10%;
                margin-left: 10%;
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

            .custom-forget-msg{
                text-align: left;
                display: block;
                margin-bottom: 30px;
                margin-right: 10%;
                margin-left: 10%;
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
                    <h4 class="text-center mb-3">Reset Password</h4>
                    <div class= "custom-forget-msg">
                        <h8 class="forget-text"><strong>1.</strong>  Enter your username/email and matric/staff number. </h8><br>
                        <h8 class="forget-text"><strong>2.</strong>  A new temporary password will be generated and send to your email. </h8><br>
                        <h8 class="forget-text"><strong>3.</strong>  During login, please remember to change your password in the system. </h8><br>
                        <h8 class="forget-text"><strong>4.</strong>  If forgot username or email no longer valid, please inform IDP coordinator </h8>
                    </div>
                    
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                        <div class="form-floating mb-3 ms-5 me-5 custom-textbox">
                            <input type="text" class="form-control " id="nameEmail" name="nameEmail" placeholder="Enter username or email" value="<?php echo isset($_POST['nameEmail']) ? htmlspecialchars($_POST['nameEmail']) : ''; ?>">
                            <label for="nameEmail">Username / Email</label>
                        </div>
                        <div class="form-floating mb-3 ms-5 me-5 custom-textbox">
                            <input type="text" class="form-control " id="matricStaff" name="matricStaff" placeholder="Enter matric or staff number" value="<?php echo isset($_POST['matricStaff']) ? htmlspecialchars($_POST['matricStaff']) : ''; ?>">
                            <label for="nameEmail">Matric / Staff / Panel Number</label>
                        </div>
                        <div>
                            <button type="submit" id="submit" name="submit" class="btn btn-success btn-block login-btn mt-2 btn-custom-shadow custom-btn-size">RESET</button>
                        </div>
                    </form>
                    <a href="./login.php" style="text-decoration: none;"><p class="mt-3" style="color: green;">Back to Login</p></a>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="container text-center">
		<div class="glowing-text footer-size"> Copyright 2024 FKTEN(COMPUTER ENGINEERING) UniMAP </div>
	</footer>

    <!-- jQuery 2.2.3 -->
    <script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="../../bootstrap/js/bootstrap.min.js"></script>


    <script>
        // fade-in transition
        document.addEventListener('DOMContentLoaded', function() {
        const body = document.querySelector('body');
        body.classList.add('loaded');
    });
    </script>
</body>
</html>
