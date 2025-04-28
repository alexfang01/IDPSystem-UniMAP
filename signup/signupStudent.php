<?php

include('../config/db_connect.php');

// initialize variable
$name_err=$ic_err=$matric_err=$progcode_err=$race_err=$cgpa_err=$email_err=$mobile_err=$pass_err=$cpass_err="";
$name=$ic=$matric=$progcode= $race= $cgpa= $email= $mobile= $pass=$cpass= "";

// Function to generate a random salt
function generate_salt() {
  return bin2hex(random_bytes(16)); // 16 bytes = 32 characters in hexadecimal
}

// Function to generate hashed password with salt
function generate_hashed_password($password, $salt) {
  return password_hash($password . $salt, PASSWORD_BCRYPT);
}

if (isset($_POST['stuSignup'])) {

    // Generate salt
    $saltTemp = generate_salt();
    $salt = mysqli_real_escape_string($conn, $saltTemp);

    $name = mysqli_real_escape_string($conn, $_POST['stuUsername']);
    $ic = mysqli_real_escape_string($conn, $_POST['stuIc']);
    $matric = mysqli_real_escape_string($conn, $_POST['stuMatric']);
    $progcode = mysqli_real_escape_string($conn, $_POST['stuProgcode']);
    $race = mysqli_real_escape_string($conn, $_POST['stuRace']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $cgpa = floatval(mysqli_real_escape_string($conn, $_POST['stuCgpa']));
    $email = mysqli_real_escape_string($conn, $_POST['stuEmail']);
    $mobile = mysqli_real_escape_string($conn, $_POST['stuMobile']);
    $pass = mysqli_real_escape_string($conn, $_POST['stuPassword']);
    $cpass = mysqli_real_escape_string($conn, $_POST['stuCheckpassword']);

    

    // initialize the var to store error msg
    $name_err=$ic_err=$matric_err=$progcode_err=$race_err=$cgpa_err=$email_err=$mobile_err=$pass_err=$cpass_err="";

    // name verification
    if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $name_err = "Name must contain only alphabets and space";
    }
    // mobile verification
    if(preg_match("/^[0-9]+$/", $mobile)){
      if(strlen($ic)<12){
        $ic_err = "Please enter valid identification number";
      }
    }else{
      $ic_err = "No special character is allowed";
    }
    // matric number verification
    if(preg_match("/^[0-9]+$/", $matric)){
      if(strlen($matric) < 5){
        $matric_err = "Please enter valid matric number";
      }
    }else{
      $matric_err = "No special character is allowed";
    }
    // programme code verification
    if (!preg_match("/^[a-zA-Z0-9 ]+$/", $progcode)) {
      $progcode_err = "Must contain only alphabets and numbers";
    }
    // race verification
    if (!$race){
      $race_err = "Please select your race";
    }
    // cgpa verification
    if (!filter_var($cgpa, FILTER_VALIDATE_FLOAT)) {
      $cgpa_err = "Please enter in format: 3.00";
    }
    // email verification
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter valid email";
    }
    // mobile verification
    if (preg_match("/^[0-9]+$/", $mobile)){
        if(strlen($mobile) < 10){
          $mobile_err = "Mobile number must be a minimum of 10 characters";
        }
    }else{
      $mobile_err = "No special character is allowed";
    }
    // password 
    if (strlen($pass) < 8) {
      $pass_err = "Password must be a minimum of 8 characters";
    }
    if ($cpass != $pass || $cpass < 8) {
        $cpass_err = "Password doesn't match";
    }
    // saved data to database if no error
    if (!$name_err && !$ic_err && !$matric_err && !$progcode_err && !$race_err && !$cgpa_err && !$email_err && !$mobile_err && !$pass_err && !$cpass_err) {
      // Generate hashed password with salt
      $hashed_password = generate_hashed_password($pass, $salt);

      $sql = "INSERT INTO student(name, ic, matric, prog, race, gender, cgpa, phnum, email, pass, verify, salt) VALUES ('$name', '$ic', '$matric', '$progcode', '$race', '$gender', '$cgpa', '$mobile', '$email', '$hashed_password', 0, '$salt')";
        if(mysqli_query($conn, $sql)) {
          // Set session variable for success message
          $_SESSION['success_message'] = "Account Registered, Wait for Approval";
        }else{
          echo 'query error'.mysqli_error($conn);
        }
      }
    mysqli_close($conn);
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IDP</title>
<!--   <link rel="shortcut icon" type="image/png" href="assets/images/logos/unimaptab.png" /> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> <!-- CDN -->
<!--   <link rel="stylesheet" href="assets/css/styles.min.css" /> -->
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
            
            /* avoid scroll bar to shift the content */
            body {
                overflow-y: scroll;
            }

            .login-box-image {
            max-width: 500px;
            margin: auto;
            margin-top: 50px;
            padding: 40px;
            border: 0px solid #ccc;
            border-radius: 40px 40px 0px 0px;
            background-image: url('../assets/c2/loginImage.png');
            background-size: cover;
            background-position: 0% 80%;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            }

            .login-pic{
            background-color: #C5C6C7;
            }

            .custom-textbox{
                font-size: 13px;
            }

            .btn-custom-shadow {
                width: 150px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            }

            footer {
            margin-top: auto; 
            padding: 20px 0;
            }

            .glowing-text {
            font-weight: bold;
            color: black; /* Setting the font color to black */
            text-shadow: 0 0 10px white, 0 0 20px white, 0 0 30px white, 0 0 40px white;
            animation: glowing 1.5s infinite alternate;
            }

            .fade-in {
                opacity: 0;
                transition: opacity 0.7s ease-in-out;
            }

            .fade-in.loaded {
                opacity: 1;
            }

            .btn-custom-shadow {
                width: 150px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            }

            .error-msg{
              font-size : 12px;
              color: red;
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

            .custom-drop{
              width: 363px; 
              padding: 17px; 
              font-size: 14px;
            }
          
        @media(max-width: 768px){
          .box {
              max-width: none; 
              width: 100%;
              padding: 20px;
              border: 1px solid #ccc;
              border-radius: 0px 0px 40px 40px;
              background-color: white;
              box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            }
            
            /* avoid scroll bar to shift the content */
            body {
                overflow-y: scroll;
            }

            .login-box-image {
              width: 100%;
              padding: 30px;
              border: 0px solid #ccc;
              border-radius: 40px 40px 0px 0px;
              background-image: url('../assets/c2/loginImage.png');
              background-size: cover;
              background-position: 0% 80%;
              box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
              margin-top: 10px;
            }

            .login-pic{
            background-color: #C5C6C7;
            }

            .custom-textbox{
                font-size: 13px;
            }

            .btn-custom-shadow {
                width: 150px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            }

            footer {
              padding: 12px 0;
              margin-bottom: 0;
            }

            .glowing-text {
              font-weight: bold;
              font-size: 25px;
              color: black; /* Setting the font color to black */
              text-shadow: 0 0 10px white, 0 0 20px white, 0 0 30px white, 0 0 40px white;
              animation: glowing 1.5s infinite alternate;
            }

            .fade-in {
                opacity: 0;
                transition: opacity 0.7s ease-in-out;
            }

            .fade-in.loaded {
                opacity: 1;
            }

            .btn-custom-shadow {
                width: 150px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            }

            .footer-size{
                font-size: 11px;
            }

            .custom-btn-size{
                width: 80px;
                font-size: 12px;
            }

            .btn-group{
                height: 35px;
            }

            .title-container{
                margin-top: 12px;
            }
            
            .error-msg{
              font-size : 12px;
              color: red;
            }

            .custom-btn-signup{
                width: 120px;
                font-size: 15px;
            }

            h4{
              font-size: 15px;
            }

            .custom-drop{
              width: 215px; 
              padding: 17px; 
              font-size: 14px;
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
            <h4 class="text-center mb-3">Sign up as:</h4>
            <div class="btn-group" role="group" aria-label="Horizontal radio toggle button group">
              <input type="radio" class="btn-check" name="role-select" id="role1" autocomplete="off">
              <label class="btn btn-outline-success custom-btn-size" for="role1">Student</label>
              <input type="radio" class="btn-check" name="role-select" id="role2" autocomplete="off">
              <label class="btn btn-outline-warning custom-btn-size" for="role2">Supervisor</label>
              <input type="radio" class="btn-check" name="role-select" id="role3" autocomplete="off">
              <label class="btn btn-outline-danger custom-btn-size" for="role3">Committee</label>
              <input type="radio" class="btn-check" name="role-select" id="role4" autocomplete="off">
              <label class="btn btn-outline-info custom-btn-size" for="role4">Panel</label>
            </div>

            <h4 class="text-center mt-3 mb-3" id="label1">Please fill all the fields below</h4>
            <!-- Student form -->
          <form method="post" action="signupStudent.php" id="stuSignupForm">
          <!-- Student signup content -->
          <div id="studentContent">
            <div class="form-floating mb-3 ms-5 me-5 custom-textbox">
                <input required type="text" class="form-control" id="stuUsername" name="stuUsername" placeholder="Enter username" value="<?php echo isset($_POST['stuUsername']) ? htmlspecialchars($_POST['stuUsername']) : ''; ?>">
                <label for="stuUsername">Username</label>
            </div>
              <!-- error message --><p><span class="error-msg"><?php echo $name_err ?></span></p>
            <div class="form-floating mb-3 ms-5 me-5 custom-textbox">
                <input required type="text" class="form-control" id="stuIc" name="stuIc" placeholder="Enter identification number" value="<?php echo isset($_POST['stuIc']) ? htmlspecialchars($_POST['stuIc']) : ''; ?>">
                <label for="stuIc">IC</label>
            </div>
            <!-- error message --><p><span class="error-msg"><?php echo $ic_err ?></span></p>
            <div class="form-floating mb-3 ms-5 me-5 custom-textbox">
                <input required type="text" class="form-control" id="stuMatric" name="stuMatric"placeholder="Enter matric number" value="<?php echo isset($_POST['stuMatric']) ? htmlspecialchars($_POST['stuMatric']) : ''; ?>">
                <label for="stuMatric">Matric Number</label>
            </div>
            <!-- error message --><p><span class="error-msg"><?php echo $matric_err ?></span></p>
            <div class="form-floating mb-3 ms-5 me-5 custom-textbox">
                <input required type="text" class="form-control" id="stuProgcode" name="stuProgcode" placeholder="Enter programme code" value="<?php echo isset($_POST['stuProgcode']) ? htmlspecialchars($_POST['stuProgcode']) : ''; ?>">
                <label for="stuProgcode">Programme Code</label>
            </div>
            <!-- error message --><p><span class="error-msg"><?php echo $progcode_err ?></span></p>
            <div>
            <select required id="stuRace" name="stuRace" class="form-select mb-3 ms-5 me-5 custom-drop"  aria-label="Select Race">
              <option selected value="" <?php echo isset($_POST['stuRace']) && $_POST['stuRace'] == '' ? 'selected' : ''; ?>>Race</option>
              <option value="Malay" <?php echo isset($_POST['stuRace']) && $_POST['stuRace'] == 'Malay' ? 'selected' : ''; ?>>Malay</option>
              <option value="Indian" <?php echo isset($_POST['stuRace']) && $_POST['stuRace'] == 'Indian' ? 'selected' : ''; ?>>Indian</option>
              <option value="Chinese" <?php echo isset($_POST['stuRace']) && $_POST['stuRace'] == 'Chinese' ? 'selected' : ''; ?>>Chinese</option>
              <option value="Others" <?php echo isset($_POST['stuRace']) && $_POST['stuRace'] == 'Others' ? 'selected' : ''; ?>>Others</option>
            </select>

            <select required id="gender" name="gender" class="form-select mb-3 ms-5 me-5 custom-drop"  aria-label="Select Gender">
              <option selected value="" <?php echo isset($_POST['gender']) && $_POST['gender'] == '' ? 'selected' : ''; ?>>Gender</option>
              <option value="female" <?php echo isset($_POST['gender']) && $_POST['gender'] == 'female' ? 'selected' : ''; ?>>female</option>
              <option value="male" <?php echo isset($_POST['gender']) && $_POST['gender'] == 'male' ? 'selected' : ''; ?>>male</option>
            </select>

            </div>
            <!-- error message --><p><span class="error-msg"><?php echo $race_err ?></span></p>
            <div class="form-floating mb-3 ms-5 me-5 custom-textbox">
                <input required type="text" class="form-control" id="stuCgpa" name="stuCgpa" placeholder="Enter cgpa" value="<?php echo isset($_POST['stuCgpa']) ? htmlspecialchars($_POST['stuCgpa']) : ''; ?>">
                <label for="stuCgpa">CGPA</label>
            </div>
            <!-- error message --><p><span class="error-msg"><?php echo $cgpa_err ?></span></p>
            <div class="form-floating mb-3 ms-5 me-5 custom-textbox">
                <input required type="text" class="form-control" id="stuEmail" name="stuEmail" placeholder="Enter email" value="<?php echo isset($_POST['stuEmail']) ? htmlspecialchars($_POST['stuEmail']) : ''; ?>">
                <label for="stuEmail">Email Address</label>
            </div>
            <!-- error message --><p><span class="error-msg"><?php echo $email_err ?></span></p>
            <div class="form-floating mb-3 ms-5 me-5 custom-textbox">
                <input required type="text" class="form-control" id="stuMobile" name="stuMobile" placeholder="Enter mobile number" value="<?php echo isset($_POST['stuMobile']) ? htmlspecialchars($_POST['stuMobile']) : ''; ?>">
                <label for="stuMobile">Mobile</label>
            </div>
            <!-- error message --><p><span class="error-msg"><?php echo $mobile_err ?></span></p>
            <div class="form-floating mb-3 ms-5 me-5 custom-textbox">
                <input required type="password" class="form-control" id="stuPassword" name="stuPassword" placeholder="Enter password" value="<?php echo isset($_POST['stuPassword']) ? htmlspecialchars($_POST['stuPassword']) : ''; ?>">
                <label for="stuPassword">Password</label>
            </div>
            <!-- error message --><p><span class="error-msg"><?php echo $pass_err ?></span></p>
            <div class="form-floating mb-3 ms-5 me-5 custom-textbox">
                <input required type="password" class="form-control" id="stuCheckpassword" name="stuCheckpassword" placeholder="Enter password again" value="<?php echo isset($_POST['stuCheckpassword']) ? htmlspecialchars($_POST['stuCheckpassword']) : ''; ?>">
                <label for="stuCheckpassword">Confirm Password</label>
            </div>
            <!-- error message --><p><span class="error-msg"><?php echo $cpass_err ?></span></p>
            <button type="submit" id="stuSignup" name="stuSignup" class="btn btn-success btn-block login-btn btn-custom-shadow custom-btn-signup">REGISTER</button>
          </div>
          </form>

            <a href="../login.php" style="text-decoration: none;"><p class="mt-3" style="color: green;">Already have an account</p></a>
        </div>
        </div>
    </div>
    </div>
    
    <footer class="container text-center">
		<div class="glowing-text footer-size"> Copyright 2024 FKTEN(COMPUTER ENGINEERING) UniMAP </div>
	</footer>

  <!-- script stuff for fancy thing -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const body = document.querySelector('body');
        body.classList.add('loaded');
    });

    // navigating using button
    const role1 = document.getElementById('role1');
    const role2 = document.getElementById('role2');
    const role3 = document.getElementById('role3');
    const role4 = document.getElementById('role4');
    role1.checked = true; // let it stay checked

    // Add event listeners to radio buttons
    role1.addEventListener('click', function() {
        window.location.href = 'signupStudent.php'; // Redirect to student signup page
    });

    role2.addEventListener('click', function() {
        window.location.href = 'signupSV.php'; // Redirect to supervisor signup page
    });

    role3.addEventListener('click', function() {
        window.location.href = 'signupCom.php'; // Redirect to committee signup page
    });

    role4.addEventListener('click', function() {
        window.location.href = 'signupPanel.php'; // Redirect to committee signup page
    });

    // pop up successful message before going to login page
    <?php if(isset($_SESSION['success_message'])): ?>
            alert('<?php echo $_SESSION['success_message']; ?>');
            window.location.href = '../login.php';
            unset($_SESSION['success_message']);
    <?php endif; ?>

  </script>
</script>
</body>

</html>
