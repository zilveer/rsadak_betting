<?php

require_once 'initSession.php';
require_once 'sessionManager.php';

function loginForm($email="", $userNameMessage="", $passwordMessage=""){
	echo '<form action="login.php" method="post">
	E-mail: <input type="email" name="email" value="'.$email.'">'.$userNameMessage.' <br>
	Password: <input type="password" name="password">'.$passwordMessage.'<br>
	<input type="submit" value="Login">
	<a href="register.php"><button type="button">Register</button></a>
	</form>';
}

$current_user = SessionManager::getInstance()->getCurrentUser();
if($current_user){
	// redirect to the main page
	header("Location: index.php");
	die();
}

echo
'<html>
<head>
</head>

<body>';

if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$email = $pass = "";
	
	if(isset($_POST["email"])){
		$email = $_POST["email"];
		if(!SessionManager::isEmailValid($email)){
			$email = "";
			$email_err = "Email is not valid";
		}
	}else{
		$email_err = "Email is required";
	}
	
	if(isset($_POST["password"])){
		$pass = $_POST["password"];
	}else{
		$pass_err = "Password is required";
	}
	
	$error = SessionManager::getInstance()->login($email, $pass);
	if($error == HttpStatus::$HTTP_STATUS_UNAUTHORIZED){
		loginForm($email, "", "Incorrect password");
	} else if($error == HttpStatus::$HTTP_STATUS_NOT_FOUND){
		loginForm($email, "Incorrect email");
	} else if($error == HttpStatus::$HTTP_STATUS_OK){
		header("Location: index.php");
		die();
	}
	echo $error;
}
else{
	loginForm();
}

echo
'</body>
</html>';

?>