<?php


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
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 0px 0px 40px 40px;
                background-color: white;
                box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
                font-size: 12px;
                margin-bottom: 1px;
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

            .title-container{
                margin-top: 12px;
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

                <!-- no more form -->

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
    // to load the fade-in effect
    document.addEventListener('DOMContentLoaded', function() {
        const body = document.querySelector('body');
        body.classList.add('loaded');
    });

    // changing location when pressing the radio button
    const role1 = document.getElementById('role1');
    const role2 = document.getElementById('role2');
    const role3 = document.getElementById('role3');
    const role4 = document.getElementById('role4');

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
  </script>
</body>
</html>
