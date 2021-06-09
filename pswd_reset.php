<?php
	require_once "./config/setup.php";
	require_once "./config/email.php";
	require "./classes/userview.class.php";
	require "./classes/userctrl.class.php";

	$selector = $_GET['selector'];
	$validator = $_GET['validator'];
	if (empty($selector) || empty($validator) || ctype_xdigit($selector) === false || ctype_xdigit($validator) === false)
	{
		echo "We could not validate your request!";
		exit();
	}
?>

<html>
<head>
	<title>password reset</title>
	<link rel="stylesheet" href="./css/user_input.css">
</head>
<body>
	<div class="userform">
		<h2>Camagru</h2>
		<h3>Reset password</h3>
		<form method="post">
			<input type="password" name="passwd" placeholder="New password">
			<p>*Minimum of 6 characters. Should include at least one capital letter and one digit!</p>
			<input type="password" name="passwd2" placeholder="Re-enter new password">
        	<button type="submit">Reset password</button>
    	</form>
		<a href="signup.php">Signup</a>
		<a href="login.php">Login</a>
	</div>
</body>
</html>

<?php
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$NewPasswd = new validator($_POST);
		$NewPasswd->validate_password();
		if ($NewPasswd->error)
		{
			echo json_encode($NewPasswd->error);
			exit;
		}
		$TokenCheck = new UserView($DB_DSN, $DB_USER, $DB_PASSWORD);
		$result = $TokenCheck->get_token($selector);
		if (!$result['pwdResetToken'])
		{
			echo "Please re-submit a password reset request.";
			exit;
		}
		$tokenBin = hex2bin($validator);
		$tokenVerify = password_verify($tokenBin, $result["pwdResetToken"]);
		if ($tokenVerify === false)
		{
			echo "Please re-submit a password reset request.";
			exit;
		}
		elseif ($tokenVerify === true)
		{
			$tokenEmail = $result['pwdResetEmail'];
			$ResetPasswd = new UserCtrl($DB_DSN, $DB_USER, $DB_PASSWORD);
			$ResetPasswd->reset_password($NewPasswd->passwd, $token_email);
			$ResetPasswd->clear_pwdreset($token_email);
		}
		header("Location: login.php");
	}
?>
