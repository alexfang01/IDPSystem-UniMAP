<?php

$conn = mysqli_connect('localhost', 'root', '', 'IDPsystem');


if(!$conn){
	echo 'Connection error: '.mysqli_connect_error();
}

?>